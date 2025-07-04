@extends('layouts.app')
@section('content')
    @include('partials.psoul-menu')

    <div 
        x-data="{
            search: ''
        }"
        class="bg-white/90 rounded-2xl shadow-2xl p-6 max-w-5xl mx-auto w-full min-h-[360px] flex flex-col items-center"
    >
        <h2 class="text-3xl font-bold text-[#B81F1C] mb-6 tracking-wide drop-shadow">Quests</h2>

        <!-- Campo de pesquisa -->
        <input 
            type="text"
            x-model="search"
            placeholder="Pesquisar quest..."
            class="mb-4 px-4 py-2 rounded border border-[#EDC416] focus:outline-none focus:ring-2 focus:ring-[#EDC416] w-full max-w-md"
        >

        <div 
            class="flex flex-col gap-4 w-full"
            style="max-height: 60vh; overflow-y: auto; overflow-x: hidden;"
        >
            @foreach($quests as $quest)
                <div 
                    class="bg-gradient-to-br from-[#F8EE9A] via-[#EDC416] to-[#EA7514] rounded-xl shadow-lg p-5 flex flex-col md:flex-row md:items-center gap-4 border-l-8 border-[#B81F1C] hover:scale-[1.01] transition"
                    x-show="!search || '{{ mb_strtolower($quest->name) }}'.includes(search.toLowerCase())"
                >
                    <div class="flex-1 min-w-0">
                        <div class="text-xl font-extrabold text-[#B81F1C] mb-1 tracking-wide" style="font-family: 'Bebas Neue', Oswald, Arial, sans-serif;">
                            {{ $quest->name }}
                        </div>
                        <div class="mb-1 text-[#680F0F]"><strong>Requisitos:</strong> {{ $quest->requirements ?? 'Nenhum' }}</div>
                        <div class="flex flex-wrap gap-2 items-center text-[#C6241D] font-bold">
                            <span class="text-[#B81F1C]">Recompensas:</span>
                            @forelse($quest->rewards as $reward)
                                <span class="bg-white/90 rounded px-2 py-1 border border-[#EDC416] text-[#680F0F]">{{ $reward->name }}</span>
                            @empty
                                <span class="text-gray-500">Nenhuma</span>
                            @endforelse
                        </div>
                    </div>
                    @if($quest->link)
                        <div>
                            <a href="{{ $quest->link }}" target="_blank" class="inline-block px-4 py-2 rounded bg-[#B81F1C] text-[#F8EE9A] font-bold shadow hover:bg-[#C6241D] transition">Ver Detalhes</a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection