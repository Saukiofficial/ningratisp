<?php

namespace Database\Factories;

use App\Models\PppProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Packages>
 */
class PackagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ppp = PppProfile::inRandomOrder()->first();

        return [
            'name' => 'Paket ' . $ppp->profile_name,
            'price' => fake()->randomElement(range(50000, 500000, 10000)),
            'ppp_profile_id' => $ppp->id
        ];
    }
}
