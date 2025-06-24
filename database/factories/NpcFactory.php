<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NpcFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'localization' => 'Cidade ' . $this->faker->city,
        ];
    }
}