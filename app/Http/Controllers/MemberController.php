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
    public function create()
    {
        // Busca todos os cargos para o select
        $roles = Role::all();
        return view('members.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Validação dos dados
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'whatsapp' => 'nullable|string|max:100',
            'discord' => 'nullable|string|max:100',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Upload do avatar se enviado
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        // Cria o membro
        Member::create($validated);

        return redirect()->route('members.index')->with('success', 'Membro cadastrado com sucesso!');
    }

    public function adminIndex()
    {
        $roles = [
            1 => 'Líder',
            2 => 'Vice-líder',
            3 => 'Membro',
            4 => 'Em Teste',
        ];

        $members = \App\Models\Member::orderByRaw("FIELD(role_id, 1,2,3,4)")
            ->orderBy('name')
            ->get();

        return view('admin.members', compact('members', 'roles'));
    }
}