<?php

namespace Database\Seeders;

use App\Models\Alamat;
use App\Models\MetodePembayaran;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class BudiPembelianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $budi = User::where('email', 'budi@example.com')->first();

        if (! $budi) {
            return;
        }

        DB::transaction(function () use ($budi): void {
            Order::where('user_id', $budi->id)->delete();
            Alamat::where('user_id', $budi->id)->delete();

            $alamat = Alamat::create([
                'user_id' => $budi->id,
                'label' => 'Rumah',
                'penerima' => $budi->name,
                'telepon' => $budi->telepon ?? '081234567890',
                'alamat_lengkap' => 'Jl. Raya Darmo No. 18, Tegalsari',
                'provinsi' => 'Jawa Timur',
                'kota' => 'Surabaya',
                'kecamatan' => 'Tegalsari',
                'kode_pos' => '60262',
                'is_default' => true,
            ]);

            $produkList = Produk::query()
                ->where('is_active', true)
                ->with('images')
                ->orderBy('id')
                ->get();

            $metodeList = MetodePembayaran::query()
                ->where('is_active', true)
                ->orderBy('urutan')
                ->get();

            if ($produkList->isEmpty() || $metodeList->isEmpty()) {
                return;
            }

            $faker = Faker::create('id_ID');
            $finalStatuses = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'];
            $totalOrders = 20;
            $baseDate = Carbon::now()->subDays($totalOrders + 5);

            for ($index = 0; $index < $totalOrders; $index++) {
                $finalStatus = $faker->randomElement($finalStatuses);
                $orderDate = $baseDate->copy()->addDays($index + 1);
                $metode = $metodeList->random();
                $selectedProduk = $this->pickProducts($produkList, $faker->numberBetween(1, 3));

                $subtotal = 0;
                $items = [];

                foreach ($selectedProduk as $produk) {
                    $quantity = $faker->numberBetween(1, 4);
                    $harga = (float) ($produk->harga_diskon ?: $produk->harga);
                    $itemSubtotal = $harga * $quantity;
                    $subtotal += $itemSubtotal;

                    $items[] = [
                        'produk_id' => $produk->id,
                        'produk_nama' => $produk->nama,
                        'produk_sku' => $produk->sku,
                        'produk_image' => optional($produk->images->first())->image_path,
                        'harga' => $harga,
                        'quantity' => $quantity,
                        'subtotal' => $itemSubtotal,
                    ];
                }

                $ongkir = in_array($finalStatus, ['delivered', 'shipped', 'processing', 'paid'], true)
                    ? 15000
                    : 0;

                if ($index % 4 === 0) {
                    $ongkir = 0;
                }

                $total = $subtotal + $ongkir;
                $orderNumber = $this->generateUniqueOrderNumber();

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => $budi->id,
                    'alamat_id' => $alamat->id,
                    'alamat_penerima' => $alamat->penerima,
                    'alamat_telepon' => $alamat->telepon,
                    'alamat_lengkap' => $alamat->alamat_lengkap,
                    'alamat_provinsi' => $alamat->provinsi,
                    'alamat_kota' => $alamat->kota,
                    'alamat_kecamatan' => $alamat->kecamatan,
                    'alamat_kode_pos' => $alamat->kode_pos,
                    'subtotal' => $subtotal,
                    'ongkir' => $ongkir,
                    'total' => $total,
                    'kurir' => in_array($finalStatus, ['processing', 'shipped', 'delivered'], true)
                        ? $faker->randomElement(['JNE', 'J&T', 'SiCepat'])
                        : null,
                    'resi' => in_array($finalStatus, ['shipped', 'delivered'], true)
                        ? strtoupper('BUDI' . Str::random(8))
                        : null,
                    'estimasi_sampai' => in_array($finalStatus, ['shipped', 'delivered'], true)
                        ? $orderDate->copy()->addDays($faker->numberBetween(2, 5))->toDateString()
                        : null,
                    'metode_pembayaran' => $metode->nama,
                    'bukti_pembayaran' => null,
                    'paid_at' => in_array($finalStatus, ['paid', 'processing', 'shipped', 'delivered'], true)
                        ? $orderDate->copy()->addHours(3)
                        : null,
                    'status' => $finalStatus,
                    'cancelled_reason' => $finalStatus === 'cancelled' ? 'Budi membatalkan pesanan dummy.' : null,
                    'cancelled_at' => $finalStatus === 'cancelled' ? $orderDate->copy()->addHours(4) : null,
                    'catatan' => $faker->randomElement([
                        'Mohon packing rapi.',
                        'Bisa dikirim secepatnya.',
                        'Cek kondisi barang sebelum dikirim.',
                        'Gunakan bubble wrap tambahan.',
                        'Pesanan dummy untuk demo laporan.',
                    ]),
                ]);

                $order->forceFill([
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ])->saveQuietly();

                foreach ($items as $itemIndex => $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'produk_id' => $item['produk_id'],
                        'produk_nama' => $item['produk_nama'],
                        'produk_sku' => $item['produk_sku'],
                        'produk_image' => $item['produk_image'],
                        'harga' => $item['harga'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                    ])->forceFill([
                        'created_at' => $orderDate->copy()->addMinutes($itemIndex + 1),
                        'updated_at' => $orderDate->copy()->addMinutes($itemIndex + 1),
                    ])->saveQuietly();
                }

                $statusTimeline = $this->statusTimelineFor($finalStatus);

                foreach ($statusTimeline as $statusIndex => $status) {
                    OrderStatus::create([
                        'order_id' => $order->id,
                        'status' => $status,
                        'keterangan' => $this->statusNote($status),
                        'created_by' => null,
                    ])->forceFill([
                        'created_at' => $orderDate->copy()->addHours($statusIndex + 1),
                        'updated_at' => $orderDate->copy()->addHours($statusIndex + 1),
                    ])->saveQuietly();
                }
            }
        });
    }

    /**
     * @param Collection<int, Produk> $produkList
     * @return Collection<int, Produk>
     */
    private function pickProducts(Collection $produkList, int $count): Collection
    {
        $count = max(1, min($count, $produkList->count()));

        return $count === 1 ? collect([$produkList->random()]) : $produkList->random($count)->values();
    }

    /**
     * @return array<int, string>
     */
    private function statusTimelineFor(string $finalStatus): array
    {
        return match ($finalStatus) {
            'paid' => ['pending', 'paid'],
            'processing' => ['pending', 'paid', 'processing'],
            'shipped' => ['pending', 'paid', 'processing', 'shipped'],
            'delivered' => ['pending', 'paid', 'processing', 'shipped', 'delivered'],
            'cancelled' => ['pending', 'cancelled'],
            default => ['pending'],
        };
    }

    private function statusNote(string $status): ?string
    {
        return match ($status) {
            'pending' => 'Pesanan dibuat dan menunggu pembayaran.',
            'paid' => 'Pembayaran Budi sudah diterima.',
            'processing' => 'Pesanan sedang diproses di gudang.',
            'shipped' => 'Pesanan sudah dikirim ke alamat Budi.',
            'delivered' => 'Pesanan telah diterima.',
            'cancelled' => 'Pesanan dibatalkan oleh Budi.',
            default => null,
        };
    }

    private function generateUniqueOrderNumber(): string
    {
        $lastSequence = Order::query()
            ->where('order_number', 'like', 'ORD-%')
            ->pluck('order_number')
            ->map(function (string $orderNumber): int {
                return (int) substr($orderNumber, -4);
            })
            ->max() ?? 0;

        do {
            $lastSequence++;
            $orderNumber = 'ORD-' . Carbon::now()->format('Ymd') . '-' . str_pad((string) $lastSequence, 4, '0', STR_PAD_LEFT);
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}