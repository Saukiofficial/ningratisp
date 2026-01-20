<?php

namespace Database\Factories;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Diskon ' . fake()->words(3, true),
            'code' => fake()->bothify(),
            'type' => Discount::FIXED_AMOUNT,
            'value' => fake()->randomElement(range(5000, 10000, 1000)),
            'applicable_to' => Discount::FOR_INVOICE
        ];
    }
}
