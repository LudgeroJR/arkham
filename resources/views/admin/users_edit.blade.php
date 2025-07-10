@extends('layouts.admin')

@section('admin-content')
<div class="max-w-lg mx-auto mt-10 bg-black/90 border-2 border-green-400 rounded-xl p-8">
    <h2 class="text-2xl font-bold text-green-400 mb-6">Editar Usuário</h2>

    @if($errors->any())
        <div class="bg-red-800 text-red-100 rounded p-3 mb-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        <div class="mb-4">
            <label class="block text-green-200 font-bold">Nome</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
        </div>
        <div class="mb-4">
            <label class="block text-green-200 font-bold">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
        </div>
        <div class="mb-4">
            <label class="block text-green-200 font-bold">Nova senha (deixe em branco para não alterar)</label>
            <input type="password" name="password" class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
        </div>
        <div class="mb-4">
            <label class="block text-green-200 font-bold">Confirmar nova senha</label>
            <input type="password" name="password_confirmation" class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
        </div>
        <div class="flex gap-4">
            <button type="submit" class="bg-green-400 hover:bg-green-600 text-black font-bold rounded px-8 py-2 transition">Salvar</button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white rounded px-8 py-2 transition">Cancelar</a>
        </div>
    </form>
</div>
@endsection