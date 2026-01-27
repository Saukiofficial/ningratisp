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
                'category' => PaymentMethod::E_WALLET,
                'midtrans_code' => 'gopay'
            ],
            [
                'code' => 'qris',
                'name' => 'QRIS',
                'logo' => 'QRIS.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::OTHERS,
                'midtrans_code' => 'other_qris'
            ],
            [
                'code' => 'mandiri',
                'name' => 'Bank Mandiri',
                'logo' => 'Mandiri.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::BANK,
                'midtrans_code' => 'mandiri'
            ],
            [
                'code' => 'bni',
                'name' => 'Bank BNI',
                'logo' => 'BNI.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::BANK,
                'midtrans_code' => 'bni_va',
            ],
            [
                'code' => 'bri',
                'name' => 'Bank BRI',
                'logo' => 'BRI.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::BANK,
                'midtrans_code' => 'bri_va'
            ],
            [
                'code' => 'permata',
                'name' => 'Bank Permata',
                'logo' => 'Permata Bank (Alt).svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::BANK,
                'midtrans_code' => 'permata_va'
            ],
            [
                'code' => 'cimb',
                'name' => 'Bank CIMB Niaga',
                'logo' => 'CIMB Niaga.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::BANK,
                'midtrans_code' => 'cimb_va'
            ],
            [
                'code' => 'dana',
                'name' => 'Dana Digital',
                'logo' => 'DANA.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::E_WALLET,
                'midtrans_code' => 'other_qris'
            ],
            [
                'code' => 'ovo',
                'name' => 'OVO',
                'logo' => 'OVO (New Alt).svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::E_WALLET,
                'midtrans_code' => 'other_qris'
            ],
            [
                'code' => 'linkaja',
                'name' => 'LinkAja',
                'logo' => 'LinkAja.svg',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::E_WALLET,
                'midtrans_code' => 'other_qris'
            ],

            [
                'code' => 'cash',
                'name' => 'Cash / Langsung',
                'logo' => '',
                'created_at' => $now,
                'updated_at' => $now,
                'category' => PaymentMethod::OTHERS,
            ],
        ];

        array_map(
            fn($channel) => PaymentMethod::query()->updateOrCreate(
                ['code' => $channel['code']],
                $channel
            ),
            $channels
        );
    }
}
