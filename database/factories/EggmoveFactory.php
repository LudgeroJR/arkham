<?php

namespace Database\Factories;

use App\Models\Pokedex;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class EggmoveFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pokedex_id' => Pokedex::inRandomOrder()->first()->id ?? 1,
            'skill_id' => Skill::inRandomOrder()->first()->id ?? 1,
        ];
    }
}