<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Npc;

class NpcSeeder extends Seeder
{
    public function run(): void
    {
        Npc::factory(5)->create();
    }
}