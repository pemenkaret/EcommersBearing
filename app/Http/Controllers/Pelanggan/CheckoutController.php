<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pelanggan\CheckoutRequest;
use App\Models\Keranjang;
use App\Models\MetodePembayaran;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\Ongkir;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function showCheckoutForm()
    {
        $keranjangs = Keranjang::with(['produk.images', 'produk.merk'])
            ->where('user_id', auth()->id())
            ->get();
        
        if ($keranjangs->isEmpty()) {
            return redirect()->route('pelanggan.keranjang.index')->with('error', 'Keranjang Anda kosong.');
        }
        
        $user = auth()->user();
        $alamats = $user->alamats;
        $defaultAlamat = $user->getDefaultAlamat();
        $metodes = MetodePembayaran::where('is_active', true)->get();
        
        $subtotal = Keranjang::getGrandTotal(auth()->id());
        
        return view('pelanggan.checkout.index', compact('keranjangs', 'alamats', 'defaultAlamat', 'subtotal', 'metodes'));
    }

    public function showBuyNowForm(Request $request, $produkId)
    {
        $produk = Produk::with('images', 'merk')->findOrFail($produkId);
        
        if ($produk->stok < 1) {
            return back()->with('error', 'Maaf, produk ini sedang tidak tersedia.');
        }
        
        $quantity = $request->get('quantity', 1);
        
        // Validasi quantity
        if ($quantity > $produk->stok) {
            $quantity = $produk->stok;
        }
        
        $user = auth()->user();
        $alamats = $user->alamats;
        $defaultAlamat = $user->getDefaultAlamat();
        $metodes = MetodePembayaran::where('is_active', true)->get();
        
        $harga = $produk->harga_diskon ?? $produk->harga;
        $subtotal = $harga * $quantity;
        
        return view('pelanggan.checkout.buy-now', compact('produk', 'quantity', 'alamats', 'defaultAlamat', 'subtotal', 'harga', 'metodes'));
    }

    public function processCheckout(CheckoutRequest $request)
    {
        $keranjangs = Keranjang::with('produk')
            ->where('user_id', auth()->id())
            ->get();
        
        if ($keranjangs->isEmpty()) {
            return back()->with('error', 'Keranjang Anda kosong.');
        }
        
        DB::beginTransaction();
        try {
            // Lock produk rows untuk mencegah race condition stok
            $produkIds = $keranjangs->pluck('produk_id')->all();
            $lockedProduks = Produk::whereIn('id', $produkIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Validasi stok pakai data terkunci
            foreach ($keranjangs as $item) {
                $lockedProduk = $lockedProduks->get($item->produk_id);
                if (!$lockedProduk || $lockedProduk->stok < $item->quantity) {
                    throw new \Exception("Stok {$item->produk->nama} tidak mencukupi.");
                }
            }
            
            // Get alamat
            $alamat = auth()->user()->alamats()->findOrFail($request->alamat_id);
            
            // Get metode pembayaran
            $metodePembayaran = MetodePembayaran::findOrFail($request->metode_pembayaran_id);
            
            // Calculate subtotal
            $subtotal = $keranjangs->sum(function($item) {
                return $item->subtotal;
            });

            // Calculate ongkir
            $ongkirData = Ongkir::hitungOngkirDenganSubtotal($alamat->provinsi, $alamat->kota, $subtotal);
            $ongkir = $ongkirData['tarif'];
            
            // Create Order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'alamat_id' => $alamat->id,
                'alamat_penerima' => $alamat->penerima,
                'alamat_telepon' => $alamat->telepon,
                'alamat_lengkap' => $alamat->alamat_lengkap,
                'alamat_provinsi' => $alamat->provinsi,
                'alamat_kota' => $alamat->kota,
                'alamat_kecamatan' => $alamat->kecamatan,
                'alamat_kode_pos' => $alamat->kode_pos,
                'subtotal' => $subtotal,
                'ongkir' => $ongkir,
                'total' => $subtotal + $ongkir,
                'metode_pembayaran' => $metodePembayaran->nama,
                'metode_pembayaran_id' => $metodePembayaran->id,
                'catatan' => $request->catatan,
                'status' => 'pending',
            ]);
            
            // Create Order Items
            foreach ($keranjangs as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'produk_id' => $item->produk_id,
                    'produk_nama' => $item->produk->nama,
                    'produk_sku' => $item->produk->sku,
                    'produk_image' => $item->produk->primary_image?->image_path,
                    'harga' => $item->harga,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);

                // Decrement stok pakai produk yang sudah dikunci
                $lockedProduk = $lockedProduks->get($item->produk_id);
                $lockedProduk->decrement('stok', $item->quantity);
                $lockedProduk->increment('sold_count', $item->quantity);
            }
            
            // Create Order Status
            $order->updateStatus('pending', 'Pesanan dibuat, menunggu pembayaran');
            
            // Clear keranjang
            Keranjang::where('user_id', auth()->id())->delete();
            
            DB::commit();
            
            return redirect()->route('pelanggan.pembelian.show', $order->order_number)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'quantity' => 'required|integer|min:1',
            'alamat_id' => 'required|exists:alamats,id',
            'metode_pembayaran_id' => 'required|exists:metode_pembayarans,id',
        ]);

        DB::beginTransaction();
        try {
            // Lock produk untuk mencegah race condition stok
            $produk = Produk::where('id', $request->produk_id)->lockForUpdate()->firstOrFail();

            if ($produk->stok < $request->quantity) {
                throw new \Exception('Stok tidak mencukupi.');
            }

            $alamat = auth()->user()->alamats()->findOrFail($request->alamat_id);
            $metodePembayaran = MetodePembayaran::findOrFail($request->metode_pembayaran_id);
            
            $harga = $produk->harga_diskon ?? $produk->harga;
            $subtotal = $harga * $request->quantity;

            // Calculate ongkir
            $ongkirData = Ongkir::hitungOngkirDenganSubtotal($alamat->provinsi, $alamat->kota, $subtotal);
            $ongkir = $ongkirData['tarif'];
            
            // Create Order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'alamat_id' => $alamat->id,
                'alamat_penerima' => $alamat->penerima,
                'alamat_telepon' => $alamat->telepon,
                'alamat_lengkap' => $alamat->alamat_lengkap,
                'alamat_provinsi' => $alamat->provinsi,
                'alamat_kota' => $alamat->kota,
                'alamat_kecamatan' => $alamat->kecamatan,
                'alamat_kode_pos' => $alamat->kode_pos,
                'subtotal' => $subtotal,
                'ongkir' => $ongkir,
                'total' => $subtotal + $ongkir,
                'metode_pembayaran' => $metodePembayaran->nama,
                'metode_pembayaran_id' => $metodePembayaran->id,
                'status' => 'pending',
            ]);
            
            // Create Order Item
            OrderItem::create([
                'order_id' => $order->id,
                'produk_id' => $produk->id,
                'produk_nama' => $produk->nama,
                'produk_sku' => $produk->sku,
                'produk_image' => $produk->primary_image?->image_path,
                'harga' => $harga,
                'quantity' => $request->quantity,
                'subtotal' => $subtotal,
            ]);
            
            // Decrement stok (produk sudah dikunci di atas)
            $produk->decrement('stok', $request->quantity);
            $produk->increment('sold_count', $request->quantity);
            
            // Create Order Status
            $order->updateStatus('pending', 'Pesanan dibuat, menunggu pembayaran');
            
            DB::commit();
            
            return redirect()->route('pelanggan.pembelian.show', $order->order_number)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
