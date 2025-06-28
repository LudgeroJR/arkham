@extends('layouts.app')

@section('content')
    @include('partials.psoul-menu')

    <div class="bg-white/90 rounded-2xl shadow-2xl p-6 max-w-5xl mx-auto w-full min-h-[360px] flex flex-col items-center">
        <h2 class="text-3xl font-bold text-[#B81F1C] mb-6 tracking-wide drop-shadow" style="font-family: 'Bebas Neue', Oswald, Arial, sans-serif;">
            Pokedex
        </h2>
        <div class="w-full max-h-[60vh] overflow-y-auto grid md:grid-cols-4 sm:grid-cols-2 gap-6">
            @foreach($pokemonsByDex as $dex => $pokemons)
                <div 
                    x-data="{ idx: 0, total: {{ $pokemons->count() }} }"
                    class="bg-gradient-to-br from-[#F8EE9A] via-[#EDC416] to-[#EA7514] rounded-xl shadow-lg flex flex-col items-center p-4 relative"
                >
                    {{-- Thumb + setas se houver mais de uma variante --}}
                    <div class="flex items-center justify-center w-full mb-2 relative">
                        {{-- Seta esquerda --}}
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
                        {{-- Thumb do pokemon --}}
                        <template x-for="(poke, i) in {{ $pokemons->toJson() }}" :key="poke.id">
                            <div x-show="idx === i" class="flex flex-col items-center w-full">
                                <div class="w-20 h-20 rounded-full bg-[#fff8] flex items-center justify-center shadow-lg border-4 border-[#B81F1C] mb-2 mx-auto">
                                    <img :src="'{{ asset('images/jogos/psoul/pokemonsthumb') }}/' + poke.thumb" :alt="poke.name" class="w-16 h-16 object-contain drop-shadow-xl">
                                </div>
                                <div class="text-center text-[#C6241D] font-bold text-base">
                                    #<span x-text="poke.dex"></span> - <span x-text="poke.name"></span>
                                </div>
                            </div>
                        </template>
                        {{-- Seta direita --}}
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

                    {{-- Bolinhas de página se houver mais de uma variante --}}
                    <div class="flex justify-center items-center gap-1 mt-1" x-show="total > 1">
                        <template x-for="i in total" :key="i">
                            <span :class="{'bg-[#B81F1C]': idx === i-1, 'bg-[#F6E160]': idx !== i-1 }"
                                  class="w-2 h-2 rounded-full mx-0.5 inline-block"></span>
                        </template>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-xs text-gray-600 mt-4">Use as setas nos cards para alternar formas/variantes de um mesmo Pokémon.</div>
    </div>
@endsection