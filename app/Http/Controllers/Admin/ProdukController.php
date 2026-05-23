<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProdukStoreRequest;
use App\Http\Requests\Admin\ProdukUpdateRequest;
use App\Models\Produk;
use App\Models\ProdukImage;
use App\Models\Kategori;
use App\Models\Merk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['kategori', 'merk', 'images']);
        
        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Filter by kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        
        // Filter by merk
        if ($request->filled('merk_id')) {
            $query->where('merk_id', $request->merk_id);
        }
        
        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        $produks = $query->latest()->paginate(20);
        $kategoris = Kategori::active()->get();
        $merks = Merk::active()->get();
        
        return view('admin.produk.index', compact('produks', 'kategoris', 'merks'));
    }

    public function create()
    {
        $kategoris = Kategori::active()->ordered()->get();
        $merks = Merk::active()->orderBy('nama')->get();
        
        return view('admin.produk.create', compact('kategoris', 'merks'));
    }

    public function store(ProdukStoreRequest $request)
    {
        $data = $request->validated();
        // Buang field non-DB sebelum mass assignment (file upload bukan kolom)
        unset($data['images']);

        // Auto generate SKU jika kosong
        if (empty($data['sku'])) {
            // Cari nomor SKU tertinggi yang sudah ada (termasuk yang soft-deleted)
            $maxSku = Produk::withTrashed()
                ->where('sku', 'like', 'BRG-%')
                ->orderByRaw('CAST(SUBSTRING(sku, 5) AS UNSIGNED) DESC')
                ->value('sku');
            
            $number = $maxSku ? intval(substr($maxSku, 4)) + 1 : 1;
            $data['sku'] = 'BRG-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        }
        
        // Auto generate slug unik
        $data['slug'] = $this->generateUniqueSlug($request->nama);
        
        $produk = Produk::create($data);
        
        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                // Pakai guessExtension() agar tidak percaya extension dari client; whitelist tipe
                $ext = strtolower($image->guessExtension() ?? '');
                if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                    continue;
                }
                $imageName = Str::uuid()->toString() . '.' . $ext;
                $image->storeAs('produk/' . $produk->id, $imageName, 'public');

                ProdukImage::create([
                    'produk_id' => $produk->id,
                    'image_path' => 'produk/' . $produk->id . '/' . $imageName,
                    'is_primary' => $index === 0,
                    'urutan' => $index + 1,
                ]);
            }
        }
        
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show($id)
    {
        $produk = Produk::with(['kategori', 'merk', 'images'])->findOrFail($id);
        
        return view('admin.produk.detail', compact('produk'));
    }

    public function edit($id)
    {
        $produk = Produk::with('images')->findOrFail($id);
        $kategoris = Kategori::active()->ordered()->get();
        $merks = Merk::active()->orderBy('nama')->get();
        
        return view('admin.produk.edit', compact('produk', 'kategoris', 'merks'));
    }

    public function update(ProdukUpdateRequest $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $data = $request->validated();
        unset($data['images']);
        $data['slug'] = $this->generateUniqueSlug($request->nama, (int) $produk->id);

        $produk->update($data);
        
        // Upload new images
        if ($request->hasFile('images')) {
            $existingImagesCount = $produk->images()->count();

            foreach ($request->file('images') as $index => $image) {
                $ext = strtolower($image->guessExtension() ?? '');
                if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                    continue;
                }
                $imageName = Str::uuid()->toString() . '.' . $ext;
                $image->storeAs('produk/' . $produk->id, $imageName, 'public');

                ProdukImage::create([
                    'produk_id' => $produk->id,
                    'image_path' => 'produk/' . $produk->id . '/' . $imageName,
                    'is_primary' => false,
                    'urutan' => $existingImagesCount + $index + 1,
                ]);
            }
        }
        
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete(); // Soft delete
        
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function export()
    {
        // TODO: Implement Excel export
        return back()->with('info', 'Fitur export akan segera tersedia.');
    }

    // Buat slug unik dari nama produk; cek juga soft-deleted agar tidak bentrok unique constraint
    private function generateUniqueSlug(string $nama, ?int $ignoreId = null): string
    {
        $base = Str::slug($nama);
        if ($base === '') {
            $base = 'produk';
        }

        $slug = $base;
        $i = 1;

        while (
            Produk::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId !== null, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
