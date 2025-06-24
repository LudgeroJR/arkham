<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AbilityPokedex;

class AbilityPokedexSeeder extends Seeder
{
    public function run(): void
    {
        AbilityPokedex::factory(25)->create();
    }
}