@extends('layouts.admin')

@section('admin-content')
<div class="max-w-lg mx-auto mt-10 bg-black/90 border-2 border-green-400 rounded-xl p-8">
    <h2 class="text-2xl font-bold text-green-400 mb-6">Meu Perfil</h2>
    <div class="mb-6">
        <p class="font-bold text-green-200"><span>Nome: </span> {{ $user->name }}</p>
        <p class="font-bold text-green-200"><span>Email: </span> {{ $user->email }}</p>
    </div>
    <hr class="my-6 border-green-700">

    <h3 class="text-xl font-bold text-green-300 mb-4">Alterar Senha</h3>
    @if(session('success'))
        <div class="bg-green-800 text-green-100 rounded p-3 mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-800 text-red-100 rounded p-3 mb-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    <form method="POST" action="{{ route('admin.profile.password') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-green-200 font-bold">Senha atual</label>
            <input type="password" name="current_password" required class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
        </div>
        <div class="mb-4">
            <label class="block text-green-200 font-bold">Nova senha</label>
            <input type="password" name="new_password" required class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
        </div>
        <div class="mb-4">
            <label class="block text-green-200 font-bold">Confirmar nova senha</label>
            <input type="password" name="new_password_confirmation" required class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
        </div>
        <button type="submit" class="bg-green-400 hover:bg-green-600 text-black font-bold rounded px-8 py-2 transition">Salvar nova senha</button>
    </form>
    <hr class="my-6 border-green-700">

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold rounded px-8 py-2 transition">
            Sair (Logout)
        </button>
    </form>
</div>
@endsection