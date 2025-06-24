<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movetutor;

class MovetutorSeeder extends Seeder
{
    public function run(): void
    {
        Movetutor::factory(15)->create();
    }
}