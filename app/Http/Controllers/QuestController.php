<?php

namespace App\Http\Controllers;

use App\Models\Quest;
use Illuminate\Http\Request;

class QuestController extends Controller
{
    public function index()
    {
        // Carrega quests com recompensas (itens)
        $quests = Quest::with('rewards')->orderBy('name')->get();
        return view('psoul.quests', compact('quests'));
    }
    public function adminIndex()
    {
        $quests = \App\Models\Quest::with('rewards')->orderBy('name')->get();
        $items = \App\Models\Item::orderBy('name')->get();
        return view('admin.quests', compact('quests', 'items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'requirements' => 'required|string|max:1000',
            'link' => 'nullable|url|max:255',
            'rewards' => 'nullable|array',
            'rewards.*.item_id' => 'required|exists:items,id',
            'rewards.*.amount' => 'required|integer|min:1',
        ]);

        $quest = \App\Models\Quest::create([
            'name' => $data['name'],
            'requirements' => $data['requirements'],
            'link' => $data['link'] ?? null,
        ]);

        // Monta array para sync com quantidade
        $syncData = [];
        if (!empty($data['rewards'])) {
            foreach ($data['rewards'] as $reward) {
                $syncData[$reward['item_id']] = ['amount' => $reward['amount']];
            }
            $quest->rewards()->sync($syncData);
        }

        return response()->json(['success' => true, 'message' => 'Quest cadastrada com sucesso!']);
    }

    public function update(Request $request, Quest $quest)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'requirements' => 'required|string|max:1000',
            'link' => 'nullable|url|max:255',
            'rewards' => 'nullable|array',
            'rewards.*.item_id' => 'required|exists:items,id',
            'rewards.*.amount' => 'required|integer|min:1',
        ]);

        $quest->update([
            'name' => $data['name'],
            'requirements' => $data['requirements'],
            'link' => $data['link'] ?? null,
        ]);

        // Monta array para sync com quantidade
        $syncData = [];
        if (!empty($data['rewards'])) {
            foreach ($data['rewards'] as $reward) {
                $syncData[$reward['item_id']] = ['amount' => $reward['amount']];
            }
            $quest->rewards()->sync($syncData);
        } else {
            $quest->rewards()->detach();
        }

        return response()->json(['success' => true, 'message' => 'Quest atualizada com sucesso!']);
    }

    public function showAjax(Quest $quest)
    {
        $quest->load('rewards'); // carrega os itens de recompensa
        // Retornando os itens com quantidade (pivot: amount)
        return response()->json([
            'success' => true,
            'quest' => [
                'id' => $quest->id,
                'name' => $quest->name,
                'requirements' => $quest->requirements,
                'link' => $quest->link,
                'rewards' => $quest->rewards->map(function($item){
                    return [
                        'item_id' => $item->id,
                        'amount' => $item->pivot->amount
                    ];
                })->toArray()
            ]
        ]);
    }

    public function destroy(Quest $quest)
    {
        // Remove os registros da tabela item_quest relacionados à quest
        $quest->rewards()->detach();
        $quest->delete();

        return response()->json(['success' => true, 'message' => 'Quest excluída com sucesso!']);
    }
}