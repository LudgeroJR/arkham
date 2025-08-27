<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\Type;
use App\Models\Range;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::with('type')->orderBy('name')->get();
        return view('psoul.skills', compact('skills'));
    }

    public function adminIndex()
    {
        // Carrega todas as skills, já com type e ranges (para exibir na tela)
        $skills = Skill::with(['type', 'ranges'])->orderBy('name')->get()->map(function ($skill) {
            return [
                'id' => $skill->id,
                'name' => $skill->name,
                'category' => $skill->category,
                'type_id' => $skill->type_id,
                'type_name' => $skill->type ? $skill->type->name : '',
                'power' => $skill->power,
                'description' => $skill->description,
                'ranges' => $skill->ranges->map(function ($range) {
                    return [
                        'id' => $range->id,
                        'name' => $range->name
                    ];
                })->values(),
            ];
        });

        // Carrega todos os types para o select
        $types = \App\Models\Type::orderBy('name')->get(['id', 'name']);

        // Carrega todos os ranges para o select
        $ranges = \App\Models\Range::orderBy('name')->get(['id', 'name']);

        // Retorna a view do admin
        return view('admin.psoul.skills', compact('skills', 'types', 'ranges'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:skills,name',
            'category' => 'required|in:Physical,Special,Status',
            'type_id' => 'required|exists:types,id',
            'power' => 'required|integer|min:0',
            'description' => 'required|string',
            'ranges' => 'array',
            'ranges.*' => 'exists:ranges,id'
        ]);

        // Cria a skill
        $skill = Skill::create([
            'name' => $data['name'],
            'category' => $data['category'],
            'type_id' => $data['type_id'],
            'power' => $data['power'],
            'description' => $data['description'],
        ]);

        // Associa os ranges (tabela skill_range)
        if (!empty($data['ranges'])) {
            $skill->ranges()->sync($data['ranges']);
        }

        // Retorna a skill criada (formato igual index)
        $skill = Skill::with(['type', 'ranges'])->find($skill->id);
        return response()->json([
            'success' => true,
            'skill' => [
                'id' => $skill->id,
                'name' => $skill->name,
                'category' => $skill->category,
                'type_id' => $skill->type_id,
                'type_name' => $skill->type ? $skill->type->name : '',
                'power' => $skill->power,
                'description' => $skill->description,
                'ranges' => $skill->ranges->map(function ($range) {
                    return [
                        'id' => $range->id,
                        'name' => $range->name
                    ];
                })->values(),
            ]
        ]);
    }

    public function showJson($id)
    {
        $skill = Skill::with([
            'type',
            'ranges.range',
            'movesetPokemons',
            'eggmovePokemons',
            'movetutorPokemons'
        ])->findOrFail($id);

        return response()->json([
            'id' => $skill->id,
            'name' => $skill->name,
            'category' => $skill->category,
            'type' => $skill->type ? $skill->type->name : null,
            'power' => $skill->power,
            'description' => $skill->description,
            'ranges' => $skill->ranges->map(fn($r) => $r->range ? $r->range->name : null)->filter()->values(),
            'moveset_pokemons' => $skill->movesetPokemons->map(fn($p) => ['id'=>$p->id,'name'=>$p->name, 'thumb'=>$p->thumb])->values(),
            'eggmove_pokemons' => $skill->eggmovePokemons->map(fn($p) => ['id'=>$p->id,'name'=>$p->name, 'thumb'=>$p->thumb])->values(),
            'movetutor_pokemons' => $skill->movetutorPokemons->map(fn($p) => ['id'=>$p->id,'name'=>$p->name, 'thumb'=>$p->thumb])->values(),
        ]);
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $data = $request->validate([
            'name' => "required|string|max:255|unique:skills,name,{$id}",
            'category' => 'required|in:Physical,Special,Status',
            'type_id' => 'required|exists:types,id',
            'power' => 'required|integer|min:0',
            'description' => 'required|string',
            'ranges' => 'array',
            'ranges.*' => 'exists:ranges,id'
        ]);

        $skill = \App\Models\Skill::findOrFail($id);

        // Atualiza dados
        $skill->update([
            'name' => $data['name'],
            'category' => $data['category'],
            'type_id' => $data['type_id'],
            'power' => $data['power'],
            'description' => $data['description'],
        ]);

        // Sincroniza ranges
        $skill->ranges()->sync($data['ranges'] ?? []);

        // Retorna a Skill atualizada
        $skill = \App\Models\Skill::with(['type', 'ranges'])->find($skill->id);
        return response()->json([
            'success' => true,
            'skill' => [
                'id' => $skill->id,
                'name' => $skill->name,
                'category' => $skill->category,
                'type_id' => $skill->type_id,
                'type_name' => $skill->type ? $skill->type->name : '',
                'power' => $skill->power,
                'description' => $skill->description,
                'ranges' => $skill->ranges->map(function ($range) {
                    return [
                        'id' => $range->id,
                        'name' => $range->name
                    ];
                })->values(),
            ]
        ]);
    }
    public function destroy($id)
    {
        $skill = \App\Models\Skill::findOrFail($id);

        // Remove os ranges relacionados (pivot)
        $skill->ranges()->detach();

        // Remove a Skill
        $skill->delete();

        return response()->json(['success' => true]);
    }
}