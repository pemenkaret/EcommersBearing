<?php

namespace App\Observers;

use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

/**
 * Observer untuk model `Produk`.
 *
 * Menangani pembersihan file gambar dan record `ProdukImage` saat produk
 * dihapus secara permanen (forceDelete).
 */
class ProdukObserver
{
    /**
     * Handle the Produk "deleting" event.
     *
     * @param  Produk  $produk
     * @return void
     */
    public function deleting(Produk $produk): void
    {
        // Hanya bersihkan file/rekaman gambar saat ini adalah force delete
        if (! method_exists($produk, 'isForceDeleting') || ! $produk->isForceDeleting()) {
            return;
        }

        // Jika produk pernah menjadi bagian dari order, cegah penghapusan permanen
        if ($produk->orderItems()->exists()) {
            throw new \Exception('Produk memiliki transaksi terkait dan tidak dapat dihapus permanen.');
        }

        foreach ($produk->images as $image) {
            if (! empty($image->image_path) && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Hapus record gambar dari database
            $image->delete();
        }
    }
}
