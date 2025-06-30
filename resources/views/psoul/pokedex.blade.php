@extends('layouts.app')

@section('content')
    @include('partials.psoul-menu')

    <div 
        x-data="{
            showModal: false, 
            pokemon: null,
            error: null,
            search: '',
            openModal(poke) {
                this.error = null;
                fetch(`/psoul/pokedex/${poke.id}/json`)
                    .then(res => {
                        if (!res.ok) throw new Error('Erro ao buscar dados');
                        return res.json();
                    })
                    .then(data => { this.pokemon = data; this.showModal = true; })
                    .catch(e => { 
                        this.error = 'Erro ao carregar dados do Pokémon';
                        console.error(e);
                    });
            },
            closeModal() { this.showModal = false; this.pokemon = null; this.error = null; }
        }"
        @keydown.escape.window="closeModal()"
        @open-poke.window="openModal($event.detail)"
        class="bg-white/90 rounded-2xl shadow-2xl p-6 max-w-5xl mx-auto w-full min-h-[360px] flex flex-col items-center"
    >
        <h2 class="text-3xl font-bold text-[#B81F1C] mb-6 tracking-wide drop-shadow" style="font-family: 'Bebas Neue', Oswald, Arial, sans-serif;">
            Pokedex
        </h2>
        <!-- Campo de pesquisa -->
            <input 
            type="text"
            x-model="search"
            placeholder="Pesquisar por nome..."
            class="mb-4 px-4 py-2 rounded border border-[#EDC416] focus:outline-none focus:ring-2 focus:ring-[#EDC416] w-full max-w-md"
        >
        <div class="w-full max-h-[60vh] overflow-y-auto grid md:grid-cols-4 sm:grid-cols-2 gap-6">
            @foreach($pokemons_by_dex as $dex => $pokemons)
                <div 
                    x-data="{
                        idx: 0, 
                        total: {{ $pokemons->count() }}, 
                        pokes: {{ $pokemons->toJson() }},
                        activePoke() { return this.pokes[this.idx]; }
                    }"
                    x-show="!search || pokes[0].name.toLowerCase().includes(search.toLowerCase())"
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
            x-show="showModal && (pokemon || error)" 
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80"
            style="backdrop-filter: blur(2px);"
        >
            <div 
                @click.away="closeModal()" 
                class="bg-gradient-to-br from-[#F8EE9A] via-[#EDC416] to-[#EA7514] rounded-2xl shadow-2xl p-8 max-w-[95rem] w-full mx-4 relative max-h-[90vh] overflow-y-auto"
            >
                <button @click="closeModal()" class="absolute top-4 right-4 text-2xl text-[#B81F1C] hover:text-[#C6241D] font-bold">&times;</button>
                <template x-if="error">
                    <div class="text-red-700 font-bold text-center py-8" x-text="error"></div>
                </template>
                <template x-if="pokemon && !error">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        {{-- Coluna 1: Geral + Abilities --}}
                        <div class="flex flex-col items-center gap-3">
                            {{-- Geral --}}
                            <div class="flex flex-col items-center w-full">
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
                                            <img :src="'{{ asset('images/jogos/psoul/types') }}/' + pokemon.primary_type + '.png'" alt="" class="w-5 h-5 mr-1"> <span x-text="pokemon.primary_type"></span>
                                        </span>
                                    </template>
                                    <template x-if="pokemon.secondary_type">
                                        <span class="inline-flex items-center px-2 py-1 rounded bg-[#680F0F] text-[#F6E160] font-bold text-xs">
                                            <img :src="'{{ asset('images/jogos/psoul/types') }}/' + pokemon.secondary_type + '.png'" alt="" class="w-5 h-5 mr-1"> <span x-text="pokemon.secondary_type"></span>
                                        </span>
                                    </template>
                                </div>
                            </div>
                            {{-- Abilities --}}
                            <div class="bg-white/90 rounded-xl shadow p-3 w-full">
                                <div class="font-extrabold text-[#B81F1C] mb-1">Abilidades:</div>
                                <template x-if="pokemon.abilities && pokemon.abilities.length">
                                    <ul>
                                        <template x-for="ability in pokemon.abilities" :key="ability.name">
                                            <li class="mb-1">
                                                <span 
                                                    :class="ability.hidden ? 'font-bold text-[#C6241D]' : 'font-medium text-[#C6241D]'"
                                                    x-text="ability.name"
                                                ></span>:
                                                <span class="text-[#680F0F]" x-text="ability.description"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                                <template x-if="!pokemon.abilities || !pokemon.abilities.length">
                                    <span class="text-gray-400">Nenhuma ability cadastrada.</span>
                                </template>
                            </div>
                        </div>
                        {{-- Coluna 2: Moveset --}}
                        <div class="flex flex-col gap-3">
                            <div class="bg-white/90 rounded-xl shadow p-3 flex-1">
                                <div class="font-extrabold text-[#B81F1C] mb-1">Moveset</div>
                                <template x-if="Array.isArray(pokemon.moveset) && pokemon.moveset.length">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-center border-separate text-sm border-spacing-y-1">
                                            <thead>
                                                <tr class="bg-[#F6E160] text-[#B81F1C] font-bold text-xs">
                                                    <th class="px-2 py-1 rounded">Type</th>
                                                    <th class="px-2 py-1 rounded">Cat</th>
                                                    <th class="px-2 py-1 rounded">Pwr</th>
                                                    <th class="px-2 py-1 rounded">Lv</th>
                                                    <th class="px-2 py-1 rounded">Area</th>
                                                </tr>
                                            </thead>
                                            <template x-for="move in pokemon.moveset" :key="move.position + '-' + move.name">
                                                <tbody>
                                                    <tr class="bg-white/70">
                                                        <td colspan="5" class="align-middle text-sm font-bold text-[#C6241D]">
                                                            [<span x-text="move.position !== undefined ? move.position : '-'"></span>]&nbsp;
                                                            <span x-text="move.name !== undefined ? move.name : '-'"></span>
                                                        </td>
                                                    </tr>
                                                    <tr class="bg-white/70">
                                                        <td class="align-middle">
                                                            <template x-if="move.type">
                                                                <img :src="'{{ asset('images/jogos/psoul/types') }}/' + move.type + '.png'" 
                                                                     :alt="move.type" 
                                                                     class="w-6 h-6 mx-auto" 
                                                                     :title="move.type">
                                                            </template>
                                                            <template x-if="!move.type">
                                                                <span class="text-gray-400">-</span>
                                                            </template>
                                                        </td>
                                                        <td class="align-middle">
                                                            <template x-if="move.category === 'Physical'">
                                                                <span title="Physical" class="text-yellow-400 text-lg">★</span>
                                                            </template>
                                                            <template x-if="move.category === 'Special'">
                                                                <span title="Special" class="text-gray-700 text-lg">☯️</span>
                                                            </template>
                                                            <template x-if="move.category === 'Status'">
                                                                <span title="Status" class="text-blue-600 text-lg">◎</span>
                                                            </template>
                                                            <template x-if="!move.category">
                                                                <span class="text-gray-400">-</span>
                                                            </template>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span x-text="(move.power !== undefined && move.power !== null) ? move.power : '-'"></span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span x-text="(move.level !== undefined && move.level !== null) ? move.level : '-'"></span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <template x-if="move.ranges && move.ranges.length && move.ranges.some(r => r.name)">
                                                                <span>
                                                                    <template x-for="range in move.ranges" :key="range.name + Math.random()">
                                                                        <span>
                                                                            <template x-if="range.name === 'Target'">
                                                                                <span title="Target" class="mx-1 text-lg">🎯</span>
                                                                            </template>
                                                                            <template x-if="range.name === 'Frontal'">
                                                                                <span title="Frontal" class="mx-1 text-lg">➡️</span>
                                                                            </template>
                                                                            <template x-if="range.name === 'Area'">
                                                                                <span title="Area" class="mx-1 text-lg">🌐</span>
                                                                            </template>
                                                                            <template x-if="range.name === 'Gap Closed'">
                                                                                <span title="Gap Closed" class="mx-1 text-lg">🏹</span>
                                                                            </template>
                                                                        </span>
                                                                    </template>
                                                                </span>
                                                            </template>
                                                            <template x-if="!move.ranges || !move.ranges.length || !move.ranges.some(r => r.name)">
                                                                <span class="text-gray-400">-</span>
                                                            </template>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </template>
                                        </table>
                                    </div>
                                </template>
                                <template x-if="!pokemon.moveset || !pokemon.moveset.length">
                                    <span class="text-gray-400">Nenhum move cadastrado.</span>
                                </template>
                            </div>
                        </div>
                        {{-- Coluna 3: Egg Moves + Move Tutors --}}
                        <div class="flex flex-col gap-3">
                            {{-- Egg Moves --}}
                            <div class="bg-white/90 rounded-xl shadow p-3 w-full">
                                <div class="font-extrabold text-[#B81F1C] mb-1">Egg Moves</div>
                                <template x-if="Array.isArray(pokemon.egg_moves) && pokemon.egg_moves.length">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-center border-separate text-sm border-spacing-y-1">
                                            <thead>
                                                <tr class="bg-[#F6E160] text-[#B81F1C] font-bold text-xs">
                                                    <th class="px-2 py-1 rounded">Name</th>
                                                    <th class="px-2 py-1 rounded">Type</th>
                                                    <th class="px-2 py-1 rounded">Cat</th>
                                                    <th class="px-2 py-1 rounded">Pwr</th>
                                                    <th class="px-2 py-1 rounded">Area</th>
                                                </tr>
                                            </thead>
                                            <template x-for="eggmove in pokemon.egg_moves" :key="eggmove.name + Math.random()">
                                                <tbody>
                                                    <tr class="bg-white/70">
                                                        <td class="align-middle text-sm font-bold text-[#C6241D]">
                                                            <span x-text="eggmove.name !== undefined ? eggmove.name : '-'"></span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <template x-if="eggmove.type">
                                                                <img :src="'{{ asset('images/jogos/psoul/types') }}/' + eggmove.type + '.png'" 
                                                                     :alt="eggmove.type" 
                                                                     class="w-6 h-6 mx-auto" 
                                                                     :title="eggmove.type">
                                                            </template>
                                                            <template x-if="!eggmove.type">
                                                                <span class="text-gray-400">-</span>
                                                            </template>
                                                        </td>
                                                        <td class="align-middle">
                                                            <template x-if="eggmove.category === 'Physical'">
                                                                <span title="Physical" class="text-yellow-400 text-2xl">★</span>
                                                            </template>
                                                            <template x-if="eggmove.category === 'Special'">
                                                                <span title="Special" class="text-gray-700 text-lg">☯️</span>
                                                            </template>
                                                            <template x-if="eggmove.category === 'Status'">
                                                                <span title="Status" class="text-blue-600 text-2xl">◎</span>
                                                            </template>
                                                            <template x-if="!eggmove.category">
                                                                <span class="text-gray-400">-</span>
                                                            </template>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span x-text="(eggmove.power !== undefined && eggmove.power !== null) ? eggmove.power : '-'"></span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <template x-if="eggmove.ranges && eggmove.ranges.length && eggmove.ranges.some(r => r.name)">
                                                                <span>
                                                                    <template x-for="range in eggmove.ranges" :key="range.name + Math.random()">
                                                                        <span>
                                                                            <template x-if="range.name === 'Target'">
                                                                                <span title="Target" class="mx-1 text-lg">🎯</span>
                                                                            </template>
                                                                            <template x-if="range.name === 'Frontal'">
                                                                                <span title="Frontal" class="mx-1 text-lg">➡️</span>
                                                                            </template>
                                                                            <template x-if="range.name === 'Area'">
                                                                                <span title="Area" class="mx-1 text-lg">🌐</span>
                                                                            </template>
                                                                            <template x-if="range.name === 'Gap Closed'">
                                                                                <span title="Gap Closed" class="mx-1 text-lg">🏹</span>
                                                                            </template>
                                                                        </span>
                                                                    </template>
                                                                </span>
                                                            </template>
                                                            <template x-if="!eggmove.ranges || !eggmove.ranges.length || !eggmove.ranges.some(r => r.name)">
                                                                <span class="text-gray-400">-</span>
                                                            </template>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </template>
                                        </table>
                                    </div>
                                </template>
                                <template x-if="!pokemon.egg_moves || !pokemon.egg_moves.length">
                                    <span class="text-gray-400">Nenhum egg move cadastrado.</span>
                                </template>
                            </div>
                            {{-- Move Tutor --}}
                            <div class="bg-white/90 rounded-xl shadow p-3 w-full">
                                <div class="font-extrabold text-[#B81F1C] mb-1">Move Tutor</div>
                                <template x-if="Array.isArray(pokemon.move_tutors) && pokemon.move_tutors.length">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-center border-separate text-sm border-spacing-y-1">
                                            <thead>
                                                <tr class="bg-[#F6E160] text-[#B81F1C] font-bold text-xs">
                                                    <th class="px-2 py-1 rounded">Name</th>
                                                    <th class="px-2 py-1 rounded">Type</th>
                                                    <th class="px-2 py-1 rounded">Cat</th>
                                                    <th class="px-2 py-1 rounded">Pwr</th>
                                                    <th class="px-2 py-1 rounded">Area</th>
                                                </tr>
                                            </thead>
                                            <template x-for="movetutor in pokemon.move_tutors" :key="movetutor.name + Math.random()">
                                                <tbody>
                                                    <tr class="bg-white/70">
                                                        <td class="align-middle text-sm font-bold text-[#C6241D]">
                                                            <span x-text="movetutor.name !== undefined ? movetutor.name : '-'"></span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <template x-if="movetutor.type">
                                                                <img :src="'{{ asset('images/jogos/psoul/types') }}/' + movetutor.type + '.png'" 
                                                                     :alt="movetutor.type" 
                                                                     class="w-6 h-6 mx-auto" 
                                                                     :title="movetutor.type">
                                                            </template>
                                                            <template x-if="!movetutor.type">
                                                                <span class="text-gray-400">-</span>
                                                            </template>
                                                        </td>
                                                        <td class="align-middle">
                                                            <template x-if="movetutor.category === 'Physical'">
                                                                <span title="Physical" class="text-yellow-400 text-2xl">★</span>
                                                            </template>
                                                            <template x-if="movetutor.category === 'Special'">
                                                                <span title="Special" class="text-gray-700 text-lg">☯️</span>
                                                            </template>
                                                            <template x-if="movetutor.category === 'Status'">
                                                                <span title="Status" class="text-blue-600 text-2xl">◎</span>
                                                            </template>
                                                            <template x-if="!movetutor.category">
                                                                <span class="text-gray-400">-</span>
                                                            </template>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span x-text="(movetutor.power !== undefined && movetutor.power !== null) ? movetutor.power : '-'"></span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <template x-if="movetutor.ranges && movetutor.ranges.length && movetutor.ranges.some(r => r.name)">
                                                                <span>
                                                                    <template x-for="range in movetutor.ranges" :key="range.name + Math.random()">
                                                                        <span>
                                                                            <template x-if="range.name === 'Target'">
                                                                                <span title="Target" class="mx-1 text-lg">🎯</span>
                                                                            </template>
                                                                            <template x-if="range.name === 'Frontal'">
                                                                                <span title="Frontal" class="mx-1 text-lg">➡️</span>
                                                                            </template>
                                                                            <template x-if="range.name === 'Area'">
                                                                                <span title="Area" class="mx-1 text-lg">🌐</span>
                                                                            </template>
                                                                            <template x-if="range.name === 'Gap Closed'">
                                                                                <span title="Gap Closed" class="mx-1 text-lg">🏹</span>
                                                                            </template>
                                                                        </span>
                                                                    </template>
                                                                </span>
                                                            </template>
                                                            <template x-if="!movetutor.ranges || !movetutor.ranges.length || !movetutor.ranges.some(r => r.name)">
                                                                <span class="text-gray-400">-</span>
                                                            </template>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </template>
                                        </table>
                                    </div>
                                </template>
                                <template x-if="!pokemon.move_tutors || !pokemon.move_tutors.length">
                                    <span class="text-gray-400">Nenhum move tutor cadastrado.</span>
                                </template>
                            </div>
                        </div>
                        {{-- Coluna 4: Loot --}}
                        <div class="flex flex-col gap-3">
                            <div class="bg-white/90 rounded-xl shadow p-3 flex-1">
                                <div class="font-extrabold text-[#B81F1C] mb-1">Loot</div>
                                <template x-if="Array.isArray(pokemon.loot) && pokemon.loot.length">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-center border-separate border-spacing-y-2">
                                            <thead>
                                                <tr class="bg-[#F6E160] text-[#B81F1C] font-bold text-xs">
                                                    <th class="px-2 py-1 rounded">Name</th>
                                                    <th class="px-2 py-1 rounded">Min</th>
                                                    <th class="px-2 py-1 rounded">Max</th>
                                                </tr>
                                            </thead>
                                            <template x-for="item in pokemon.loot" :key="item.name + Math.random()">
                                                <tbody>
                                                    <tr class="bg-white/70">
                                                        <td class="align-middle text-sm font-bold text-[#C6241D]">
                                                            <span x-text="item.name !== undefined ? item.name : '-'"></span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span x-text="(item.amount_min !== undefined && item.amount_min !== null) ? item.amount_min : '-'"></span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span x-text="(item.amount_max !== undefined && item.amount_max !== null) ? item.amount_max : '-'"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </template>
                                        </table>
                                    </div>
                                </template>
                                <template x-if="!pokemon.loot || !pokemon.loot.length">
                                    <span class="text-gray-400">Nenhum loot cadastrado.</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
@endsection