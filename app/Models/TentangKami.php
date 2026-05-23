<?php

namespace App\Models;

use App\Support\HtmlSanitizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Model TentangKami
 *
 * Model untuk mengelola konten halaman "Tentang Kami".
 * Menyimpan informasi profil perusahaan, visi, dan misi.
 *
 * @package App\Models
 * @author  Bearing Shop Team
 * @version 1.0.0
 *
 * @property int         $id
 * @property string      $judul
 * @property string      $konten
 * @property string|null $gambar
 * @property string|null $visi
 * @property string|null $misi
 * @property bool        $is_active
 */
class TentangKami extends Model
{
    /**
     * Nama tabel yang digunakan model.
     *
     * @var string
     */
    protected $table = 'tentang_kamis';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'konten',
        'gambar',
        'visi',
        'misi',
        'is_active',
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
    | MUTATORS (XSS sanitization)
    |--------------------------------------------------------------------------
    */

    public function setKontenAttribute(?string $value): void
    {
        $this->attributes['konten'] = HtmlSanitizer::clean($value);
    }

    public function setVisiAttribute(?string $value): void
    {
        $this->attributes['visi'] = HtmlSanitizer::clean($value);
    }

    public function setMisiAttribute(?string $value): void
    {
        $this->attributes['misi'] = HtmlSanitizer::clean($value);
    }

    // Bersihkan HTML dari tag/atribut berbahaya untuk mencegah XSS
    public static function sanitizeHtml(?string $value): ?string
    {
        return HtmlSanitizer::clean($value);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope untuk filter data yang aktif.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | STATIC METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan data Tentang Kami yang aktif.
     *
     * @return TentangKami|null
     */
    public static function getActive(): ?self
    {
        return self::active()->first();
    }
}
