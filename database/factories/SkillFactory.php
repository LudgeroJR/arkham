<?php

namespace Database\Factories;

use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->word),
            'category' => $this->faker->randomElement(['Physical', 'Special', 'Status']),
            'type_id' => Type::inRandomOrder()->first()->id ?? 1,
            'power' => $this->faker->numberBetween(10, 120),
            'description' => $this->faker->sentence,
        ];
    }
}