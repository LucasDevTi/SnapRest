<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::inRandomOrder()->first()->id,
            'street' => fake()->text('25'),
            'neighborhood' => fake()->text('25'),
            'number' => rand(0, 1000),
            'is_primary' => function (array $attributes) {
                $hasPrimary = Address::where('client_id', $attributes['client_id'])
                    ->where('is_primary', true)
                    ->exists();
                return !$hasPrimary;
            }
        ];
    }
}
