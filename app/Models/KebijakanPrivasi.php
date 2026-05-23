<?php

namespace App\Models;

use App\Support\HtmlSanitizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Model KebijakanPrivasi
 *
 * Model untuk mengelola konten halaman Kebijakan Privasi.
 * Menyimpan informasi kebijakan privasi dengan tanggal berlaku.
 *
 * @package App\Models
 * @author  Bearing Shop Team
 * @version 1.0.0
 *
 * @property int              $id
 * @property string           $judul
 * @property string           $konten
 * @property \Carbon\Carbon   $tanggal_berlaku
 * @property bool             $is_active
 */
class KebijakanPrivasi extends Model
{
    /**
     * Nama tabel yang digunakan model.
     *
     * @var string
     */
    protected $table = 'kebijakan_privasis';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'konten',
        'tanggal_berlaku',
        'is_active',
    ];

    /**
     * Atribut yang harus di-cast ke tipe native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_berlaku' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | MUTATORS (XSS sanitization)
    |--------------------------------------------------------------------------
    */

    // Sanitize konten kebijakan privasi (JSON array of {judul, isi}); tiap item.isi dibersihkan
    public function setKontenAttribute(mixed $value): void
    {
        if (is_array($value)) {
            foreach ($value as $i => $item) {
                if (is_array($item) && isset($item['isi'])) {
                    $value[$i]['isi'] = HtmlSanitizer::clean((string) $item['isi']);
                }
            }
            $this->attributes['konten'] = json_encode(array_values($value));
            return;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                foreach ($decoded as $i => $item) {
                    if (is_array($item) && isset($item['isi'])) {
                        $decoded[$i]['isi'] = HtmlSanitizer::clean((string) $item['isi']);
                    }
                }
                $this->attributes['konten'] = json_encode(array_values($decoded));
                return;
            }

            // Plain string fallback
            $this->attributes['konten'] = HtmlSanitizer::clean($value);
            return;
        }

        $this->attributes['konten'] = $value;
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
     * Mendapatkan data Kebijakan Privasi yang aktif.
     *
     * @return KebijakanPrivasi|null
     */
    public static function getActive(): ?self
    {
        return self::active()->first();
    }
}
