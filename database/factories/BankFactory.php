<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bank>
 */
class BankFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'  => fake()->unique()->company(),
            'color' => fake()->hexColor(),
        ];
    }
}
