<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemCompositionFactory extends Factory
{
    public function definition(): array
    {
        static $combinations = [];

        $itemIds = Item::pluck('id')->toArray();

        // Gera todas as combinações possíveis (evita item == material)
        if (empty($combinations)) {
            foreach ($itemIds as $itemId) {
                foreach ($itemIds as $materialId) {
                    if ($itemId !== $materialId) {
                        $combinations[] = [$itemId, $materialId];
                    }
                }
            }
            shuffle($combinations);
        }

        // Pega uma combinação única
        $combo = array_pop($combinations) ?? [reset($itemIds), next($itemIds) ?: reset($itemIds)];

        return [
            'item_id' => $combo[0],
            'material_id' => $combo[1],
            'amount' => $this->faker->numberBetween(1, 5),
        ];
    }
}