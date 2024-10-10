<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{

    public function definition(): array
    {
        return [
            //'id' => fake()->numberBetween(1, 300),
            'name' => fake()->company(),
            'reference' => fake()->text(10),
            'category_id' => fake()->numberBetween(1, 3),
            'startDate' => fake()->date(),
            'description' => fake()->paragraph(),

        ];
    }
}
    