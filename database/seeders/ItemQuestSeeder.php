<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemQuest;

class ItemQuestSeeder extends Seeder
{
    public function run(): void
    {
        ItemQuest::factory(20)->create();
    }
}