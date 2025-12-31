<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Packages;
use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $username = fake()->userName();
        return [
            'username' => $username,
            'full_name' => fake()->name(),
            'password' => Hash::make('12345'),
            'password_pptp' => '12345',
            'email' => "{$username}@mail.com",
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),

            // ppp package
            'ppp_profile_id' => Packages::inRandomOrder()->first()->ppp_profile_id,
        ];
    }


    public function configure()
    {
        return $this->afterCreating(function (Customer $record) {
            $package = Packages::inRandomOrder()->first();

            $record->update([
                'ppp_profile_id' => $package->ppp_profile_id
            ]);

            $record->customerPackages()->create([
                'package_id' => $package->id,
                'start_date' => now()
            ]);
        });
    }
}
