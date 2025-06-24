<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Moveset;

class MovesetSeeder extends Seeder
{
    public function run(): void
    {
        Moveset::factory(30)->create();
    }
}