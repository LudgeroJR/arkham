<?php

namespace Database\Seeders;

use App\Models\Quest;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        RoleSeeder::class,
        RangeSeeder::class,
        TypeSeeder::class,
        MemberSeeder::class,
        SkillSeeder::class,
        ItemSeeder::class,
        QuestSeeder::class,
        PokedexSeeder::class,
        AbilitySeeder::class,
        NpcSeeder::class,
        SkillRangeSeeder::class,
        ItemQuestSeeder::class,
        ItemCompositionSeeder::class,
        AbilityPokedexSeeder::class,
        MovesetSeeder::class,
        MovetutorSeeder::class,
        EggmoveSeeder::class,
        NpcSellSeeder::class,
        NpcBuySeeder::class,
        LootSeeder::class,
        GamesTableSeeder::class,
        ]);
        
        
    }
}
