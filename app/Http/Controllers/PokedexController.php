<?php

namespace App\Http\Controllers;

use App\Models\Pokedex;
use Illuminate\Http\Request;

class PokedexController extends Controller
{
    /**
     * Exibe a lista da pokedex.
     */
    public function index()
    {
        // Lista para a página principal (pode ajustar os campos conforme sua necessidade)
        $pokemonsByDex = Pokedex::orderBy('dex')->orderBy('id')->get()->groupBy('dex');
        return view('psoul.pokedex', ['pokemons_by_dex' => $pokemonsByDex]);
    }

    /**
     * Exibe o popup com detalhes do Pokémon.
     *
     * @param int $id  ID do Pokémon na tabela pokedex
     */
    public function show($id)
    {
        $pokemon = Pokedex::with([
                'primaryType',
                'secondaryType',
                // Abilities com info de hidden
                'abilities',
                // Moveset ordenado por position
                'moveset.skill.type',
                'eggmoves.skill.type',
                'movetutors.skill.type',
                // Loot e o item relacionado
                'loot.item',
            ])->findOrFail($id);

        // Organiza as abilities em normal/hidden para facilitar no Blade
        $abilities = $pokemon->abilities->map(function ($ability) {
            return [
                'name'   => $ability->name,
                'hidden' => $ability->pivot->hidden,
                // Adicione mais campos se tiver na tabela abilities
            ];
        });

        // Moveset ordenado por nível/posição
        $moveset = $pokemon->moveset->sortBy('position')->map(function ($move) {
            return [
                'name'     => $move->skill->name,
                'level'    => $move->level,
                'position' => $move->position,
                'type'     => $move->skill->type->name ?? null,
                'category' => $move->skill->category,
                'power'    => $move->skill->power,
                // Adicione outros campos da skill se necessário
            ];
        })->values();

        // Egg Moves
        $eggmoves = $pokemon->eggmoves->map(function ($eggm) {
            return [
                'name'     => $eggm->skill->name,
                'type'     => $eggm->skill->type->name ?? null,
                'category' => $eggm->skill->category,
                'power'    => $eggm->skill->power,
            ];
        });

        // Tutor Moves
        $tutormoves = $pokemon->movetutors->map(function ($tutor) {
            return [
                'name'     => $tutor->skill->name,
                'type'     => $tutor->skill->type->name ?? null,
                'category' => $tutor->skill->category,
                'power'    => $tutor->skill->power,
            ];
        });

        // Loots
        $loot = $pokemon->loot->map(function ($loot) {
            return [
                'item'       => $loot->item->name,
                'amount_min' => $loot->amount_min,
                'amount_max' => $loot->amount_max,
            ];
        });

        // Dados principais para o popup
        $data = [
            'id'           => $pokemon->id,
            'dex'          => $pokemon->dex,
            'name'         => $pokemon->name,
            'description'  => $pokemon->description,
            'thumb'        => $pokemon->thumb,
            'primary_type' => $pokemon->primaryType->name ?? null,
            'secondary_type' => $pokemon->secondaryType->name ?? null,
            'abilities'    => $abilities,
            'moveset'      => $moveset,
            'eggmoves'     => $eggmoves,
            'tutormoves'   => $tutormoves,
            'loot'         => $loot,
        ];

        return view('pokedex.popup', compact('data', 'pokemon'));
    }
}