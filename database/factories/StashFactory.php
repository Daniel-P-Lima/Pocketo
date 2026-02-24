<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stash>
 */
class StashFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'          => fake()->word(),
            'amount'        => fake()->randomFloat(2, 1, 2000),
            'goal_amount'   => fake()->randomFloat(2, 1, 20000),
            'purpose'       => fake()->word(),
        ];
    }
}
