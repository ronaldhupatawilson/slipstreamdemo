<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{

    public function definition(): array
    {
        return [
            'id' => fake()->numberBetween(1, 300),
            'name' => fake()->text(255),
            'reference' => fake()->sentence(),
            'category_id' => fake()->numberBetween(1, 300),
            'startDate' => fake()->date(),
            'description' => fake()->sentence(),

        ];
    }
}
    