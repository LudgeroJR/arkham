<?php

namespace Database\Factories;

use App\Models\Pokedex;
use App\Models\Ability;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbilityPokedexFactory extends Factory
{
    public function definition(): array
    {
        static $combinations = [];

        $pokedexIds = Pokedex::pluck('id')->toArray();
        $abilityIds = Ability::pluck('id')->toArray();

        // Gera todas as combinações possíveis
        if (empty($combinations)) {
            foreach ($pokedexIds as $pokedexId) {
                foreach ($abilityIds as $abilityId) {
                    $combinations[] = [$pokedexId, $abilityId];
                }
            }
            shuffle($combinations);
        }

        // Pega uma combinação única
        $combo = array_pop($combinations) ?? [reset($pokedexIds), reset($abilityIds)];

        return [
            'pokedex_id' => $combo[0],
            'ability_id' => $combo[1],
        ];
    }
}