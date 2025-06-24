<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemComposition;

class ItemCompositionSeeder extends Seeder
{
    public function run(): void
    {
        ItemComposition::factory(15)->create();
    }
}