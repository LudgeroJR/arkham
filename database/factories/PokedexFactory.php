<?php

namespace Database\Factories;

use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class PokedexFactory extends Factory
{
    public function definition(): array
    {
        $primaryType = Type::inRandomOrder()->first()->id ?? 1;
        $secondaryType = Type::inRandomOrder()->first()->id ?? null;
        return [
            'dex' => $this->faker->unique()->numberBetween(1, 999),
            'name' => ucfirst($this->faker->unique()->firstName),
            'description' => $this->faker->paragraph,
            'thumb' => $this->faker->imageUrl(128, 128, 'pokemon'),
            'primary_type_id' => $primaryType,
            'secondary_type_id' => $this->faker->boolean(60) ? $secondaryType : null,
        ];
    }
}