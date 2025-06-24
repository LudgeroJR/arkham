<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ability;

class AbilitySeeder extends Seeder
{
    public function run(): void
    {
        Ability::factory(10)->create();
    }
}