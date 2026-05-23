<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User
 *
 * Model untuk mengelola data pengguna aplikasi.
 * Mendukung autentikasi, manajemen profil, dan relasi ke berbagai entitas.
 *
 * @package App\Models
 * @author  Bearing Shop Team
 * @version 1.0.0
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    // role_id dikecualikan dari fillable untuk cegah privilege escalation via mass assignment; set lewat penugasan langsung
    protected $fillable = [
        'name',
        'email',
        'password',
        'telepon',
        'avatar',
        'is_active',
        'notifikasi_email',
        'notifikasi_order',
        'notifikasi_promo',
        'last_login_at',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mendapatkan atribut yang harus di-cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'notifikasi_email' => 'boolean',
            'notifikasi_order' => 'boolean',
            'notifikasi_promo' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan role yang dimiliki user.
     *
     * @return BelongsTo<Role, User>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Mendapatkan semua alamat milik user.
     *
     * @return HasMany<Alamat>
     */
    public function alamats(): HasMany
    {
        return $this->hasMany(Alamat::class);
    }

    /**
     * Mendapatkan semua item keranjang milik user.
     *
     * @return HasMany<Keranjang>
     */
    public function keranjangs(): HasMany
    {
        return $this->hasMany(Keranjang::class);
    }

    /**
     * Mendapatkan semua order milik user.
     *
     * @return HasMany<Order>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Mengecek apakah user memiliki role admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }

    /**
     * Mengecek apakah user memiliki role owner.
     *
     * @return bool
     */
    public function isOwner(): bool
    {
        return $this->role && $this->role->name === 'owner';
    }

    /**
     * Check apakah user adalah pelanggan
     */
    public function isPelanggan(): bool
    {
        return $this->role && $this->role->name === 'pelanggan';
    }

    /**
     * Get alamat default
     */
    public function getDefaultAlamat(): ?Alamat
    {
        return $this->alamats()->where('is_default', true)->first()
            ?? $this->alamats()->first();
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }
}
