<?php

namespace Database\Factories;

use App\Models\Skill;
use App\Models\Range;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillRangeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'skill_id' => Skill::inRandomOrder()->first()->id ?? 1,
            'range_id' => Range::inRandomOrder()->first()->id ?? 1,
        ];
    }
}
