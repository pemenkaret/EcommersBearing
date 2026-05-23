<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Keranjang
 *
 * Model untuk mengelola keranjang belanja pengguna.
 * Menyimpan item produk sebelum checkout.
 *
 * @package App\Models
 * @author  Bearing Shop Team
 * @version 1.0.0
 *
 * @property int   $id
 * @property int   $user_id
 * @property int   $produk_id
 * @property int   $quantity
 * @property float $harga
 */
class Keranjang extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'produk_id',
        'quantity',
        'harga',
    ];

    /**
     * Atribut yang harus di-cast ke tipe native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan user pemilik keranjang.
     *
     * @return BelongsTo<User, Keranjang>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan produk dalam keranjang.
     *
     * @return BelongsTo<Produk, Keranjang>
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Menghitung subtotal item (harga x quantity).
     *
     * @return float
     */
    public function getSubtotalAttribute(): float
    {
        return $this->harga * $this->quantity;
    }

    /*
    |--------------------------------------------------------------------------
    | STATIC METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan total jumlah item dalam keranjang user.
     *
     * @param int $userId
     * @return int
     */
    public static function getTotalItems(int $userId): int
    {
        return static::where('user_id', $userId)->sum('quantity');
    }

    /**
     * Menghitung grand total keranjang user.
     * Dihitung di level database agar tidak fetch semua row.
     *
     * @param int $userId
     * @return float
     */
    public static function getGrandTotal(int $userId): float
    {
        return (float) static::where('user_id', $userId)
            ->selectRaw('COALESCE(SUM(harga * quantity), 0) AS total')
            ->value('total');
    }
}
