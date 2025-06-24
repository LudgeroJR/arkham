<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Type;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Normal', 'Fire', 'Water', 'Grass', 'Electric', 'Ice', 'Fighting', 'Poison',
            'Ground', 'Flying', 'Psychic', 'Bug', 'Rock', 'Ghost', 'Dragon', 'Dark',
            'Steel', 'Fairy'
        ];

        foreach ($types as $type) {
            Type::firstOrCreate(['name' => $type]);
        }
    }
}