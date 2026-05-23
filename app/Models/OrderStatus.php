<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model OrderStatus
 *
 * Model untuk mencatat riwayat perubahan status order.
 * Digunakan untuk tracking progress pesanan.
 *
 * @package App\Models
 * @author  Bearing Shop Team
 * @version 1.0.0
 *
 * @property int         $id
 * @property int         $order_id
 * @property string      $status
 * @property string|null $keterangan
 * @property int|null    $created_by
 */
class OrderStatus extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'status',
        'keterangan',
        'created_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan order yang memiliki status ini.
     *
     * @return BelongsTo<Order, OrderStatus>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mendapatkan admin yang membuat/mengupdate status.
     *
     * @return BelongsTo<User, OrderStatus>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
