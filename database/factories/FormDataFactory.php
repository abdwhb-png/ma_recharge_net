<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FormData>
 */
class FormDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['transcash', 'pcs', 'neosurf'];
        return [
            'ip_address' => fake()->ipv4(),
            'data' => [
                'type' => $types[array_rand($types)],
                'code' => Str::random(12),
                'amount' => fake()->numberBetween(50, 150) . "€",
            ]
        ];
    }
}
