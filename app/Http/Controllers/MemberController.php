<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class MemberController extends Controller
{
    public function index()
    {
        $roles = config('roles');

        // Busca todos os membros com seus jogos, agrupados por role_id
        $members = Member::with('games')
            ->orderByRaw("FIELD(role_id, 1,2,3,4)")
            ->orderBy('name')
            ->get()
            ->groupBy('role_id');

        $overlayOpacity = 0.7;

        return view('members.index', compact('members', 'roles', 'overlayOpacity'));
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
            'start_in' => 'nullable|date',
        ]);

        // Upload do avatar se enviado
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $validated['start_in'] = $request->input('start_in');

        // Cria o membro
        Member::create($validated);

        return redirect()->route('members.index')->with('success', 'Membro cadastrado com sucesso!');
    }

    public function adminIndex()
    {
        $roles = config('roles');

        $members = \App\Models\Member::orderByRaw("FIELD(role_id, 1,2,3,4)")
            ->orderBy('name')
            ->get();

        return view('admin.members', compact('members', 'roles'));
    }

    public function storeAjax(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'whatsapp' => 'nullable|string|max:100',
                'discord' => 'nullable|string|max:100',
                'role_id' => 'required|integer',
                'start_in' => 'nullable|date',
                'games' => 'array',
                'games.*.name' => 'nullable|string|max:255',
                'games.*.nick' => 'nullable|string|max:255',
            ]);

            // Processa o arquivo de avatar, se enviado
            if ($request->hasFile('avatar')) {
                $avatarDir = public_path('images/avatars');
                if (!file_exists($avatarDir)) {
                    mkdir($avatarDir, 0777, true);
                }
                $avatarName = time() . '_' . $request->file('avatar')->getClientOriginalName();
                $request->file('avatar')->move($avatarDir, $avatarName);
                $validated['avatar'] = $avatarName;
            } else {
                $validated['avatar'] = null;
            }

            $validated['start_in'] = $request->input('start_in');

            // Cria o membro
            $member = \App\Models\Member::create($validated);

            // Salva os jogos, se enviados
            if ($request->has('games')) {
                foreach ($request->input('games') as $game) {
                    if (!empty($game['name']) || !empty($game['nick'])) {
                        $member->games()->create([
                            'name' => $game['name'] ?? '',
                            'nick' => $game['nick'] ?? '',
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'member' => [
                    'id' => $member->id,
                    'name' => $member->name,
                    'avatar' => $member->avatar ? asset('images/avatars/' . $member->avatar) : null,
                    'whatsapp' => $member->whatsapp,
                    'discord' => $member->discord,
                    'role_id' => $member->role_id,
                    'role' => config('roles')[$member->role_id] ?? '',
                    'start_in' => $member->start_in,
                ],
                'message' => 'Membro cadastrado com sucesso!',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar membro: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno no servidor: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroyAjax(Member $member)
    {
        try {
            // Remove os jogos do membro (se desejar deletar em cascata)
            $member->games()->delete();
            $member->delete();

            return response()->json([
                'success' => true,
                'message' => 'Membro excluído com sucesso!',
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir membro: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir membro: ' . $e->getMessage(),
            ], 500);
        }
    }
}