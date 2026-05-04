<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Controller Dashboard Admin
 *
 * Menangani tampilan dan data untuk halaman dashboard administrator.
 * Menyediakan ringkasan statistik, grafik penjualan, dan informasi penting lainnya.
 *
 * @package App\Http\Controllers\Admin
 * @author  Bearing Shop Team
 * @version 1.0.0
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     *
     * Mengambil data statistik seperti:
     * - Total pesanan
     * - Total produk
     * - Total pelanggan
     * - Grafik penjualan 7 hari terakhir
     * - Pesanan terbaru
     * - Produk dengan stok menipis
     *
     * @return View
     */
    public function index(): View
    {
        // Total Pesanan
        $totalPesanan = Order::count();

        // Total Produk
        $totalProduk = Produk::count();

        // Total Pelanggan
        $totalPelanggan = User::whereHas('role', function ($q) {
            $q->where('name', 'pelanggan');
        })->count();

        // Grafik Penjualan 7 hari terakhir
        $penjualan7Hari = Order::where('status', 'delivered')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as tanggal, SUM(total) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Pesanan Terbaru
        $pesananTerbaru = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Produk dengan Stok Menipis
        $produkStokMenipis = Produk::with(['kategori', 'merk'])
            ->whereColumn('stok', '<=', 'min_stok')
            ->where('stok', '>', 0)
            ->orderBy('stok')
            ->take(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalPesanan',
            'totalProduk',
            'totalPelanggan',
            'penjualan7Hari',
            'pesananTerbaru',
            'produkStokMenipis'
        ));
    }
}
