<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Página de listagem
    public function index()
    {
        $items = Item::orderBy('name')->get();
        return view('psoul.itens', compact('items'));
    }

    // Detalhe AJAX para o modal
    public function showJson($id)
    {
        $item = Item::with([
            'materials',
            'usedFor',
            'droppedBy',
            'soldByNPCs',
            'boughtByNPCs',
            'questRewards'
        ])->findOrFail($id);

        return response()->json([
            'id' => $item->id,
            'name' => $item->name,
            'materials' => ($item->materials ?? collect([]))->map(fn($i) => [
                'id' => $i->id,
                'name' => $i->name,
                'amount' => $i->pivot->amount ?? null
            ])->values(),
            'used_for' => ($item->usedFor ?? collect([]))->map(fn($i) => [
                'id' => $i->id,
                'name' => $i->name
            ])->values(),
            'dropped_by' => ($item->droppedBy ?? collect([]))->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'thumb' => $p->thumb
            ])->values(),
            'sold_by' => ($item->soldByNPCs ?? collect([]))->map(fn($n) => [
                'id' => $n->id,
                'name' => $n->name,
                'localization' => $n->localization
            ])->values(),
            'bought_by' => ($item->boughtByNPCs ?? collect([]))->map(fn($n) => [
                'id' => $n->id,
                'name' => $n->name,
                'localization' => $n->localization
            ])->values(),
            'quest_rewards' => ($item->questRewards ?? collect([]))->map(fn($q) => [
                'id' => $q->id,
                'name' => $q->name,
                'link' => $q->link ?? null
            ])->values(),
        ]);
    }

    public function adminIndex()
    {
        $items = \App\Models\Item::orderBy('name')->get();
        return view('admin.items', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'materials' => 'array',
            'materials.*.material_id' => 'required_with:materials|exists:items,id',
            'materials.*.amount' => 'required_with:materials|integer|min:1',
        ]);

        // Cria o item
        $item = \App\Models\Item::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
        ]);

        // Se houver materiais, salva na item_compositions
        if (!empty($validated['materials'])) {
            foreach ($validated['materials'] as $mat) {
                \App\Models\ItemComposition::create([
                    'item_id' => $item->id,
                    'material_id' => $mat['material_id'],
                    'amount' => $mat['amount'],
                ]);
            }
        }

        // Retorna o item criado já com materiais (para atualizar a lista sem recarregar)
        $item->materials = $item->materials()->withPivot('amount')->get();
        return response()->json(['success' => true, 'item' => $item]);
    }

    public function update(Request $request, \App\Models\Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'materials' => 'array',
            'materials.*.material_id' => 'required_with:materials|exists:items,id',
            'materials.*.amount' => 'required_with:materials|integer|min:1',
        ]);

        // Atualiza o item
        $item->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
        ]);

        // Remove composições antigas
        $item->materialCompositions()->delete();

        // Adiciona composições novas, se houver
        if (!empty($validated['materials'])) {
            foreach ($validated['materials'] as $mat) {
                \App\Models\ItemComposition::create([
                    'item_id' => $item->id,
                    'material_id' => $mat['material_id'],
                    'amount' => $mat['amount'],
                ]);
            }
        }

        // Retorna o item atualizado (com materiais)
        $item->materials = $item->materials()->withPivot('amount')->get();
        return response()->json(['success' => true, 'item' => $item]);
    }
    
    public function compositions(\App\Models\Item $item)
    {
        return $item->materialCompositions()->get(['material_id', 'amount']);
    }
    
    public function destroy(Item $item)
    {
        // Remove relações (composições) antes de excluir o item principal
        $item->materialCompositions()->delete();

        // Dica: Se o item for usado como material em outros, remova também as relações inversas
        \App\Models\ItemComposition::where('material_id', $item->id)->delete();

        // Exclui o item principal
        $item->delete();

        return response()->json(['success' => true, 'message' => 'Item excluído com sucesso!']);
    }
}