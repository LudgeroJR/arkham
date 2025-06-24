<?php

namespace Database\Factories;

use App\Models\Npc;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class NpcSellFactory extends Factory
{
    public function definition(): array
    {
        static $combinations = [];

        $npcIds = Npc::pluck('id')->toArray();
        $itemIds = Item::pluck('id')->toArray();

        // Gera todas as combinações possíveis
        if (empty($combinations)) {
            foreach ($npcIds as $npcId) {
                foreach ($itemIds as $itemId) {
                    $combinations[] = [$npcId, $itemId];
                }
            }
            shuffle($combinations);
        }

        // Pega uma combinação única
        $combo = array_pop($combinations) ?? [reset($npcIds), reset($itemIds)];

        return [
            'npc_id' => $combo[0],
            'item_id' => $combo[1],
        ];
    }
}