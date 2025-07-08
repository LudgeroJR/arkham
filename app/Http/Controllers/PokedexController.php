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

    public function adminIndex()
    {
        $pokemons = \App\Models\Pokedex::orderBy('dex')->get();
        $types = \App\Models\Type::orderBy('name')->get();
        $skills = \App\Models\Skill::orderBy('name')->get();
        $abilities = \App\Models\Ability::orderBy('name')->get();
        $items = \App\Models\Item::orderBy('name')->get();

        return view('admin.pokedex', compact('pokemons', 'types', 'skills', 'abilities', 'items'));
    }

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

    public function storeAjax(\Illuminate\Http\Request $request)
    {
        try {
            $validated = $request->validate([
                'dex' => 'required|integer', // removido unique
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'thumb' => 'nullable|string|max:100', // nome do arquivo
                'primary_type' => 'required|exists:types,id',
                'secondary_type' => 'nullable|exists:types,id',

                // Abilities
                'abilities' => 'array',
                'abilities.*.id' => 'required|exists:abilities,id',
                'abilities.*.hidden' => 'boolean',

                // Moveset
                'moveset' => 'array',
                'moveset.*.position' => 'required|integer',
                'moveset.*.skill_id' => 'required|exists:skills,id',
                'moveset.*.level' => 'required|integer|min:1|max:99',

                // Eggmoves
                'eggmoves' => 'array',
                'eggmoves.*.skill_id' => 'required|exists:skills,id',

                // Movetutors
                'movetutors' => 'array',
                'movetutors.*.skill_id' => 'required|exists:skills,id',

                // Loot
                'loot' => 'array',
                'loot.*.item_id' => 'required|exists:items,id',
                'loot.*.min' => 'required|integer|min:1|max:99',
                'loot.*.max' => 'nullable|integer|min:1|max:99', // agora nullable
            ]);

            // Cria o registro principal
            $poke = new \App\Models\Pokedex();
            $poke->dex = $validated['dex'];
            $poke->name = $validated['name'];
            $poke->description = $validated['description'] ?? null;
            $poke->thumb = $validated['thumb'] ?? null;
            $poke->primary_type_id = $validated['primary_type'];
            $poke->secondary_type_id = $validated['secondary_type'] ?? null;
            $poke->save();

            // Abilities (pivot ability_pokedex)
            if (!empty($validated['abilities'])) {
                foreach ($validated['abilities'] as $ab) {
                    $poke->abilities()->attach($ab['id'], [
                        'hidden' => !empty($ab['hidden']),
                    ]);
                }
            }

            // Moveset
            if (!empty($validated['moveset'])) {
                foreach ($validated['moveset'] as $move) {
                    $poke->moveset()->create([
                        'position' => $move['position'],
                        'skill_id' => $move['skill_id'],
                        'level' => $move['level'],
                    ]);
                }
            }

            // Eggmoves
            if (!empty($validated['eggmoves'])) {
                foreach ($validated['eggmoves'] as $egg) {
                    $poke->eggmoves()->create([
                        'skill_id' => $egg['skill_id'],
                    ]);
                }
            }

            // Movetutors
            if (!empty($validated['movetutors'])) {
                foreach ($validated['movetutors'] as $mt) {
                    $poke->movetutors()->create([
                        'skill_id' => $mt['skill_id'],
                    ]);
                }
            }

            // Loot
            if (!empty($validated['loot'])) {
                foreach ($validated['loot'] as $loot) {
                    $poke->loot()->create([
                        'item_id' => $loot['item_id'],
                        'amount_min' => $loot['min'],
                        'amount_max' => isset($loot['max']) && $loot['max'] !== '' ? $loot['max'] : null,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Pokémon cadastrado com sucesso!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function showAjax(\App\Models\Pokedex $pokedex)
    {
        $pokedex->load([
            'abilities',
            'moveset',
            'eggmoves',
            'movetutors',
            'loot'
        ]);
        return response()->json([
            'success' => true,
            'pokemon' => [
                'id' => $pokedex->id,
                'dex' => $pokedex->dex,
                'name' => $pokedex->name,
                'description' => $pokedex->description,
                'thumb' => $pokedex->thumb,
                'primary_type' => $pokedex->primary_type_id,
                'secondary_type' => $pokedex->secondary_type_id,
                'abilities' => $pokedex->abilities->map(function($ab) {
                    return ['id' => $ab->id, 'hidden' => (bool)$ab->pivot->hidden];
                })->toArray(),
                'moveset' => $pokedex->moveset->map(function($mv) {
                    return [
                        'position' => $mv->position,
                        'skill_id' => $mv->skill_id,
                        'level' => $mv->level
                    ];
                })->toArray(),
                'eggmoves' => $pokedex->eggmoves->map(function($em) {
                    return ['skill_id' => $em->skill_id];
                })->toArray(),
                'movetutors' => $pokedex->movetutors->map(function($mt) {
                    return ['skill_id' => $mt->skill_id];
                })->toArray(),
                'loot' => $pokedex->loot->map(function($l) {
                    return [
                        'item_id' => $l->item_id,
                        'min' => $l->amount_min,
                        'max' => $l->amount_max
                    ];
                })->toArray(),
            ]
        ]);
    }

    public function updateAjax(\Illuminate\Http\Request $request, \App\Models\Pokedex $pokedex)
    {
        try{
            $validated = $request->validate([
                'dex' => 'required|integer',
                'name' => 'required|string|max:100',
                'description' => 'nullable|string',
                'thumb' => 'nullable|string|max:100',
                'primary_type' => 'required|exists:types,id',
                'secondary_type' => 'nullable|exists:types,id',

                'abilities' => 'array',
                'abilities.*.id' => 'required|exists:abilities,id',
                'abilities.*.hidden' => 'boolean',

                'moveset' => 'array',
                'moveset.*.position' => 'required|integer',
                'moveset.*.skill_id' => 'required|exists:skills,id',
                'moveset.*.level' => 'required|integer|min:1|max:99',

                'eggmoves' => 'array',
                'eggmoves.*.skill_id' => 'required|exists:skills,id',

                'movetutors' => 'array',
                'movetutors.*.skill_id' => 'required|exists:skills,id',

                'loot' => 'array',
                'loot.*.item_id' => 'required|exists:items,id',
                'loot.*.min' => 'required|integer|min:1|max:99',
                'loot.*.max' => 'nullable|integer|min:1|max:99',
            ]);

            // Atualiza dados principais
            $pokedex->dex = $validated['dex'];
            $pokedex->name = $validated['name'];
            $pokedex->description = $validated['description'] ?? null;
            $pokedex->thumb = $validated['thumb'] ?? null;
            $pokedex->primary_type_id = $validated['primary_type'];
            $pokedex->secondary_type_id = $validated['secondary_type'] ?? null;
            $pokedex->save();

            // Abilities (pivot)
            $pokedex->abilities()->detach();
            if (!empty($validated['abilities'])) {
                foreach ($validated['abilities'] as $ab) {
                    $pokedex->abilities()->attach($ab['id'], [
                        'hidden' => !empty($ab['hidden']),
                    ]);
                }
            }

            // Moveset
            $pokedex->moveset()->delete();
            if (!empty($validated['moveset'])) {
                foreach ($validated['moveset'] as $move) {
                    $pokedex->moveset()->create([
                        'position' => $move['position'],
                        'skill_id' => $move['skill_id'],
                        'level' => $move['level'],
                    ]);
                }
            }

            // Eggmoves
            $pokedex->eggmoves()->delete();
            if (!empty($validated['eggmoves'])) {
                foreach ($validated['eggmoves'] as $egg) {
                    $pokedex->eggmoves()->create([
                        'skill_id' => $egg['skill_id'],
                    ]);
                }
            }

            // Movetutors
            $pokedex->movetutors()->delete();
            if (!empty($validated['movetutors'])) {
                foreach ($validated['movetutors'] as $mt) {
                    $pokedex->movetutors()->create([
                        'skill_id' => $mt['skill_id'],
                    ]);
                }
            }

            // Loot
            $pokedex->loot()->delete();
            if (!empty($validated['loot'])) {
                foreach ($validated['loot'] as $loot) {
                    $pokedex->loot()->create([
                        'item_id' => $loot['item_id'],
                        'amount_min' => $loot['min'],
                        'amount_max' => $loot['max'],
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Pokémon atualizado com sucesso!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        }
    } 

    public function destroyAjax(\App\Models\Pokedex $pokedex)
    {
        try {
            // Exclui as relações (ordem não importa pois foreign keys não são CASCADE)
            $pokedex->moveset()->delete();
            $pokedex->eggmoves()->delete();
            $pokedex->movetutors()->delete();
            $pokedex->loot()->delete();
            $pokedex->abilities()->detach(); // Tabela pivô ability_pokedex

            // Exclui o próprio pokémon
            $pokedex->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pokémon excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir Pokémon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir Pokémon: ' . $e->getMessage()
            ], 500);
        }
    }
    
}