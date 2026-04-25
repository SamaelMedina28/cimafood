<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'logo' => $this->faker->imageUrl,
            'banner' => $this->faker->imageUrl,
            'phone' => $this->faker->phoneNumber,
            'open_time' => $this->faker->time('H:i'),
            'close_time' => $this->faker->time('H:i'),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'rating' => rand(0, 5),
            'user_id' => 1,
        ];
    }
}
