<?php

namespace App\Http\Controllers;

use App\Models\Pokedex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PokedexController extends Controller
{

    public function index()
    {
        // Lista para a página principal (pode ajustar os campos conforme sua necessidade)
        $pokemonsByDex = Pokedex::orderBy('dex')->orderBy('id')->get()->groupBy('dex');
        return view('psoul.pokedex', ['pokemons_by_dex' => $pokemonsByDex]);
    }

    // public function index()
    // {
    //     $pokemonsByDex = Pokedex::orderBy('dex')->orderBy('id')->get()->groupBy('dex');
    //     $allPokemons = Pokedex::orderBy('dex')->orderBy('id')->get()->values(); // array plano
    //     return view('psoul.pokedex', [
    //         'pokemons_by_dex' => $pokemonsByDex,
    //         'all_pokemons' => $allPokemons
    //     ]);
    // }
    
    public function showJson($id)
    {
        $pokemon = Pokedex::with([
            'primaryType',
            'secondaryType',
            'abilities',
            'moveset.skill.type',
            'moveset.skill.ranges',
            'eggmoves.skill.type',
            'eggmoves.skill.ranges',
            'movetutors.skill.type',
            'movetutors.skill.ranges',
            'loot.item',
        ])->findOrFail($id);

        // Abilities
        $abilities = $pokemon->abilities->map(function ($ability) {
            return [
                'name' => $ability->name,
                'description' => $ability->description,
                'hidden' => (bool)$ability->pivot->hidden,
            ];
        })->values();

        // Moveset (adicionando ranges)
        $moveset = $pokemon->moveset->sortBy('position')->map(function ($move) {
            $ranges = $move->skill
                ? $move->skill->ranges->map(function($r) {
                    return ['name' => $r->range ? $r->range->name : null];
                })
                : [];
            return [
                'position' => $move->position,
                'category' => $move->skill->category ?? null,
                'name' => $move->skill->name ?? null,
                'power' => $move->skill->power ?? null,
                'level' => $move->level,
                'type' => $move->skill->type->name ?? null,
                'ranges' => $ranges,
            ];
        })->values();

        // Eggmoves
        $eggmoves = $pokemon->eggmoves->map(function ($eggm) {
            $ranges = $eggm->skill
                ? $eggm->skill->ranges->map(fn($r) => ['name' => $r->range ? $r->range->name : null])
                : [];
            return [
                'category' => $eggm->skill->category ?? null,
                'name' => $eggm->skill->name ?? null,
                'power' => $eggm->skill->power ?? null,
                'type' => $eggm->skill->type->name ?? null,
                'ranges' => $ranges,
            ];
        })->values();

        $movetutors = $pokemon->movetutors->map(function ($tutor) {
            $ranges = $tutor->skill
                ? $tutor->skill->ranges->map(fn($r) => ['name' => $r->range ? $r->range->name : null])
                : [];
            return [
                'category' => $tutor->skill->category ?? null,
                'name' => $tutor->skill->name ?? null,
                'power' => $tutor->skill->power ?? null,
                'type' => $tutor->skill->type->name ?? null,
                'ranges' => $ranges,
            ];
        })->values();

        // Loot
        $loot = $pokemon->loot->map(function ($loot) {
            return [
                'name' => $loot->item->name ?? null,
                'amount_min' => $loot->amount_min,
                'amount_max' => $loot->amount_max,
            ];
        })->values();

        // Retorno final
        return response()->json([
            'id' => $pokemon->id,
            'dex' => $pokemon->dex,
            'name' => $pokemon->name,
            'description' => $pokemon->description,
            'thumb' => $pokemon->thumb,
            'primary_type' => $pokemon->primaryType->name ?? null,
            'secondary_type' => $pokemon->secondaryType->name ?? null,
            'abilities' => $abilities,
            'moveset' => $moveset,
            'egg_moves' => $eggmoves,
            'move_tutors' => $movetutors,
            'loot' => $loot,
        ]);
    }
}