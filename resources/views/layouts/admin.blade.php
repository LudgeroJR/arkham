@extends('layouts.app')

@section('content')
<div class="flex h-full max-h-[80vh] bg-gradient-to-br from-[#0F191A] via-[#0B2C21] to-[#0A4327] max-w-[95rem] mx-auto w-full">
    <!-- Sidebar -->
    <aside class="w-64 bg-black/90 border-r-4 border-green-400 flex flex-col py-8 px-4 h-full">
        <h2 class="text-2xl font-bold text-green-400 mb-8 text-center tracking-wider">Cadastros</h2>
        <nav class="flex flex-col gap-2">
            {{-- <a href="#" class="flex items-center px-4 py-2 rounded text-green-200 hover:bg-green-400 hover:text-black font-semibold transition">
                <span class="material-icons mr-2">person</span> Usuários
            </a> --}}
            <a href="{{ route('admin.members') }}" class="flex items-center px-4 py-2 rounded text-green-200 hover:bg-green-400 hover:text-black font-semibold transition">
                <span class="material-icons mr-2">group</span> Membros
            </a>
            <!-- Psoul Menu -->
            <div x-data="{ open: false }" class="flex flex-col">
                <button @click="open = !open" class="flex items-center px-4 py-2 rounded text-green-200 hover:bg-green-400 hover:text-black font-semibold transition focus:outline-none w-full justify-between">
                    <span class="flex items-center">
                        <span class="material-icons mr-2">extension</span> Psoul
                    </span>
                    <svg :class="{'rotate-90': open}" class="w-4 h-4 ml-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <div x-show="open" class="ml-6 mt-2 flex flex-col gap-1" x-cloak>
                    <a href="{{ route('admin.psoul.pokedex') }}" class="px-3 py-1 rounded text-green-100 hover:bg-green-400 hover:text-black transition">Pokédex</a>
                    <a href="{{ route('admin.psoul.items') }}" class="px-3 py-1 rounded text-green-100 hover:bg-green-400 hover:text-black transition">Itens</a>
                    <a href="#" class="px-3 py-1 rounded text-green-100 hover:bg-green-400 hover:text-black transition">Quests</a>
                    <a href="#" class="px-3 py-1 rounded text-green-100 hover:bg-green-400 hover:text-black transition">Ranges</a>
                    <a href="#" class="px-3 py-1 rounded text-green-100 hover:bg-green-400 hover:text-black transition">Skills</a>
                    <a href="#" class="px-3 py-1 rounded text-green-100 hover:bg-green-400 hover:text-black transition">Types</a>
                    <a href="#" class="px-3 py-1 rounded text-green-100 hover:bg-green-400 hover:text-black transition">NPCs</a>
                </div>
            </div>
        </nav>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center justify-center p-4 h-full overflow-auto">
        @yield('admin-content')
    </main>
</div>
<!-- Alpine.js para submenu -->
<script src="//unpkg.com/alpinejs" defer></script>
<!-- Material Icons CDN (opcional, para ícones) -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection
