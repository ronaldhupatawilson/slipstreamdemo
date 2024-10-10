<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{

    public function definition(): array
    {
        return [
            'id' => fake()->numberBetween(1, 300),
            'category' => fake()->text(32),

        ];
    }
}
    