<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Pelanggan;
use App\Http\Controllers\Owner;
use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\Api\OngkirController;

/*
|==========================================================================
| WEB ROUTES - BEARING SHOP E-COMMERCE
|==========================================================================
|
| File ini berisi definisi semua route web untuk aplikasi Bearing Shop.
| Route dikelompokkan berdasarkan fungsionalitas:
| - API Wilayah & Ongkir
| - Authentication
| - Pelanggan (Public & Authenticated)
| - Admin Panel
|
| @package routes
| @author  Bearing Shop Team
| @version 1.0.0
|
*/

/*
|--------------------------------------------------------------------------
| API Wilayah Routes (Proxy)
|--------------------------------------------------------------------------
|
| Route untuk mengambil data wilayah Indonesia (provinsi, kota, kecamatan).
| Digunakan untuk form alamat pengiriman.
|
*/
Route::prefix('api/wilayah')->name('api.wilayah.')->group(function () {
    Route::get('/provinsi', [WilayahController::class, 'provinsi'])->name('provinsi');
    Route::get('/kota', [WilayahController::class, 'kota'])->name('kota');
    Route::get('/kecamatan', [WilayahController::class, 'kecamatan'])->name('kecamatan');
});

/*
|--------------------------------------------------------------------------
| API Ongkir Routes
|--------------------------------------------------------------------------
|
| Route untuk kalkulasi ongkos kirim berdasarkan alamat atau provinsi.
| Memerlukan autentikasi user.
|
*/
Route::prefix('api/ongkir')->name('api.ongkir.')->middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::post('/hitung-by-alamat', [OngkirController::class, 'hitungByAlamat'])->name('hitung-by-alamat');
    Route::post('/hitung-by-provinsi', [OngkirController::class, 'hitungByProvinsi'])->name('hitung-by-provinsi');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Route untuk proses autentikasi: login, register, logout, dan reset password.
|
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
    ->middleware('throttle:5,1')
    ->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])
    ->middleware('throttle:5,1')
    ->name('password.update');

/*
|--------------------------------------------------------------------------
| Pelanggan Routes - Public
|--------------------------------------------------------------------------
|
| Route yang dapat diakses tanpa login:
| - Halaman utama
| - Katalog produk
| - Detail produk
| - Halaman informasi (Tentang Kami, Kontak, Kebijakan Privasi)
|
*/
Route::get('/', [Pelanggan\HomeController::class, 'index'])->name('pelanggan.home.index');
Route::get('/pelanggan/produk', [Pelanggan\ProdukController::class, 'index'])->name('pelanggan.produk.index');
Route::get('/pelanggan/produk/{slug}', [Pelanggan\ProdukController::class, 'show'])->name('pelanggan.produk.show');

// Halaman Informasi Statis
Route::get('/tentang-kami', [Pelanggan\HalamanController::class, 'tentangKami'])->name('pelanggan.tentang-kami');
Route::get('/kontak', [Pelanggan\HalamanController::class, 'kontak'])->name('pelanggan.kontak');
Route::get('/kebijakan-privasi', [Pelanggan\HalamanController::class, 'kebijakanPrivasi'])->name('pelanggan.kebijakan-privasi');


/*
|--------------------------------------------------------------------------
| Pelanggan Routes - Authenticated
|--------------------------------------------------------------------------
|
| Route yang memerlukan login dengan role pelanggan:
| - Profil pengguna
| - Manajemen alamat
| - Keranjang belanja
| - Checkout & Buy Now
| - Riwayat pembelian
|
*/
Route::prefix('pelanggan')->name('pelanggan.')->middleware(['auth', 'role:pelanggan'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | Profil Pengguna
    |----------------------------------------------------------------------
    */
    Route::get('/profil', [Pelanggan\ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil/pribadi', [Pelanggan\ProfilController::class, 'updatePribadi'])->name('profil.update-pribadi');
    Route::put('/profil/password', [Pelanggan\ProfilController::class, 'updatePassword'])->name('profil.update-password');
    Route::post('/profil/avatar', [Pelanggan\ProfilController::class, 'updateAvatar'])->name('profil.update-avatar');
    Route::put('/profil/notifikasi', [Pelanggan\ProfilController::class, 'updateNotifikasi'])->name('profil.update-notifikasi');
    Route::delete('/profil/remember/{id}', [Pelanggan\ProfilController::class, 'deleteRememberToken'])->name('profil.delete-remember');
    Route::delete('/profil/remember', [Pelanggan\ProfilController::class, 'deleteAllRememberTokens'])->name('profil.delete-all-remember');

    /*
    |----------------------------------------------------------------------
    | Manajemen Alamat Pengiriman
    |----------------------------------------------------------------------
    */
    Route::post('/alamat', [Pelanggan\AlamatPengirimanController::class, 'store'])->name('alamat.store');
    Route::put('/alamat/{id}', [Pelanggan\AlamatPengirimanController::class, 'update'])->name('alamat.update');
    Route::delete('/alamat/{id}', [Pelanggan\AlamatPengirimanController::class, 'destroy'])->name('alamat.destroy');
    Route::patch('/alamat/{id}/set-default', [Pelanggan\AlamatPengirimanController::class, 'setDefault'])->name('alamat.set-default');

    /*
    |----------------------------------------------------------------------
    | Keranjang Belanja
    |----------------------------------------------------------------------
    */
    Route::get('/keranjang', [Pelanggan\KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang', [Pelanggan\KeranjangController::class, 'store'])->name('keranjang.store');
    Route::put('/keranjang/{id}', [Pelanggan\KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{id}', [Pelanggan\KeranjangController::class, 'destroy'])->name('keranjang.destroy');
    Route::delete('/keranjang-clear', [Pelanggan\KeranjangController::class, 'clear'])->name('keranjang.clear');

    /*
    |----------------------------------------------------------------------
    | Checkout & Pembelian Langsung
    |----------------------------------------------------------------------
    */
    Route::get('/checkout', [Pelanggan\CheckoutController::class, 'showCheckoutForm'])->name('checkout.form');
    Route::post('/checkout', [Pelanggan\CheckoutController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/buy-now/{produk}', [Pelanggan\CheckoutController::class, 'showBuyNowForm'])->name('buy-now.form');
    Route::post('/buy-now', [Pelanggan\CheckoutController::class, 'buyNow'])->name('buy-now');

    /*
    |----------------------------------------------------------------------
    | Riwayat Pembelian
    |----------------------------------------------------------------------
    */
    Route::get('/pembelian/riwayat', [Pelanggan\PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('/pembelian/{order_number}', [Pelanggan\PembelianController::class, 'show'])->name('pembelian.show');
    Route::post('/pembelian/{id}/upload-bukti', [Pelanggan\PembelianController::class, 'uploadBuktiPembayaran'])->name('pembelian.upload-bukti');
    Route::post('/pembelian/{id}/cancel', [Pelanggan\PembelianController::class, 'cancel'])->name('pembelian.cancel');
});

/*
|--------------------------------------------------------------------------
| Owner Routes
|--------------------------------------------------------------------------
|
| Route untuk owner yang hanya melihat laporan pendapatan.
|
*/
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/laporan-pendapatan', [Owner\ReportController::class, 'index'])->name('laporan-pendapatan.index');
    Route::get('/laporan-pendapatan/export', [Owner\ReportController::class, 'export'])->name('laporan-pendapatan.export');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Route untuk panel administrator dengan middleware auth dan role admin.
| Mencakup manajemen:
| - Dashboard
| - Produk, Kategori, Merk
| - Pesanan/Pembelian
| - Akun Pelanggan
| - Konten (Tentang Kami, Kontak, Kebijakan Privasi)
| - Metode Pembayaran
|
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | Dashboard
    |----------------------------------------------------------------------
    */
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard.index');

    /*
    |----------------------------------------------------------------------
    | Profil Admin
    |----------------------------------------------------------------------
    */
    Route::get('/profil', [Admin\ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [Admin\ProfilController::class, 'update'])->name('profil.update');
    Route::delete('/profil/remember/{id}', [Admin\ProfilController::class, 'deleteRememberToken'])->name('profil.delete-remember');
    Route::delete('/profil/remember', [Admin\ProfilController::class, 'deleteAllRememberTokens'])->name('profil.delete-all-remember');

    /*
    |----------------------------------------------------------------------
    | Manajemen Produk
    |----------------------------------------------------------------------
    */
    Route::get('/produk', [Admin\ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [Admin\ProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk', [Admin\ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{id}/detail', [Admin\ProdukController::class, 'show'])->name('produk.show');
    Route::get('/produk/{id}/edit', [Admin\ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [Admin\ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [Admin\ProdukController::class, 'destroy'])->name('produk.destroy');
    Route::get('/produk-export', [Admin\ProdukController::class, 'export'])->name('produk.export');

    /*
    |----------------------------------------------------------------------
    | Manajemen Kategori
    |----------------------------------------------------------------------
    */
    Route::get('/kategori', [Admin\KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [Admin\KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [Admin\KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [Admin\KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [Admin\KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [Admin\KategoriController::class, 'destroy'])->name('kategori.destroy');
    Route::patch('/kategori/{id}/toggle-status', [Admin\KategoriController::class, 'toggleStatus'])->name('kategori.toggle-status');

    /*
    |----------------------------------------------------------------------
    | Manajemen Merk
    |----------------------------------------------------------------------
    */
    Route::get('/merk', [Admin\MerkController::class, 'index'])->name('merk.index');
    Route::get('/merk/create', [Admin\MerkController::class, 'create'])->name('merk.create');
    Route::post('/merk', [Admin\MerkController::class, 'store'])->name('merk.store');
    Route::get('/merk/{id}/edit', [Admin\MerkController::class, 'edit'])->name('merk.edit');
    Route::put('/merk/{id}', [Admin\MerkController::class, 'update'])->name('merk.update');
    Route::delete('/merk/{id}', [Admin\MerkController::class, 'destroy'])->name('merk.destroy');
    Route::patch('/merk/{id}/toggle-status', [Admin\MerkController::class, 'toggleStatus'])->name('merk.toggle-status');

    /*
    |----------------------------------------------------------------------
    | Manajemen Pesanan/Pembelian
    |----------------------------------------------------------------------
    */
    Route::get('/pembelian', [Admin\PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('/pembelian/{id}', [Admin\PembelianController::class, 'show'])->name('pembelian.show');
    Route::patch('/pembelian/{id}/status', [Admin\PembelianController::class, 'updateStatus'])->name('pembelian.update-status');
    Route::patch('/pembelian/{id}/resi', [Admin\PembelianController::class, 'updateResi'])->name('pembelian.update-resi');
    Route::get('/pembelian-export', [Admin\PembelianController::class, 'export'])->name('pembelian.export');

    /*
    |----------------------------------------------------------------------
    | Manajemen Akun Pelanggan
    |----------------------------------------------------------------------
    */
    Route::get('/akunpelanggan', [Admin\AkunPelangganController::class, 'index'])->name('akunpelanggan.index');
    Route::get('/akunpelanggan/{id}/edit', [Admin\AkunPelangganController::class, 'edit'])->name('akunpelanggan.edit');
    Route::put('/akunpelanggan/{id}', [Admin\AkunPelangganController::class, 'update'])->name('akunpelanggan.update');
    Route::delete('/akunpelanggan/{id}', [Admin\AkunPelangganController::class, 'destroy'])->name('akunpelanggan.destroy');

    /*
    |----------------------------------------------------------------------
    | Manajemen Konten - Tentang Kami
    |----------------------------------------------------------------------
    */
    Route::get('/tentang-kami', [Admin\TentangKamiController::class, 'index'])->name('tentang-kami.index');
    Route::get('/tentang-kami/edit', [Admin\TentangKamiController::class, 'edit'])->name('tentang-kami.edit');
    Route::put('/tentang-kami', [Admin\TentangKamiController::class, 'update'])->name('tentang-kami.update');

    /*
    |----------------------------------------------------------------------
    | Manajemen Konten - Kontak
    |----------------------------------------------------------------------
    */
    Route::get('/kontak', [Admin\KontakController::class, 'index'])->name('kontak.index');
    Route::get('/kontak/edit', [Admin\KontakController::class, 'edit'])->name('kontak.edit');
    Route::put('/kontak', [Admin\KontakController::class, 'update'])->name('kontak.update');

    /*
    |----------------------------------------------------------------------
    | Manajemen Konten - Kebijakan Privasi
    |----------------------------------------------------------------------
    */
    Route::get('/kebijakan-privasi', [Admin\KebijakanPrivasiController::class, 'index'])->name('kebijakan-privasi.index');
    Route::get('/kebijakan-privasi/edit', [Admin\KebijakanPrivasiController::class, 'edit'])->name('kebijakan-privasi.edit');
    Route::put('/kebijakan-privasi', [Admin\KebijakanPrivasiController::class, 'update'])->name('kebijakan-privasi.update');

    /*
    |----------------------------------------------------------------------
    | Manajemen Metode Pembayaran
    |----------------------------------------------------------------------
    */
    Route::get('/metode-pembayaran', [Admin\MetodePembayaranController::class, 'index'])->name('metode-pembayaran.index');
    Route::get('/metode-pembayaran/create', [Admin\MetodePembayaranController::class, 'create'])->name('metode-pembayaran.create');
    Route::post('/metode-pembayaran', [Admin\MetodePembayaranController::class, 'store'])->name('metode-pembayaran.store');
    Route::get('/metode-pembayaran/{id}/edit', [Admin\MetodePembayaranController::class, 'edit'])->name('metode-pembayaran.edit');
    Route::put('/metode-pembayaran/{id}', [Admin\MetodePembayaranController::class, 'update'])->name('metode-pembayaran.update');
    Route::delete('/metode-pembayaran/{id}', [Admin\MetodePembayaranController::class, 'destroy'])->name('metode-pembayaran.destroy');
    Route::patch('/metode-pembayaran/{id}/toggle-status', [Admin\MetodePembayaranController::class, 'toggleStatus'])->name('metode-pembayaran.toggle-status');
});
