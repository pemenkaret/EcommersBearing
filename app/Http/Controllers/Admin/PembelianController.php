<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Controller Pembelian Admin
 *
 * Menangani manajemen pesanan/order dari sisi administrator.
 * Termasuk fitur view, update status, input resi, dan export data.
 *
 * @package App\Http\Controllers\Admin
 * @author  Bearing Shop Team
 * @version 1.0.0
 */
class PembelianController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan.
     *
     * Mendukung filter berdasarkan:
     * - Pencarian (order number, penerima, telepon)
     * - Status pesanan
     * - Rentang tanggal
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items']);

        // Filter pencarian
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->dateRange($request->tanggal_mulai, $request->tanggal_akhir);
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.pembelian.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan.
     *
     * @param int $id ID order
     * @return View
     */
    public function show(int $id): View
    {
        $order = Order::with(['user', 'items.produk.images', 'statuses.createdBy'])->findOrFail($id);

        return view('admin.pembelian.detail', compact('order'));
    }

    /**
     * Memperbarui status pesanan.
     *
     * @param Request $request
     * @param int     $id ID order
     * @return RedirectResponse
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,delivered,cancelled',
            'keterangan' => 'nullable|string',
        ]);

        $order->updateStatus($request->status, $request->keterangan, auth()->id());

        return back()->with('success', 'Status order berhasil diupdate.');
    }

    /**
     * Memperbarui informasi pengiriman (resi).
     *
     * Otomatis mengubah status menjadi 'shipped' jika belum.
     *
     * @param Request $request
     * @param int     $id ID order
     * @return RedirectResponse
     */
    public function updateResi(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'kurir' => 'required|string',
            'resi' => 'required|string',
            'estimasi_sampai' => 'nullable|date',
        ]);

        $order->update([
            'kurir' => $request->kurir,
            'resi' => $request->resi,
            'estimasi_sampai' => $request->estimasi_sampai,
        ]);

        // Update status ke shipped jika belum
        if ($order->status !== 'shipped') {
            $order->updateStatus('shipped', 'Pesanan telah dikirim dengan kurir ' . $request->kurir, auth()->id());
        }

        return back()->with('success', 'Nomor resi berhasil diupdate.');
    }

    /**
     * Export data pesanan ke format Excel.
     *
     * @return RedirectResponse
     *
     * @todo Implementasi export Excel
     */
    public function export(): RedirectResponse
    {
        return back()->with('info', 'Fitur export akan segera tersedia.');
    }
}
