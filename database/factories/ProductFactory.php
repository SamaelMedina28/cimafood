<?php

namespace Database\Factories;

use App\Models\Business;
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
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(20, 150),
            'image_path' => $this->faker->imageUrl,
            'quantity' => $this->faker->numberBetween(10, 100),
            'status' => 'available',
            'business_id' => Business::factory(),
        ];
    }
}
