<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KategoriRequest;
use App\Models\Kategori;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Controller Kategori Admin
 *
 * Menangani operasi CRUD untuk manajemen kategori produk.
 * Termasuk fitur pencarian, upload icon, dan toggle status.
 *
 * @package App\Http\Controllers\Admin
 * @author  Bearing Shop Team
 * @version 1.0.0
 */
class KategoriController extends Controller
{
    /**
     * Menampilkan daftar kategori.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Kategori::query();

        // Filter pencarian berdasarkan nama
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $kategoris = $query->ordered()->paginate(20);

        return view('admin.kategori.index', compact('kategoris'));
    }

    /**
     * Menampilkan form tambah kategori baru.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.kategori.create');
    }

    /**
     * Menyimpan kategori baru ke database.
     *
     * @param KategoriRequest $request
     * @return RedirectResponse
     */
    public function store(KategoriRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Upload icon jika ada
        if ($request->hasFile('icon')) {
            $iconName = time() . '.' . $request->file('icon')->getClientOriginalExtension();
            $request->file('icon')->storeAs('kategori', $iconName, 'public');
            $data['icon'] = 'kategori/' . $iconName;
        }

        Kategori::create($data);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit kategori.
     *
     * @param int $id ID kategori
     * @return View
     */
    public function edit(int $id): View
    {
        $kategori = Kategori::findOrFail($id);

        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Memperbarui data kategori.
     *
     * @param KategoriRequest $request
     * @param int             $id ID kategori
     * @return RedirectResponse
     */
    public function update(KategoriRequest $request, int $id): RedirectResponse
    {
        $kategori = Kategori::findOrFail($id);

        $data = $request->validated();

        // Upload icon baru jika ada
        if ($request->hasFile('icon')) {
            $iconName = time() . '.' . $request->file('icon')->getClientOriginalExtension();
            $request->file('icon')->storeAs('kategori', $iconName, 'public');
            $data['icon'] = 'kategori/' . $iconName;
        }

        $kategori->update($data);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diupdate.');
    }

    /**
     * Menghapus kategori.
     *
     * Tidak dapat menghapus jika masih ada produk terkait.
     *
     * @param int $id ID kategori
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $kategori = Kategori::findOrFail($id);

        // Validasi: tidak bisa hapus jika ada produk terkait
        if ($kategori->produks()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih ada produk terkait.');
        }

        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif kategori.
     *
     * @param int $id ID kategori
     * @return RedirectResponse
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->update(['is_active' => !$kategori->is_active]);

        return back()->with('success', 'Status kategori berhasil diupdate.');
    }
}
