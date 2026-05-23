<?php

namespace App\Models;

use App\Support\HtmlSanitizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Model Produk
 *
 * Model untuk mengelola data produk bearing.
 * Mendukung soft delete, multiple images, dan berbagai atribut teknis.
 *
 * @package App\Models
 * @author  Bearing Shop Team
 * @version 1.0.0
 *
 * @property int         $id
 * @property int         $kategori_id
 * @property int         $merk_id
 * @property string      $nama
 * @property string      $slug
 * @property string      $sku
 * @property string|null $deskripsi
 * @property float       $harga
 * @property float|null  $harga_diskon
 * @property int         $stok
 * @property int         $min_stok
 * @property float|null  $berat
 * @property string      $unit
 * @property bool        $is_featured
 * @property bool        $is_active
 * @property float|null  $inner_diameter
 * @property float|null  $outer_diameter
 * @property float|null  $width
 * @property string|null $material
 * @property string|null $seal_type
 * @property string|null $cage_type
 * @property int         $views
 * @property int         $sold_count
 */
class Produk extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kategori_id',
        'merk_id',
        'nama',
        'slug',
        'sku',
        'deskripsi',
        'harga',
        'harga_diskon',
        'stok',
        'min_stok',
        'berat',
        'unit',
        'is_featured',
        'is_active',
        'inner_diameter',
        'outer_diameter',
        'width',
        'material',
        'seal_type',
        'cage_type',
        'views',
        'sold_count',
    ];

    /**
     * Atribut yang harus di-cast ke tipe native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga' => 'decimal:2',
        'harga_diskon' => 'decimal:2',
        'berat' => 'decimal:2',
        'inner_diameter' => 'decimal:2',
        'outer_diameter' => 'decimal:2',
        'width' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan kategori produk.
     *
     * @return BelongsTo<Kategori, Produk>
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Mendapatkan merk produk.
     *
     * @return BelongsTo<Merk, Produk>
     */
    public function merk(): BelongsTo
    {
        return $this->belongsTo(Merk::class);
    }

    /**
     * Mendapatkan semua gambar produk.
     *
     * @return HasMany<ProdukImage>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProdukImage::class, 'produk_id');
    }

    /**
     * Mendapatkan item keranjang yang berisi produk ini.
     *
     * @return HasMany<Keranjang>
     */
    public function keranjangs(): HasMany
    {
        return $this->hasMany(Keranjang::class, 'produk_id');
    }

    /**
     * Mendapatkan order item yang berisi produk ini.
     *
     * @return HasMany<OrderItem>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'produk_id');
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    /**
     * Mutator untuk auto-generate slug dari nama produk.
     *
     * @param string $value
     * @return void
     */
    public function setNamaAttribute(string $value): void
    {
        $this->attributes['nama'] = $value;

        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    // Sanitize deskripsi produk untuk mencegah XSS pada output {!! !!}
    public function setDeskripsiAttribute(?string $value): void
    {
        $this->attributes['deskripsi'] = HtmlSanitizer::clean($value);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan harga dalam format Rupiah.
     *
     * @return string
     */
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Menghitung persentase diskon.
     *
     * @return int
     */
    public function getDiskonPersenAttribute(): int
    {
        if ($this->harga_diskon && $this->harga > 0) {
            return round((($this->harga - $this->harga_diskon) / $this->harga) * 100);
        }

        return 0;
    }

    /**
     * Mendapatkan status ketersediaan stok.
     *
     * @return string
     */
    public function getStokStatusAttribute(): string
    {
        if ($this->stok <= 0) {
            return 'Stok Habis';
        } elseif ($this->stok <= $this->min_stok) {
            return 'Stok Menipis';
        }

        return 'Tersedia';
    }

    /**
     * Mendapatkan gambar utama produk.
     *
     * @return ProdukImage|null
     */
    public function getPrimaryImageAttribute(): ?ProdukImage
    {
        return $this->images()->where('is_primary', true)->first()
            ?? $this->images()->first();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope untuk filter produk aktif.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter produk featured/unggulan.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope untuk filter produk yang masih tersedia stok.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stok', '>', 0);
    }

    /**
     * Scope untuk filter produk dengan stok menipis.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('stok', '<=', 'min_stok')
                     ->where('stok', '>', 0);
    }

    /**
     * Scope untuk filter berdasarkan kategori.
     *
     * @param Builder $query
     * @param int     $kategoriId
     * @return Builder
     */
    public function scopeByKategori(Builder $query, int $kategoriId): Builder
    {
        return $query->where('kategori_id', $kategoriId);
    }

    /**
     * Scope untuk filter berdasarkan merk.
     *
     * @param Builder $query
     * @param int     $merkId
     * @return Builder
     */
    public function scopeByMerk(Builder $query, int $merkId): Builder
    {
        return $query->where('merk_id', $merkId);
    }

    /**
     * Scope untuk pencarian produk.
     *
     * @param Builder $query
     * @param string  $keyword
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama', 'like', "%{$keyword}%")
              ->orWhere('sku', 'like', "%{$keyword}%")
              ->orWhere('deskripsi', 'like', "%{$keyword}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Menambah jumlah view produk.
     *
     * @return void
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Mengurangi stok produk.
     *
     * @param int $qty Jumlah yang akan dikurangi
     * @return void
     */
    public function decrementStok(int $qty): void
    {
        $this->decrement('stok', $qty);
    }

    /**
     * Menambah stok produk.
     *
     * @param int $qty Jumlah yang akan ditambah
     * @return void
     */
    public function incrementStok(int $qty): void
    {
        $this->increment('stok', $qty);
    }
}
