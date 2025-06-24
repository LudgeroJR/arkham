<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;
use App\Models\Range;
use App\Models\SkillRange;

class SkillRangeSeeder extends Seeder
{
    public function run(): void
    {
        $skills = Skill::pluck('id')->toArray();
        $ranges = Range::pluck('id')->toArray();

        $combinations = [];
        foreach ($skills as $skillId) {
            foreach ($ranges as $rangeId) {
                $combinations[] = ['skill_id' => $skillId, 'range_id' => $rangeId];
            }
        }

        shuffle($combinations);

        // Defina quantos registros deseja criar (exemplo: 30)
        $toCreate = array_slice($combinations, 0, 15);

        foreach ($toCreate as $data) {
            SkillRange::firstOrCreate($data);
        }
    }
}