<?php

namespace Database\Seeders;

use App\Models\Fee;
use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeeCashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cash = PaymentMethod::query()->where(
            ['code' => 'cash']
        )->firstOrFail();

        Fee::query()->updateOrCreate(
            ['payment_method_id' => $cash->id],
            [
                'payment_method_id' => $cash->id,
                'amount' => 0,
                'unit' => Fee::NOMINAL,
                'started_at' => now()
            ]
        );
    }
}
