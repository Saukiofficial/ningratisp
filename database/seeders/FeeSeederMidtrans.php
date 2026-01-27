<?php

namespace Database\Seeders;

use App\Models\Fee;
use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeeSeederMidtrans extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $bankId = PaymentMethod::with([])->where('category', '=', PaymentMethod::BANK)->pluck('id')?->toArray();
        $recordsBank = array_map(function ($id) use ($now) {
            return [
                'payment_method_id' => $id,
                'amount' => 4000,
                'unit' => Fee::NOMINAL,
                'created_at' => $now,
                'started_at' => $now,
                'updated_at' => $now
            ];
        }, $bankId);

        $fees = [
            [
                'payment_method_id' => PaymentMethod::with([])->where('code', '=', 'gopay')->first()->id,
                'amount' => 2,
                'unit' => Fee::PERCENTAGE,
                'created_at' => $now,
                'started_at' => $now,
                'updated_at' => $now
            ],
            [
                'payment_method_id' => PaymentMethod::with([])->where('code', '=', 'qris')->first()->id,
                'amount' => 0.7,
                'unit' => Fee::PERCENTAGE,
                'created_at' => $now,
                'started_at' => $now,
                'updated_at' => $now
            ],
            [
                'payment_method_id' => PaymentMethod::with([])->where('code', '=', 'dana')->first()->id,
                'amount' => 1.67,
                'unit' => Fee::PERCENTAGE,
                'created_at' => $now,
                'started_at' => $now,
                'updated_at' => $now
            ],
        ];

        $records = array_merge($recordsBank, $fees);

        DB::table((new Fee())->getTable())->insert($records);
    }
}
