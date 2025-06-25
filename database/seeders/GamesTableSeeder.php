<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\Member;

class GamesTableSeeder extends Seeder
{
    public function run()
    {
        // Ajuste os nomes dos membros e ids conforme sua base!
        $games = [
            ['member_id' => 1, 'name' => 'Psoul', 'nick' => 'ArkhamLider'],
            ['member_id' => 1, 'name' => 'Arkus', 'nick' => 'BatmanLider'],
            ['member_id' => 2, 'name' => 'Psoul', 'nick' => 'ArkhamVice'],
            ['member_id' => 2, 'name' => 'Alliance', 'nick' => 'RobinVice'],
            ['member_id' => 3, 'name' => 'PokemonBR', 'nick' => 'AshSilver'],
            // Adicione mais conforme necessário
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}