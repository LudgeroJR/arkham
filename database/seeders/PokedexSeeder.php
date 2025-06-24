<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pokedex;

class PokedexSeeder extends Seeder
{
    public function run(): void
    {
        Pokedex::factory(10)->create();
    }
}