<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Range;

class RangeSeeder extends Seeder
{
    public function run(): void
    {
        $ranges = [
            'Target',
            'Frontal',
            'Area',
            'Gap Closed'
        ];

        foreach ($ranges as $range) {
            Range::firstOrCreate(['name' => $range]);
        }
    }
}