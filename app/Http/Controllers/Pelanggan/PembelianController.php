<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.produk.images'])
            ->where('user_id', auth()->id());
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->dateRange($request->tanggal_mulai, $request->tanggal_akhir);
        }
        
        $orders = $query->latest()->paginate(10);
        
        return view('pelanggan.pembelian.riwayat', compact('orders'));
    }

    public function show($orderNumber)
    {
        $order = Order::with(['items.produk.images', 'statuses'])
            ->where('user_id', auth()->id())
            ->where('order_number', $orderNumber)
            ->firstOrFail();
        
        return view('pelanggan.pembelian.detail', compact('order'));
    }

    public function uploadBuktiPembayaran(Request $request, $id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);
        
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if ($request->hasFile('bukti_pembayaran')) {
            // Hapus bukti lama jika ada
            if ($order->bukti_pembayaran && Storage::disk('public')->exists($order->bukti_pembayaran)) {
                Storage::disk('public')->delete($order->bukti_pembayaran);
            }

            $file = $request->file('bukti_pembayaran');
            $ext = strtolower($file->guessExtension() ?? 'jpg');
            $fileName = 'bukti_' . $order->order_number . '_' . \Illuminate\Support\Str::uuid()->toString() . '.' . $ext;
            $path = $file->storeAs('bukti-pembayaran', $fileName, 'public');

            if ($path) {
                $order->update([
                    'bukti_pembayaran' => $path,
                ]);

                return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
            }
        }
        
        return back()->with('error', 'Gagal upload bukti pembayaran.');
    }

    public function cancel(Request $request, $id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);
        
        $request->validate([
            'cancelled_reason' => 'required|string',
        ]);
        
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }
        
        $order->cancel($request->cancelled_reason);
        
        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
