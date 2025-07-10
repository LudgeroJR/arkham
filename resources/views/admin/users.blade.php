@extends('layouts.admin')

@section('admin-content')
<div class="max-w-6xl mx-auto mt-10 bg-black/90 border-2 border-green-400 rounded-xl p-8">
    <h2 class="text-2xl font-bold text-green-400 mb-6">Usuários do Admin</h2>

    @if(session('success'))
        <div class="bg-green-800 text-green-100 rounded p-3 mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-800 text-red-100 rounded p-3 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <h3 class="text-xl font-bold text-green-300 mb-4">Adicionar Novo Usuário</h3>
    <form method="POST" action="{{ route('admin.users.store') }}" class="mb-8">
        @csrf
        <div class="mb-4">
            <label class="block text-green-200 font-bold">Nome</label>
            <input type="text" name="name" required class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
        </div>
        <div class="mb-4">
            <label class="block text-green-200 font-bold">Email</label>
            <input type="email" name="email" required class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
        </div>
        <div class="mb-4">
            <label class="block text-green-200 font-bold">Senha</label>
            <input type="password" name="password" required class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
        </div>
        <div class="mb-4">
            <label class="block text-green-200 font-bold">Confirmar senha</label>
            <input type="password" name="password_confirmation" required class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
        </div>
        <button type="submit" class="bg-green-400 hover:bg-green-600 text-black font-bold rounded px-8 py-2 transition">Adicionar Usuário</button>
    </form>

    <h3 class="text-lg font-bold text-green-300 mb-2">Usuários cadastrados</h3>
    <table class="w-full bg-black/80 border border-green-400 text-green-200 rounded-xl overflow-hidden">
        <thead>
            <tr>
                <th class="py-2 px-4 text-left">Nome</th>
                <th class="py-2 px-4 text-left">Email</th>
                <th class="py-2 px-4 text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr class="border-t border-green-700">
                    <td class="py-2 px-4">{{ $user->name }}</td>
                    <td class="py-2 px-4">{{ $user->email }}</td>
                    <td class="py-2 px-4 text-center">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-green-400 hover:text-green-600 font-bold px-2">Editar</a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 font-bold px-2">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="py-4 text-center text-green-400">Nenhum usuário cadastrado ainda.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection