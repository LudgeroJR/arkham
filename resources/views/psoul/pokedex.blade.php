@extends('layouts.app')

@section('content')
    @include('partials.psoul-menu')

    <div 
        x-data="{
            showModal: false, 
            pokemon: null,
            openModal(poke) {
                fetch(`/psoul/pokedex/${poke.id}/json`)
                    .then(res => res.json())
                    .then(data => { this.pokemon = data; this.showModal = true; });
            },
            closeModal() { this.showModal = false; this.pokemon = null; }
        }"
        @keydown.escape.window="closeModal()"
        @open-poke.window="openModal($event.detail)"
        class="bg-white/90 rounded-2xl shadow-2xl p-6 max-w-5xl mx-auto w-full min-h-[360px] flex flex-col items-center"
    >
        <h2 class="text-3xl font-bold text-[#B81F1C] mb-6 tracking-wide drop-shadow" style="font-family: 'Bebas Neue', Oswald, Arial, sans-serif;">
            Pokedex
        </h2>
        <div class="w-full max-h-[60vh] overflow-y-auto grid md:grid-cols-4 sm:grid-cols-2 gap-6">
            @foreach($pokemons_by_dex as $dex => $pokemons)
                <div 
                    x-data="{
                        idx: 0, 
                        total: {{ $pokemons->count() }}, 
                        pokes: {{ $pokemons->toJson() }},
                        activePoke() { return this.pokes[this.idx]; }
                    }"
                    class="bg-gradient-to-br from-[#F8EE9A] via-[#EDC416] to-[#EA7514] rounded-xl shadow-lg flex flex-col items-center p-4 relative cursor-pointer"
                >
                    <div class="flex items-center justify-center w-full mb-2 relative">
                        <template x-if="total > 1">
                            <button
                                @click="idx = idx === 0 ? total-1 : idx-1"
                                class="absolute left-0 top-1/2 -translate-y-1/2 p-1 rounded-full bg-[#B81F1C] bg-opacity-70 hover:bg-[#C6241D] transition z-10"
                                aria-label="Anterior"
                            >
                                <svg class="w-5 h-5 text-[#F8EE9A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </template>
                        <div class="flex flex-col items-center w-full" @click="$dispatch('open-poke', activePoke())">
                            <div class="w-20 h-20 rounded-full bg-[#fff8] flex items-center justify-center shadow-lg border-4 border-[#B81F1C] mb-2 mx-auto">
                                <img :src="'{{ asset('images/jogos/psoul/pokemonsthumb') }}/' + activePoke().thumb" :alt="activePoke().name" class="w-16 h-16 object-contain drop-shadow-xl">
                            </div>
                            <div class="text-center text-[#C6241D] font-bold text-base">
                                #<span x-text="activePoke().dex"></span> - <span x-text="activePoke().name"></span>
                            </div>
                        </div>
                        <template x-if="total > 1">
                            <button
                                @click="idx = idx === total-1 ? 0 : idx+1"
                                class="absolute right-0 top-1/2 -translate-y-1/2 p-1 rounded-full bg-[#B81F1C] bg-opacity-70 hover:bg-[#C6241D] transition z-10"
                                aria-label="Próximo"
                            >
                                <svg class="w-5 h-5 text-[#F8EE9A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </template>
                    </div>
                    <div class="flex justify-center items-center gap-1 mt-1" x-show="total > 1">
                        <template x-for="i in total" :key="i">
                            <span :class="{'bg-[#B81F1C]': idx === i-1, 'bg-[#F6E160]': idx !== i-1 }"
                                  class="w-2 h-2 rounded-full mx-0.5 inline-block"></span>
                        </template>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- MODAL POKÉMON --}}
        <div 
            x-show="showModal && pokemon" 
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80"
            style="backdrop-filter: blur(2px);"
        >
            <div 
                @click.away="closeModal()" 
                class="bg-gradient-to-br from-[#F8EE9A] via-[#EDC416] to-[#EA7514] rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4 relative"
            >
                <button @click="closeModal()" class="absolute top-4 right-4 text-2xl text-[#B81F1C] hover:text-[#C6241D] font-bold">&times;</button>
                <div class="flex flex-col md:flex-row gap-6">
                    {{-- Geral --}}
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-32 h-32 rounded-full bg-white flex items-center justify-center shadow-lg border-4 border-[#B81F1C] mb-2 mx-auto">
                            <img :src="'{{ asset('images/jogos/psoul/pokemonsthumb') }}/' + pokemon.thumb" :alt="pokemon.name" class="w-28 h-28 object-contain drop-shadow-xl">
                        </div>
                        <div class="text-xl font-extrabold text-[#B81F1C] mb-2">
                            #<span x-text="pokemon.dex"></span> - <span x-text="pokemon.name"></span>
                        </div>
                        <div class="text-[#680F0F] mb-2 text-center" x-text="pokemon.description"></div>
                        <div class="flex gap-2 justify-center items-center">
                            <template x-if="pokemon.primary_type">
                                <span class="inline-flex items-center px-2 py-1 rounded bg-[#B81F1C] text-[#F8EE9A] font-bold text-xs mr-1">
                                    <img :src="'{{ asset('images/types') }}/' + pokemon.primary_type + '.png'" alt="" class="w-5 h-5 mr-1"> <span x-text="pokemon.primary_type"></span>
                                </span>
                            </template>
                            <template x-if="pokemon.secondary_type">
                                <span class="inline-flex items-center px-2 py-1 rounded bg-[#680F0F] text-[#F6E160] font-bold text-xs">
                                    <img :src="'{{ asset('images/types') }}/' + pokemon.secondary_type + '.png'" alt="" class="w-5 h-5 mr-1"> <span x-text="pokemon.secondary_type"></span>
                                </span>
                            </template>
                        </div>
                    </div>
                    {{-- Abilities, Moveset, Eggmove, Movetutor, Loot --}}
                    <div class="flex-1 flex flex-col gap-3">
                        
                        {{-- Moveset --}}
                        <div class="bg-white/90 rounded-xl shadow p-3">
                            <div class="font-bold text-[#B81F1C] mb-1">Moveset</div>
                            <template x-if="pokemon.moveset && pokemon.moveset.length">
                                <ul>
                                    <template x-for="move in pokemon.moveset.sort((a,b) => a.position - b.position)" :key="move.id">
                                        <li class="mb-2 flex flex-col relative">
                                            <div class="flex gap-2 items-center">
                                                <span class="text-xs text-[#EDC416] font-bold">Posição:</span>
                                                <span class="text-sm text-[#B81F1C] font-bold" x-text="move.position"></span>
                                                <span class="px-2 py-0.5 rounded text-xs font-bold"
                                                    :class="{
                                                        'bg-[#EDC416] text-[#B81F1C]': move.category === 'Physical',
                                                        'bg-[#F6E160] text-[#680F0F]': move.category === 'Special',
                                                        'bg-[#EA7514] text-white': move.category === 'Status'
                                                    }"
                                                    x-text="move.category"></span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-[#B81F1C] text-[#F8EE9A] text-xs font-bold">
                                                    <img :src="'{{ asset('images/types') }}/' + move.type + '.png'" alt="" class="w-4 h-4 mr-1"> <span x-text="move.type"></span>
                                                </span>
                                                <span class="text-[#C6241D] font-semibold text-xs ml-2" x-text="move.name"></span>
                                                <span class="text-xs ml-4" x-text="'Poder: ' + (move.power || '-')"></span>
                                                <span class="text-xs ml-2" x-text="'Nível: ' + (move.level || '-')"></span>
                                            </div>
                                            <div class="flex gap-1 absolute top-0 right-0">
                                                <template x-for="range in move.ranges || []" :key="range">
                                                    <span class="inline-block bg-[#F8EE9A] text-[#C6241D] rounded px-2 py-0.5 text-xs font-bold border border-[#B81F1C]">{{' '}}<span x-text="range"></span>{{' '}}</span>
                                                </template>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </template>
                            <template x-if="!pokemon.moveset || !pokemon.moveset.length">
                                <span class="text-gray-400">Nenhum move cadastrado.</span>
                            </template>
                        </div>
                        {{-- Abilities --}}
                        <div class="bg-white/90 rounded-xl shadow p-3">
                            <div class="font-bold text-[#B81F1C] mb-1">Abilities</div>
                            <template x-if="pokemon.abilities && pokemon.abilities.length">
                                <ul>
                                    <template x-for="ability in pokemon.abilities" :key="ability.id">
                                        <li class="mb-1">
                                            <span class="font-semibold text-[#C6241D]" x-text="ability.name"></span>:
                                            <span class="text-[#680F0F]" x-text="ability.description"></span>
                                        </li>
                                    </template>
                                </ul>
                            </template>
                            <template x-if="!pokemon.abilities || !pokemon.abilities.length">
                                <span class="text-gray-400">Nenhuma ability cadastrada.</span>
                            </template>
                        </div>
                        {{-- Eggmoves --}}
                        <div class="bg-white/90 rounded-xl shadow p-3">
                            <div class="font-bold text-[#B81F1C] mb-1">Egg Moves</div>
                            <template x-if="pokemon.egg_moves && pokemon.egg_moves.length">
                                <ul>
                                    <template x-for="move in pokemon.egg_moves" :key="move.id">
                                        <li class="mb-2 flex flex-col">
                                            <div class="flex gap-2 items-center">
                                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-[#B81F1C] text-[#F8EE9A]">
                                                    <img :src="'{{ asset('images/types') }}/' + move.type + '.png'" alt="" class="w-4 h-4 mr-1"> <span x-text="move.type"></span>
                                                </span>
                                                <span class="text-[#C6241D] font-semibold text-xs ml-1" x-text="move.name"></span>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </template>
                            <template x-if="!pokemon.egg_moves || !pokemon.egg_moves.length">
                                <span class="text-gray-400">Nenhum egg move cadastrado.</span>
                            </template>
                        </div>
                        {{-- Move Tutor --}}
                        <div class="bg-white/90 rounded-xl shadow p-3">
                            <div class="font-bold text-[#B81F1C] mb-1">Move Tutor</div>
                            <template x-if="pokemon.move_tutors && pokemon.move_tutors.length">
                                <ul>
                                    <template x-for="move in pokemon.move_tutors" :key="move.id">
                                        <li class="mb-2 flex flex-col">
                                            <div class="flex gap-2 items-center">
                                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-[#B81F1C] text-[#F8EE9A]">
                                                    <img :src="'{{ asset('images/types') }}/' + move.type + '.png'" alt="" class="w-4 h-4 mr-1"> <span x-text="move.type"></span>
                                                </span>
                                                <span class="text-[#C6241D] font-semibold text-xs ml-1" x-text="move.name"></span>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </template>
                            <template x-if="!pokemon.move_tutors || !pokemon.move_tutors.length">
                                <span class="text-gray-400">Nenhum move tutor cadastrado.</span>
                            </template>
                        </div>
                        {{-- Loot --}}
                        <div class="bg-white/90 rounded-xl shadow p-3">
                            <div class="font-bold text-[#B81F1C] mb-1">Loot</div>
                            <template x-if="pokemon.loot && pokemon.loot.length">
                                <ul>
                                    <template x-for="item in pokemon.loot" :key="item.id">
                                        <li class="mb-1 flex gap-2 items-center">
                                            <span class="font-semibold text-[#C6241D]" x-text="item.name"></span>
                                            <span class="text-xs text-[#EA7514]">(<span x-text="item.amount_min"></span> - <span x-text="item.amount_max"></span>)</span>
                                        </li>
                                    </template>
                                </ul>
                            </template>
                            <template x-if="!pokemon.loot || !pokemon.loot.length">
                                <span class="text-gray-400">Nenhum loot cadastrado.</span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection