<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends Controller
{
    // Lista todos os Types e mostra a tela principal
    public function index()
    {
        $types = Type::orderBy('name')->get()->map(function ($t) {
            return [
                'id' => $t->id,
                'name' => $t->name,
            ];
        });
        return view('admin.psoul.types', compact('types'));
    }

    // Cadastra um novo Type (AJAX)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:types,name',
        ]);

        $type = Type::create(['name' => $data['name']]);

        return response()->json([
            'success' => true,
            'type' => [
                'id' => $type->id,
                'name' => $type->name,
            ]
        ]);
    }

    // Atualiza o nome do Type (edição inline, AJAX)
    public function update(Request $request, Type $type)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:types,name,' . $type->id,
        ]);
        $type->update(['name' => $data['name']]);

        return response()->json([
            'success' => true,
            'message' => 'Type atualizado com sucesso!'
        ]);
    }

    // Exclui um Type (AJAX)
    public function destroy(Type $type)
    {
        $type->delete();

        return response()->json([
            'success' => true,
            'message' => 'Type excluído com sucesso!'
        ]);
    }
}