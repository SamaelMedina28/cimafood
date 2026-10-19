<?php

namespace Database\Factories;

use App\Models\User;
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
            'name' => $this->faker->company,
            'description' => $this->faker->sentence,
            'logo' => $this->faker->imageUrl,
            'banner' => $this->faker->imageUrl,
            'phone' => $this->faker->phoneNumber,
            // Abierto todo el día para que los pedidos de prueba no dependan de la hora
            'open_time' => '00:00',
            'close_time' => '23:59',
            'status' => 'active',
            'rating' => 0,
            'user_id' => User::factory()->vendor(),
        ];
    }
}
