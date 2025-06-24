<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AbilityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->word),
            'description' => $this->faker->sentence,
        ];
    }
}