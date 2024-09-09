<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task' => $this->faker->sentence(), // Generate a random task
            'user_id' => $this->faker->numberBetween(1, 10), // Associate with a random user
            'status' => $this->faker->randomElement(['PENDING', 'COMPLETED']), // Random status
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
