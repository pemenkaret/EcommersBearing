<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\ProdukImage;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $assetImages = $this->getBearingAssetImages();
        
        $materials = ['Steel', 'Ceramic', 'Stainless Steel', 'Chrome Steel'];
        $sealTypes = ['Open', 'Sealed', 'Shielded', '2RS', 'ZZ'];
        $cageTypes = ['Steel', 'Brass', 'Nylon', 'Polyamide'];
        
        // Buat 50 produk
        for ($i = 1; $i <= 50; $i++) {
            $harga = $faker->numberBetween(50000, 500000);
            $diskon = $faker->boolean(30) ? $harga - ($harga * $faker->numberBetween(5, 30) / 100) : null;
            
            $produk = Produk::create([
                'kategori_id' => $faker->numberBetween(1, 6),
                'merk_id' => $faker->numberBetween(1, 8),
                'nama' => 'Bearing ' . $faker->bothify('??-####'),
                'sku' => 'BRG-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'deskripsi' => $faker->paragraph(3),
                'harga' => $harga,
                'harga_diskon' => $diskon,
                'stok' => $faker->numberBetween(0, 100),
                'min_stok' => 5,
                'berat' => $faker->randomFloat(2, 10, 1000),
                'unit' => 'pcs',
                'is_featured' => $faker->boolean(20),
                'is_active' => $faker->boolean(95),
                'inner_diameter' => $faker->randomFloat(2, 5, 100),
                'outer_diameter' => $faker->randomFloat(2, 10, 200),
                'width' => $faker->randomFloat(2, 5, 50),
                'material' => $faker->randomElement($materials),
                'seal_type' => $faker->randomElement($sealTypes),
                'cage_type' => $faker->randomElement($cageTypes),
                'views' => $faker->numberBetween(0, 1000),
                'sold_count' => $faker->numberBetween(0, 100),
            ]);
            
            // Buat 1-3 gambar untuk setiap produk
            $imageCount = $faker->numberBetween(1, 3);
            $selectedImages = $assetImages;
            shuffle($selectedImages);
            $selectedImages = array_slice($selectedImages, 0, $imageCount);

            for ($j = 1; $j <= $imageCount; $j++) {
                $assetImage = $selectedImages[$j - 1] ?? null;

                if ($assetImage === null) {
                    break;
                }

                ProdukImage::create([
                    'produk_id' => $produk->id,
                    'image_path' => $this->storeAssetImage($assetImage, $produk->id, $j),
                    'is_primary' => $j === 1,
                    'urutan' => $j,
                ]);
            }
        }
    }

    /**
     * Ambil daftar gambar bearing dari public/assets, kecuali profil.jpg.
     *
     * @return array<int, \SplFileInfo>
     */
    private function getBearingAssetImages(): array
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        $images = array_filter(
            File::files(public_path('assets')),
            static function ($file) use ($allowedExtensions) {
                return in_array(strtolower($file->getExtension()), $allowedExtensions, true)
                    && strtolower($file->getFilename()) !== 'profil.jpg';
            }
        );

        if (empty($images)) {
            throw new \RuntimeException('Tidak ada gambar bearing di public/assets selain profil.jpg.');
        }

        return array_values($images);
    }

    /**
     * Salin gambar asset ke storage public dan kembalikan path relatifnya.
     */
    private function storeAssetImage(\SplFileInfo $assetImage, int $produkId, int $index): string
    {
        $fileName = sprintf(
            'produk/seeded/%d/%02d-%s.%s',
            $produkId,
            $index,
            sha1($assetImage->getFilename() . '-' . $produkId . '-' . $index),
            $assetImage->getExtension()
        );

        Storage::disk('public')->put($fileName, File::get($assetImage->getPathname()));

        return $fileName;
    }
}
