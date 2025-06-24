<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NpcSell;

class NpcSellSeeder extends Seeder
{
    public function run(): void
    {
        NpcSell::factory(20)->create();
    }
}