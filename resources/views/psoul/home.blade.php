@extends('layouts.app')

@section('content')
    {{-- Menu Psoul fixo no topo da área de conteúdo --}}
    <nav class="w-full max-w-4xl mx-auto bg-gradient-to-r from-[#B81F1C] via-[#C6241D] to-[#EA7514] rounded-xl shadow-lg mb-6 sticky top-0 z-20">
        <div class="flex items-center gap-2 px-6 py-3">
            <img src="{{ asset('images/jogos/psoul/logoAnimadaPsoul.png') }}" alt="Psoul" class="h-10 drop-shadow-xl mr-3">
            <ul class="flex gap-6 text-lg font-bold items-center w-full">
                <li><a href="{{ route('psoul.home') }}" class="text-[#F8EE9A] hover:text-[#EDC416] transition">Home</a></li>
                <li><a href="{{ route('psoul.pokedex') }}" class="text-[#F8EE9A] hover:text-[#EDC416] transition">Pokedex</a></li>
                <li><a href="{{ route('psoul.itens') }}" class="text-[#F8EE9A] hover:text-[#EDC416] transition">Itens</a></li>
                <li><a href="{{ route('psoul.skills') }}" class="text-[#F8EE9A] hover:text-[#EDC416] transition">Skills</a></li>
                <li><a href="{{ route('psoul.quests') }}" class="text-[#F8EE9A] hover:text-[#EDC416] transition">Quests</a></li>
            </ul>
        </div>
    </nav>

    {{-- Conteúdo da Home Psoul --}}
    <div class="bg-white/90 rounded-2xl shadow-2xl p-8 max-w-4xl mx-auto min-h-[440px]">
        <h2 class="text-3xl font-bold text-[#B81F1C] mb-4">Bem-vindo ao Psoul!</h2>
        <p class="text-lg text-gray-800">
            Aqui você encontra informações sobre pokémons, itens, skills, quests e tudo do universo Psoul. Use o menu acima para navegar pelas seções.
        </p>
    </div>
@endsection