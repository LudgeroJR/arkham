<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Quest;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemQuestFactory extends Factory
{
    public function definition(): array
    {
        static $combinations = [];

        $questIds = Quest::pluck('id')->toArray();
        $itemIds = Item::pluck('id')->toArray();

        // Gera todas as combinações possíveis
        if (empty($combinations)) {
            foreach ($questIds as $questId) {
                foreach ($itemIds as $itemId) {
                    $combinations[] = [$questId, $itemId];
                }
            }
            shuffle($combinations);
        }

        // Pega uma combinação única
        $combo = array_pop($combinations) ?? [reset($questIds), reset($itemIds)];

        return [
            'quest_id' => $combo[0],
            'item_id' => $combo[1],
        ];
    }
}