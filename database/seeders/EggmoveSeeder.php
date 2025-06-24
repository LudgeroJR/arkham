<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Eggmove;

class EggmoveSeeder extends Seeder
{
    public function run(): void
    {
        Eggmove::factory(10)->create();
    }
}