<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Order
 *
 * Model untuk mengelola data pesanan/transaksi.
 * Mencakup informasi pembeli, pengiriman, pembayaran, dan status pesanan.
 *
 * @package App\Models
 * @author  Bearing Shop Team
 * @version 1.0.0
 *
 * @property int              $id
 * @property string           $order_number
 * @property int              $user_id
 * @property int|null         $alamat_id
 * @property string           $alamat_penerima
 * @property string           $alamat_telepon
 * @property string           $alamat_lengkap
 * @property string           $alamat_provinsi
 * @property string           $alamat_kota
 * @property string           $alamat_kecamatan
 * @property string           $alamat_kode_pos
 * @property float            $subtotal
 * @property float            $ongkir
 * @property float            $total
 * @property string|null      $kurir
 * @property string|null      $resi
 * @property \Carbon\Carbon|null $estimasi_sampai
 * @property string           $metode_pembayaran
 * @property string|null      $bukti_pembayaran
 * @property \Carbon\Carbon|null $paid_at
 * @property string           $status
 * @property string|null      $cancelled_reason
 * @property \Carbon\Carbon|null $cancelled_at
 * @property string|null      $catatan
 */
class Order extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'alamat_id',
        'alamat_penerima',
        'alamat_telepon',
        'alamat_lengkap',
        'alamat_provinsi',
        'alamat_kota',
        'alamat_kecamatan',
        'alamat_kode_pos',
        'subtotal',
        'ongkir',
        'total',
        'kurir',
        'resi',
        'estimasi_sampai',
        'metode_pembayaran',
        'bukti_pembayaran',
        'paid_at',
        'status',
        'cancelled_reason',
        'cancelled_at',
        'catatan',
    ];

    /**
     * Atribut yang harus di-cast ke tipe native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'ongkir' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'estimasi_sampai' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan user pemilik order.
     *
     * @return BelongsTo<User, Order>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan alamat pengiriman order.
     *
     * @return BelongsTo<Alamat, Order>
     */
    public function alamat(): BelongsTo
    {
        return $this->belongsTo(Alamat::class);
    }

    /**
     * Mendapatkan semua item dalam order.
     *
     * @return HasMany<OrderItem>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Mendapatkan riwayat status order.
     *
     * @return HasMany<OrderStatus>
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(OrderStatus::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan badge HTML untuk status order.
     *
     * @return string
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Menunggu</span>',
            'paid' => '<span class="badge bg-info">Dibayar</span>',
            'processing' => '<span class="badge bg-primary">Diproses</span>',
            'shipped' => '<span class="badge bg-secondary">Dikirim</span>',
            'delivered' => '<span class="badge bg-success">Selesai</span>',
            'cancelled' => '<span class="badge bg-danger">Dibatalkan</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Mendapatkan label teks untuk status order.
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'processing' => 'Sedang Diproses',
            'shipped' => 'Dalam Pengiriman',
            'delivered' => 'Pesanan Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope untuk filter berdasarkan status.
     *
     * @param Builder $query
     * @param string  $status
     * @return Builder
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan user.
     *
     * @param Builder $query
     * @param int     $userId
     * @return Builder
     */
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk pencarian order.
     *
     * @param Builder $query
     * @param string  $keyword
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('order_number', 'like', "%{$keyword}%")
              ->orWhere('alamat_penerima', 'like', "%{$keyword}%")
              ->orWhere('alamat_telepon', 'like', "%{$keyword}%");
        });
    }

    /**
     * Scope untuk filter berdasarkan rentang tanggal.
     *
     * @param Builder $query
     * @param string  $start
     * @param string  $end
     * @return Builder
     */
    public function scopeDateRange(Builder $query, string $start, string $end): Builder
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATIC METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Generate nomor order unik.
     * Format: ORD-YYYYMMDD-XXXX
     *
     * Memakai DB lock untuk mencegah race condition pada concurrent checkout.
     *
     * @return string
     */
    public static function generateOrderNumber(): string
    {
        $date = Carbon::now()->format('Ymd');

        return \DB::transaction(function () use ($date) {
            $lastOrder = static::whereDate('created_at', Carbon::today())
                ->where('order_number', 'like', "ORD-{$date}-%")
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $number = $lastOrder ? intval(substr($lastOrder->order_number, -4)) + 1 : 1;

            return 'ORD-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Update status order dan catat riwayat.
     *
     * @param string      $status
     * @param string|null $keterangan
     * @param int|null    $adminId
     * @return void
     */
    public function updateStatus(string $status, ?string $keterangan = null, ?int $adminId = null): void
    {
        $updates = [];

        if ($this->status !== $status) {
            $updates['status'] = $status;
        }

        // Jika status paid, update paid_at
        if ($status === 'paid' && !$this->paid_at) {
            $updates['paid_at'] = now();
        }

        // Jika status cancelled, update cancelled_at
        if ($status === 'cancelled' && !$this->cancelled_at) {
            $updates['cancelled_at'] = now();
        }

        if (!empty($updates)) {
            $this->update($updates);
        }

        // Catat riwayat status
        OrderStatus::create([
            'order_id' => $this->id,
            'status' => $status,
            'keterangan' => $keterangan,
            'created_by' => $adminId,
        ]);
    }

    /**
     * Hitung ulang total order.
     *
     * @return void
     */
    public function calculateTotal(): void
    {
        $this->subtotal = $this->items()->sum('subtotal');
        $this->total = $this->subtotal + $this->ongkir;
        $this->save();
    }

    /**
     * Cek apakah order sudah dibayar.
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }

    /**
     * Cek apakah order dapat dibatalkan.
     *
     * @return bool
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'paid']);
    }

    /**
     * Batalkan order dan kembalikan stok produk.
     *
     * @param string $reason Alasan pembatalan
     * @return bool
     */
    public function cancel(string $reason): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        return \DB::transaction(function () use ($reason) {
            $this->update([
                'status' => 'cancelled',
                'cancelled_reason' => $reason,
                'cancelled_at' => now(),
            ]);

            // Eager load produk agar tidak N+1 saat increment stok
            $this->load('items.produk');

            // Kembalikan stok produk dengan lock untuk konsistensi
            foreach ($this->items as $item) {
                if ($item->produk) {
                    Produk::where('id', $item->produk_id)
                        ->lockForUpdate()
                        ->increment('stok', $item->quantity);
                }
            }

            // Catat riwayat status
            OrderStatus::create([
                'order_id' => $this->id,
                'status' => 'cancelled',
                'keterangan' => $reason,
            ]);

            return true;
        });
    }
}
