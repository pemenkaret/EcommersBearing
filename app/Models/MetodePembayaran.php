<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model MetodePembayaran
 *
 * Model untuk mengelola metode pembayaran yang tersedia.
 * Mendukung transfer bank, e-wallet, dan COD.
 *
 * @package App\Models
 * @author  Bearing Shop Team
 * @version 1.0.0
 *
 * @property int         $id
 * @property string      $nama
 * @property string|null $deskripsi
 * @property string      $tipe
 * @property string|null $bank_nama
 * @property string|null $bank_rekening
 * @property string|null $bank_atas_nama
 * @property string|null $logo
 * @property string|null $instruksi
 * @property bool        $is_active
 * @property int         $urutan
 */
class MetodePembayaran extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'deskripsi',
        'tipe',
        'bank_nama',
        'bank_rekening',
        'bank_atas_nama',
        'logo',
        'instruksi',
        'is_active',
        'urutan',
    ];

    /**
     * Atribut yang harus di-cast ke tipe native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope untuk filter metode pembayaran yang aktif.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan urutan dan nama.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('nama');
    }

    /*
    |--------------------------------------------------------------------------
    | STATIC METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan semua metode pembayaran yang aktif.
     *
     * @return Collection<int, MetodePembayaran>
     */
    public static function getActive(): Collection
    {
        return self::active()->ordered()->get();
    }

    /**
     * Mendapatkan metode pembayaran berdasarkan tipe.
     *
     * @param string $tipe Tipe pembayaran (transfer, cod, ewallet)
     * @return Collection<int, MetodePembayaran>
     */
    public static function getByTipe(string $tipe): Collection
    {
        return self::active()->where('tipe', $tipe)->ordered()->get();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan label tipe pembayaran yang mudah dibaca.
     *
     * @return string
     */
    public function getTipeLabelAttribute(): string
    {
        return match ($this->tipe) {
            'transfer' => 'Transfer Bank',
            'cod' => 'Bayar di Tempat (COD)',
            'ewallet' => 'E-Wallet',
            default => $this->tipe,
        };
    }
}
