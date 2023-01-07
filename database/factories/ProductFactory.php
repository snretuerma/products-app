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
            'category' => fake()->randomElement(array ('a','b','c')),
            'description' => fake()->randomHtml(2, 3),
            'images' => fake()->image(storage_path('images'),400,300, null, false),
            'date_time' => now(),
        ];
    }
}
