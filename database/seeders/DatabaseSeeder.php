<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            KategoriSeeder::class,
            MerkSeeder::class,
            ProdukSeeder::class,
            TentangKamiSeeder::class,
            KontakSeeder::class,
            KebijakanPrivasiSeeder::class,
            MetodePembayaranSeeder::class,
            BudiPembelianSeeder::class,
        ]);
    }
}
