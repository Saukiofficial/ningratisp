<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Package;
use App\Models\Complaint;
use App\Models\CoverageArea; // Import Model CoverageArea
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $paketBasic = Package::create([
            'name' => 'Home Basic',
            'speed' => '20 Mbps',
            'price' => 150000,
            'description' => 'Cocok untuk kebutuhan rumahan ringan, browsing, dan streaming SD.',
        ]);

        Package::create([
            'name' => 'Super Stream',
            'speed' => '50 Mbps',
            'price' => 300000,
            'description' => 'Ideal untuk keluarga, streaming 4K lancar, dan meeting online tanpa putus.',
        ]);

        $paketGamer = Package::create([
            'name' => 'Gamer Pro',
            'speed' => '100 Mbps',
            'price' => 500000,
            'description' => 'Ping rendah prioritas trafik game, upload cepat untuk streaming/konten kreator.',
        ]);
        $areas = [
            ['name' => 'Cangkreng', 'district' => 'Lenteng', 'city' => 'Sumenep'],
            ['name' => 'Poreh', 'district' => 'Lenteng', 'city' => 'Sumenep'],
            ['name' => 'Maddelan', 'district' => 'Lenteng', 'city' => 'Sumenep'],
            ['name' => 'Tonggal', 'district' => 'Lenteng', 'city' => 'Sumenep'],
            ['name' => 'Cankreng', 'district' => 'Lenteng', 'city' => 'Sumenep'],
            ['name' => 'Moangan', 'district' => 'Lenteng', 'city' => 'Sumenep'],
            ['name' => 'Banaressep', 'district' => 'Lenteng', 'city' => 'Sumenep'],
        ];

        // Pastikan tabel CoverageArea sudah ada (buat migration dulu jika belum)
        // Jika belum ada model CoverageArea, bagian ini bisa dikomentari dulu
        if (class_exists(CoverageArea::class)) {
            foreach ($areas as $area) {
                CoverageArea::create($area);
            }
        }

        // 3. SEED USER (ADMIN)
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@ningrat.com',
            'password' => Hash::make('password'), // Password default: password
            'role' => 'admin',
            'status' => 'active',
            'address' => 'Kantor Pusat Ningrat Net, Surabaya',
            'package_id' => null, // Admin tidak perlu paket
        ]);

        // 4. SEED USER (CUSTOMER 1 - AKTIF)
        $customer1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'customer@ningrat.com',
            'password' => Hash::make('password'), // Password default: password
            'role' => 'customer',
            'status' => 'active',
            'address' => 'Jl. Merdeka No. 45, Lenteng, Sumenep',
            'package_id' => $paketBasic->id,
        ]);

        // 5. SEED USER (CUSTOMER 2 - SUSPENDED)
        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@ningrat.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'suspended', // Contoh user telat bayar
            'address' => 'Perumahan Griya Indah Blok A1, Poreh',
            'package_id' => $paketGamer->id,
        ]);

        // 6. SEED PENGADUAN (COMPLAINT)
        Complaint::create([
            'user_id' => $customer1->id,
            'subject' => 'Internet Lambat saat Hujan',
            'description' => 'Setiap hujan deras koneksi sering RTO, mohon dicek kabel dropcore-nya.',
            'status' => 'pending',
        ]);
    }
}
