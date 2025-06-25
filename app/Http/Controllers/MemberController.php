<?php

namespace App\Http\Controllers;

use App\Models\Member;

class MemberController extends Controller
{
    public function index()
    {
        $roles = [
            1 => 'Líder',
            2 => 'Vice-líder',
            3 => 'Membro',
            4 => 'Em Teste',
        ];

        // Busca todos os membros com seus jogos, agrupados por role_id
        $members = Member::with('games')
            ->orderByRaw("FIELD(role_id, 1,2,3,4)")
            ->orderBy('name')
            ->get()
            ->groupBy('role_id');

        $overlayOpacity = 0.7;

        return view('members.index', compact('members', 'roles', 'overlayOpacity'));
    }
}