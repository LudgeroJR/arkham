@extends('layouts.app')
@section('content')
<div class="flex flex-col items-center mt-64">
    <div class="bg-black/90 border-4 border-green-400 rounded-2xl shadow-2xl p-8 w-full max-w-md flex flex-col items-center">
        <h2 class="text-3xl font-bold text-green-400 mb-6 tracking-wider title">Painel ARKHAM</h2>
        <form method="POST" action="{{ route('login') }}" class="w-full flex flex-col gap-4">
            @csrf

            <input 
                id="email" type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required autofocus 
                placeholder="Email"
                class="px-4 py-2 rounded border border-green-400 focus:outline-none focus:ring-2 focus:ring-green-400 w-full bg-gray-900 text-green-300 placeholder-green-700"
            >

            <input 
                id="password" type="password" 
                name="password" 
                required 
                placeholder="Senha"
                class="px-4 py-2 rounded border border-green-400 focus:outline-none focus:ring-2 focus:ring-green-400 w-full bg-gray-900 text-green-300 placeholder-green-700"
            >

            @error('email')
                <div class="text-red-400 text-sm">{{ $message }}</div>
            @enderror
            @error('password')
                <div class="text-red-400 text-sm">{{ $message }}</div>
            @enderror

            <button 
                type="submit" 
                class="bg-green-400 hover:bg-green-600 text-black font-bold rounded py-2 mt-2 transition"
            >
                Entrar
            </button>
        </form>
    </div>
</div>
@endsection