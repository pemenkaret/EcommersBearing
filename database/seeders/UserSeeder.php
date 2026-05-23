<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
     /**
      * Run the database seeds.
      */
     public function run(): void
     {
          // Admin User
          $admin = new User([
               'name' => 'Admin',
               'email' => 'admin@bearing.com',
               'password' => Hash::make('password'),
               'email_verified_at' => now(),
               'is_active' => true,
          ]);
          $admin->role_id = 1;
          $admin->save();

          // Owner User
          $owner = new User([
               'name' => 'Owner',
               'email' => 'owner@bearing.com',
               'password' => Hash::make('password'),
               'email_verified_at' => now(),
               'is_active' => true,
          ]);
          $owner->role_id = 3;
          $owner->save();

          // Sample Pelanggan Users
          $pelanggan = [
               ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'telepon' => '081234567890'],
               ['name' => 'Siti Nurhaliza', 'email' => 'siti@example.com', 'telepon' => '081234567891'],
               ['name' => 'Ahmad Ridwan', 'email' => 'ahmad@example.com', 'telepon' => '081234567892'],
               ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'telepon' => '081234567893'],
               ['name' => 'Rudi Hermawan', 'email' => 'rudi@example.com', 'telepon' => '081234567894'],
          ];

          foreach ($pelanggan as $data) {
               $u = new User([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'telepon' => $data['telepon'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'is_active' => true,
               ]);
               $u->role_id = 2;
               $u->save();
          }
     }
}
