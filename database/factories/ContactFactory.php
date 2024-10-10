<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{

    public function definition(): array
    {
        return [
            //'id' => fake()->numberBetween(1, 300),
            'customer_id' => fake()->numberBetween(1, 300),
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),

        ];
    }
}
    