<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->sentence(6, true),
            'category' => fake()->randomElement(['Convenience goods', 'Shopping goods', 'Specialty goods', 'Unsought goods']),
            'description' => fake()->randomHtml(2, 3),
            'images' => '[]',
            'date_time' => now(),
        ];
    }
}
