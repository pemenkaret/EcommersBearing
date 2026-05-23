<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetodePembayaran;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class MetodePembayaranController extends Controller
{
    public function index()
    {
        $metodePembayarans = MetodePembayaran::ordered()->paginate(10);
        return view('admin.metode-pembayaran.index', compact('metodePembayarans'));
    }

    public function create()
    {
        return view('admin.metode-pembayaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
            'tipe' => 'required|in:transfer,cod,ewallet',
            'bank_nama' => 'nullable|required_if:tipe,transfer|string|max:100',
            'bank_rekening' => 'nullable|required_if:tipe,transfer|string|max:50',
            'bank_atas_nama' => 'nullable|required_if:tipe,transfer|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'instruksi' => 'nullable|string',
            'is_active' => 'boolean',
            'urutan' => 'nullable|integer',
        ], [
            'nama.required' => 'Nama metode pembayaran wajib diisi',
            'tipe.required' => 'Tipe pembayaran wajib dipilih',
            'bank_nama.required_if' => 'Nama bank wajib diisi untuk tipe transfer',
            'bank_rekening.required_if' => 'Nomor rekening wajib diisi untuk tipe transfer',
            'bank_atas_nama.required_if' => 'Nama pemilik rekening wajib diisi untuk tipe transfer',
        ]);

        $data = $request->only(['nama', 'deskripsi', 'tipe', 'bank_nama', 'bank_rekening', 'bank_atas_nama', 'instruksi', 'urutan']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('metode-pembayaran', 'public');
        }

        MetodePembayaran::create($data);

        return redirect()->route('admin.metode-pembayaran.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $metodePembayaran = MetodePembayaran::findOrFail($id);
        return view('admin.metode-pembayaran.edit', compact('metodePembayaran'));
    }

    public function update(Request $request, $id)
    {
        $metodePembayaran = MetodePembayaran::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
            'tipe' => 'required|in:transfer,cod,ewallet',
            'bank_nama' => 'nullable|required_if:tipe,transfer|string|max:100',
            'bank_rekening' => 'nullable|required_if:tipe,transfer|string|max:50',
            'bank_atas_nama' => 'nullable|required_if:tipe,transfer|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'instruksi' => 'nullable|string',
            'is_active' => 'boolean',
            'urutan' => 'nullable|integer',
        ]);

        $data = $request->only(['nama', 'deskripsi', 'tipe', 'bank_nama', 'bank_rekening', 'bank_atas_nama', 'instruksi', 'urutan']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($metodePembayaran->logo) {
                Storage::disk('public')->delete($metodePembayaran->logo);
            }
            $data['logo'] = $request->file('logo')->store('metode-pembayaran', 'public');
        }

        $metodePembayaran->update($data);

        return redirect()->route('admin.metode-pembayaran.index')
            ->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $metodePembayaran = MetodePembayaran::findOrFail($id);

        if ($this->isMetodePembayaranUsed($metodePembayaran)) {
            return back()->with('error', 'Metode pembayaran sedang digunakan oleh pesanan dan tidak dapat dihapus.');
        }

        // Delete logo if exists
        if ($metodePembayaran->logo) {
            Storage::disk('public')->delete($metodePembayaran->logo);
        }

        $metodePembayaran->delete();

        return redirect()->route('admin.metode-pembayaran.index')
            ->with('success', 'Metode pembayaran berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $metodePembayaran = MetodePembayaran::findOrFail($id);
        $metodePembayaran->update(['is_active' => !$metodePembayaran->is_active]);

        $status = $metodePembayaran->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Metode pembayaran berhasil {$status}.");
    }

    private function isMetodePembayaranUsed(MetodePembayaran $metodePembayaran): bool
    {
        if (Schema::hasColumn('orders', 'metode_pembayaran_id')) {
            if (Order::where('metode_pembayaran_id', $metodePembayaran->id)->exists()) {
                return true;
            }
        }

        return Order::where('metode_pembayaran', $metodePembayaran->nama)->exists();
    }
}
