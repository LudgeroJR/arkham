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
}