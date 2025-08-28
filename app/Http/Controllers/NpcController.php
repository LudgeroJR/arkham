<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Npc;

class NpcController extends Controller
{
    // Página de administração de NPCs
    public function adminIndex()
    {
        $npcs = \App\Models\Npc::all();
        $items = \App\Models\Item::all();
        return view('admin.psoul.npcs', compact('npcs', 'items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'nullable|integer|exists:npcs,id',
            'name' => 'required|string|max:255',
            'localization' => 'string|max:255',
            'sells' => 'array',
            'sells.*' => 'integer|exists:items,id',
            'buys' => 'array',
            'buys.*' => 'integer|exists:items,id',
        ]);

        // Cadastro ou edição
        if (!empty($data['id'])) {
            $npc = \App\Models\Npc::find($data['id']);
            if (!$npc) {
                return response()->json(['success' => false, 'message' => 'NPC não encontrado.'], 404);
            }
            $npc->update([
                'name' => $data['name'],
                'localization' => $data['localization'],
            ]);
        } else {
            $npc = \App\Models\Npc::create([
                'name' => $data['name'],
                'localization' => $data['localization'],
            ]);
        }

        // Relacionamentos: vende
        $npc->sells()->sync($data['sells'] ?? []);
        // Relacionamentos: compra
        $npc->buys()->sync($data['buys'] ?? []);

        return response()->json(['success' => true, 'message' => 'NPC salvo com sucesso!']);
    }
}