<?php

namespace App\Http\Controllers;

use App\Models\Quest;

class QuestController extends Controller
{
    public function index()
    {
        // Carrega quests com recompensas (itens)
        $quests = Quest::with('rewards')->orderBy('name')->get();
        return view('psoul.quests', compact('quests'));
    }
}