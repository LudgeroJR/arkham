<?php

namespace Database\Factories;

use App\Models\Pokedex;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovetutorFactory extends Factory
{
    public function definition(): array
    {
        static $combinations = [];

        $pokedexIds = Pokedex::pluck('id')->toArray();
        $skillIds = Skill::pluck('id')->toArray();

        // Gera todas as combinações possíveis
        if (empty($combinations)) {
            foreach ($pokedexIds as $pokedexId) {
                foreach ($skillIds as $skillId) {
                    $combinations[] = [$pokedexId, $skillId];
                }
            }
            shuffle($combinations);
        }

        // Pega uma combinação única
        $combo = array_pop($combinations) ?? [reset($pokedexIds), reset($skillIds)];

        return [
            'pokedex_id' => $combo[0],
            'skill_id' => $combo[1],
        ];
    }
}