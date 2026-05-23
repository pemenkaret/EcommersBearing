<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MerkRequest;
use App\Models\Merk;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Controller Merk Admin
 *
 * Menangani operasi CRUD untuk manajemen merk/brand produk.
 * Termasuk fitur pencarian dan toggle status.
 *
 * @package App\Http\Controllers\Admin
 * @author  Bearing Shop Team
 * @version 1.0.0
 */
class MerkController extends Controller
{
    /**
     * Menampilkan daftar merk.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Merk::query();

        // Filter pencarian berdasarkan nama
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $merks = $query->orderBy('nama')->paginate(20);

        return view('admin.merk.index', compact('merks'));
    }

    /**
     * Menampilkan form tambah merk baru.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.merk.create');
    }

    /**
     * Menyimpan merk baru ke database.
     *
     * @param MerkRequest $request
     * @return RedirectResponse
     */
    public function store(MerkRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Merk::create($data);

        return redirect()->route('admin.merk.index')->with('success', 'Merk berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit merk.
     *
     * @param int $id ID merk
     * @return View
     */
    public function edit(int $id): View
    {
        $merk = Merk::findOrFail($id);

        return view('admin.merk.edit', compact('merk'));
    }

    /**
     * Memperbarui data merk.
     *
     * @param MerkRequest $request
     * @param int         $id ID merk
     * @return RedirectResponse
     */
    public function update(MerkRequest $request, int $id): RedirectResponse
    {
        $merk = Merk::findOrFail($id);

        $data = $request->validated();

        $merk->update($data);

        return redirect()->route('admin.merk.index')->with('success', 'Merk berhasil diupdate.');
    }

    /**
     * Menghapus merk.
     *
     * Tidak dapat menghapus jika masih ada produk terkait.
     *
     * @param int $id ID merk
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $merk = Merk::findOrFail($id);

        // Validasi: tidak bisa hapus jika ada produk terkait, termasuk yang soft deleted
        if ($merk->produks()->withTrashed()->exists()) {
            return back()->with('error', 'Merk tidak bisa dihapus karena masih ada produk terkait.');
        }

        $merk->delete();

        return back()->with('success', 'Merk berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif merk.
     *
     * @param int $id ID merk
     * @return RedirectResponse
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $merk = Merk::findOrFail($id);
        $merk->update(['is_active' => !$merk->is_active]);

        return back()->with('success', 'Status merk berhasil diupdate.');
    }
}
