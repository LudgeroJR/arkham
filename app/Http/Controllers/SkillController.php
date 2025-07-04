<?php

namespace App\Http\Controllers;

use App\Models\Skill;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::with('type')->orderBy('name')->get();
        return view('psoul.skills', compact('skills'));
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
}