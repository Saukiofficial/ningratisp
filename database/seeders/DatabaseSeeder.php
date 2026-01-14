<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Package;
use App\Models\Complaint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Paket Internet Dummy (Wajib ada dulu sebelum Customer)
        $paketBasic = Package::create([
            'name' => 'Home Basic',
            'speed' => '20 Mbps',
            'price' => 150000,
            'description' => 'Cocok untuk kebutuhan rumahan ringan.',
        ]);

        Package::create([
            'name' => 'Super Stream',
            'speed' => '50 Mbps',
            'price' => 300000,
            'description' => 'Ideal untuk streaming 4K dan meeting online.',
        ]);

        $paketGamer = Package::create([
            'name' => 'Gamer Pro',
            'speed' => '100 Mbps',
            'price' => 500000,
            'description' => 'Ping rendah dan prioritas trafik game.',
        ]);

        // 2. Buat Akun ADMIN
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@ningrat.com',
            'password' => Hash::make('password'), // Password default
            'role' => 'admin',
            'status' => 'active',
            'address' => 'Kantor Pusat Ningrat Net',
            'package_id' => null, // Admin tidak perlu paket (pastikan kolom nullable di migration)
        ]);

        // 3. Buat Akun CUSTOMER 1 (Aktif)
        $customer1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'customer@ningrat.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'active',
            'address' => 'Jl. Merdeka No. 45, Jakarta',
            'package_id' => $paketBasic->id,
        ]);

        // 4. Buat Akun CUSTOMER 2 (Suspended/Telat Bayar)
        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@ningrat.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'suspended',
            'address' => 'Perumahan Griya Indah Blok A1',
            'package_id' => $paketGamer->id,
        ]);

        // 5. Buat Dummy Pengaduan
        Complaint::create([
            'user_id' => $customer1->id,
            'subject' => 'Internet Lambat saat Hujan',
            'description' => 'Setiap hujan deras koneksi sering RTO, mohon dicek kabelnya.',
            'status' => 'pending',
        ]);
    }
}
