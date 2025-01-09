<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $channels = [
            [
                'code' => 'gopay',
                'name' => 'GoPay / GoPay Later',
                'logo' => 'Gopay (Alt).svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::E_WALLET
            ],
            [
                'code' => 'qris',
                'name' => 'QRIS',
                'logo' => 'QRIS.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::OTHERS
            ],
            [
                'code' => 'mandiri',
                'name' => 'Bank Mandiri',
                'logo' => 'Mandiri.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::BANK
            ],
            [
                'code' => 'bni',
                'name' => 'Bank BNI',
                'logo' => 'BNI.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::BANK
            ],
            [
                'code' => 'bri',
                'name' => 'Bank BRI',
                'logo' => 'BRI.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::BANK
            ],
            [
                'code' => 'permata',
                'name' => 'Bank Permata',
                'logo' => 'Permata Bank (Alt).svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::BANK
            ],
            [
                'code' => 'cimb',
                'name' => 'Bank CIMB Niaga',
                'logo' => 'CIMB Niaga.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::BANK
            ],
            [
                'code' => 'dana',
                'name' => 'Dana Digital',
                'logo' => 'DANA.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::E_WALLET
            ],
            [
                'code' => 'ovo',
                'name' => 'OVO',
                'logo' => 'OVO (New Alt).svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::E_WALLET
            ],
            [
                'code' => 'linkaja',
                'name' => 'LinkAja',
                'logo' => 'LinkAja.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::E_WALLET
            ],
        ];

        DB::table((new PaymentMethod())->getTable())->insert($channels);
    }
}
