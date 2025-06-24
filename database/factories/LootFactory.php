<?php

namespace Database\Factories;

use App\Models\Pokedex;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class LootFactory extends Factory
{
    public function definition(): array
    {
        static $combinations = [];

        $pokedexIds = Pokedex::pluck('id')->toArray();
        $itemIds = Item::pluck('id')->toArray();

        // Gera todas as combinações possíveis
        if (empty($combinations)) {
            foreach ($pokedexIds as $pokedexId) {
                foreach ($itemIds as $itemId) {
                    $combinations[] = [$pokedexId, $itemId];
                }
            }
            shuffle($combinations);
        }

        // Pega uma combinação única
        $combo = array_pop($combinations) ?? [reset($pokedexIds), reset($itemIds)];

        $min = $this->faker->numberBetween(1, 3);

        return [
            'pokedex_id' => $combo[0],
            'item_id' => $combo[1],
            'amount_min' => $min,
            'amount_max' => $min + $this->faker->numberBetween(0, 3),
        ];
    }
}