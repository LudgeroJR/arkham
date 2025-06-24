<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NpcBuy;

class NpcBuySeeder extends Seeder
{
    public function run(): void
    {
        NpcBuy::factory(10)->create();
    }
}