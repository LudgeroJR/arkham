<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loot;

class LootSeeder extends Seeder
{
    public function run(): void
    {
        Loot::factory(20)->create();
    }
}