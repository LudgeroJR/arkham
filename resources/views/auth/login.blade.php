@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#F8EE9A] via-[#EDC416] to-[#EA7514]">
    <div class="bg-white/95 rounded-2xl shadow-2xl p-8 w-full max-w-md flex flex-col items-center border-4 border-[#B81F1C]">
        <h2 class="text-3xl font-bold text-[#B81F1C] mb-6 tracking-wider">Admin Login</h2>
        <form method="POST" action="{{ route('login') }}" class="w-full flex flex-col gap-4">
            @csrf

            <input 
                id="email" type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required autofocus 
                placeholder="Email"
                class="px-4 py-2 rounded border border-[#EDC416] focus:outline-none focus:ring-2 focus:ring-[#EDC416] w-full"
            >

            <input 
                id="password" type="password" 
                name="password" 
                required 
                placeholder="Senha"
                class="px-4 py-2 rounded border border-[#EDC416] focus:outline-none focus:ring-2 focus:ring-[#EDC416] w-full"
            >

            @error('email')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            @error('password')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror

            <button 
                type="submit" 
                class="bg-[#B81F1C] hover:bg-[#C6241D] text-[#F8EE9A] font-bold rounded py-2 mt-2 transition"
            >
                Entrar
            </button>
        </form>
    </div>
</div>
@endsection