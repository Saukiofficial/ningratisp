<?php

namespace Database\Seeders;

use App\Models\PppArea;
use Illuminate\Database\Seeder;

class PppAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['name' => 'Daleman', 'network_prefix' => '192.168.70.', 'customer_prefix' => '_daleman'],
            ['name' => 'Banaresep', 'network_prefix' => '192.168.70.', 'customer_prefix' => '_benaresep'],
            ['name' => 'Lenteng', 'network_prefix' => '192.168.70.', 'customer_prefix' => '_lenteng'],

            ['name' => 'Poreh Tengah', 'network_prefix' => '192.168.30.', 'customer_prefix' => '_porehtengah'],
            ['name' => 'Kanok', 'network_prefix' => '192.168.30.', 'customer_prefix' => '_kanok'],
            ['name' => 'Gutoguh', 'network_prefix' => '192.168.30.', 'customer_prefix' => '_gutoguh'],

            ['name' => 'Serseran', 'network_prefix' => '192.168.60.', 'customer_prefix' => '_serseran'],
            ['name' => 'Muangan', 'network_prefix' => '192.168.60.', 'customer_prefix' => '_muangan'],
            ['name' => 'Cangkreng Laok', 'network_prefix' => '192.168.60.', 'customer_prefix' => '_cangkrenglaok'],
            ['name' => 'Kalekoy', 'network_prefix' => '192.168.60.', 'customer_prefix' => '_kalekoy'],

            ['name' => 'Benrengoh', 'network_prefix' => '192.168.50.', 'customer_prefix' => '_benrengoh'],

            ['name' => 'Tonggel', 'network_prefix' => '192.168.40.', 'customer_prefix' => '_tonggel'],
            ['name' => 'Meddelan', 'network_prefix' => '192.168.40.', 'customer_prefix' => '_meddelan'],

            ['name' => 'Cangkreng', 'network_prefix' => '192.168.20.', 'customer_prefix' => '_cangkreng'],
            ['name' => 'Pocang', 'network_prefix' => '192.168.20.', 'customer_prefix' => '_pocang'],

            ['name' => 'General / Pusat', 'network_prefix' => '192.168.10.', 'customer_prefix' => '_'],
        ];

        foreach ($areas as $area) {
            PppArea::query()->updateOrCreate($area);
        }
    }
}
