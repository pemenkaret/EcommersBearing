<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Role
 *
 * Model untuk mengelola role/peran pengguna dalam sistem.
 * Digunakan untuk manajemen hak akses dan otorisasi.
 *
 * @package App\Models
 * @author  Bearing Shop Team
 * @version 1.0.0
 *
 * @property int         $id
 * @property string      $name
 * @property string      $display_name
 * @property string|null $description
 */
class Role extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan semua user dengan role ini.
     *
     * @return HasMany<User>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
