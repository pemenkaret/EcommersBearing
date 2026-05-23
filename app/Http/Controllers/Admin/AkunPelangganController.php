<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AkunPelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereHas('role', function ($q) {
            $q->where('name', 'pelanggan');
        });

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telepon', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $pelanggans = $query->latest()->paginate(20);

        return view('admin.akunpelanggan.index', compact('pelanggans'));
    }

    public function edit($id)
    {
        $pelanggan = User::whereHas('role', function ($q) {
            $q->where('name', 'pelanggan');
        })->findOrFail($id);

        return view('admin.akunpelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'telepon' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
        ]);

        $pelanggan->update($request->only(['name', 'email', 'telepon', 'is_active']));

        return redirect()->route('admin.akunpelanggan.index')->with('success', 'Akun pelanggan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pelanggan = User::findOrFail($id);

        // Soft delete: order historis tetap utuh karena kolom snapshot
        // alamat/produk sudah disimpan di tabel orders & order_items.
        $pelanggan->delete();

        return back()->with('success', 'Akun pelanggan berhasil dihapus.');
    }
}
