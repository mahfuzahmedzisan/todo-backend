<?php

namespace Database\Factories;

use App\Models\User;
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
            'title' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'is_completed' => $this->faker->boolean(),
            'user_id' => User::inRandomOrder()->first()->id,
            'due_date' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'completed_at' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
        ];
    }
}
