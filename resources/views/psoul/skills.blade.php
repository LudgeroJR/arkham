@extends('layouts.app')
@section('content')
    @include('partials.psoul-menu')

    <div 
        x-data="{
            showModal: false,
            modalSkill: null,
            search: '',
            openModal(skill) {
                this.showModal = false;
                this.modalSkill = null;
                fetch(`/psoul/skills/${skill.id}/json`)
                    .then(res => {
                        if (!res.ok) throw new Error('Erro ao buscar skill');
                        return res.json();
                    })
                    .then(data => {
                        this.modalSkill = data;
                        this.showModal = true;
                    })
                    .catch(e => {
                        alert('Erro ao carregar os dados da skill.');
                        console.error(e);
                    });
            },
            closeModal() {
                this.showModal = false;
                this.modalSkill = null;
            }
        }"
        @keydown.escape.window="closeModal()"
        class="bg-white/90 rounded-2xl shadow-2xl p-6 max-w-5xl mx-auto w-full min-h-[360px] flex flex-col items-center"
    >
        <h2 class="text-3xl font-bold text-[#B81F1C] mb-6 tracking-wide drop-shadow">Skills</h2>

        <!-- Pesquisa -->
        <input 
            type="text"
            x-model="search"
            placeholder="Pesquisar skill..."
            class="mb-4 px-4 py-2 rounded border border-[#EDC416] focus:outline-none focus:ring-2 focus:ring-[#EDC416] w-full max-w-md"
        >

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full">
            @foreach($skills as $skill)
                <div 
                    class="cursor-pointer bg-yellow-100 rounded p-2 text-center font-bold hover:bg-yellow-200 transition"
                    x-show="!search || '{{ mb_strtolower($skill->name) }}'.includes(search.toLowerCase())"
                    @click="openModal({ id: {{ $skill->id }}, name: @js($skill->name) })"
                >
                    {{ $skill->name }}
                </div>
            @endforeach
        </div>

        <!-- Modal -->
        <div 
            x-show="showModal && modalSkill"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 backdrop-blur-sm"
        >
            <div 
                @click.away="closeModal()" 
                class="bg-gradient-to-br from-[#F8EE9A] via-[#EDC416] to-[#EA7514] rounded-2xl shadow-2xl p-8 w-full mx-4 relative overflow-y-auto max-h-[90vh] border-4 border-[#B81F1C]"
                :class="{
                    'max-w-[32rem]': showGeneral && !showMoveset && !showEgg && !showTutor,
                    'max-w-[60rem]': (showGeneral && showMoveset && !showEgg && !showTutor) ||
                            (showGeneral && !showMoveset && showEgg && !showTutor) ||
                            (showGeneral && !showMoveset && !showEgg && showTutor) ||
                            (showGeneral && !showMoveset && showEgg && showTutor),
                    'max-w-[95rem]': showGeneral && showMoveset && (showEgg || showTutor)
                }"
                x-init="
                    $watch('modalSkill', () => {
                        showGeneral = true;
                        showMoveset = modalSkill && modalSkill.moveset_pokemons && modalSkill.moveset_pokemons.length > 0;
                        showEgg = modalSkill && modalSkill.eggmove_pokemons && modalSkill.eggmove_pokemons.length > 0;
                        showTutor = modalSkill && modalSkill.movetutor_pokemons && modalSkill.movetutor_pokemons.length > 0;
                    })
                "
                x-data="{
                    showGeneral: true,
                    showMoveset: false,
                    showEgg: false,
                    showTutor: false
                }"
            >
                <button 
                    @click="closeModal()" 
                    class="absolute top-4 right-4 text-3xl text-[#B81F1C] hover:text-[#C6241D] font-extrabold transition"
                    style="text-shadow: 1px 1px 0 #fff, 2px 2px 0 #EDC416;"
                >&times;</button>
                <div 
                    class="grid gap-6 md:grid-cols-2 grid-cols-1"
                    :class="{
                        // 3 colunas: showGeneral + showMoveset + (showEgg ou showTutor)
                        'md:grid-cols-3': (
                            showGeneral && showMoveset && (showEgg || showTutor)
                        ),
                        // 2 colunas: showGeneral + showMoveset, showGeneral + showEgg, showGeneral + showTutor, showGeneral + showEgg + showTutor
                        'md:grid-cols-2': (
                            (showGeneral && showMoveset && !showEgg && !showTutor) ||
                            (showGeneral && !showMoveset && showEgg && !showTutor) ||
                            (showGeneral && !showMoveset && !showEgg && showTutor) ||
                            (showGeneral && !showMoveset && showEgg && showTutor)
                        ),
                        // 1 coluna: só showGeneral
                        'grid-cols-1': (
                            showGeneral && !showMoveset && !showEgg && !showTutor
                        )
                    }"
                >
                    <!-- Coluna 1: Informações gerais -->
                    <template x-if="showGeneral">
                        <div class="flex flex-col items-center mb-4">
                            <div class="w-24 h-24 rounded-full bg-[#fff8] flex items-center justify-center shadow-lg border-4 border-[#B81F1C] mb-2">
                                <svg class="w-14 h-14 text-[#B81F1C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="#B81F1C" stroke-width="2" fill="#F8EE9A"/>
                                    <text x="50%" y="55%" text-anchor="middle" fill="#B81F1C" font-size="18" font-family="Bebas Neue, Oswald, Arial" dy=".3em">SK</text>
                                </svg>
                            </div>
                            <h3 
                                class="text-3xl font-extrabold mb-2 text-center text-[#B81F1C] drop-shadow-lg tracking-wide"
                                style="font-family: 'Bebas Neue', Oswald, Arial, sans-serif; letter-spacing: 2px;"
                                x-text="modalSkill.name"
                            ></h3>
                            <div class="flex gap-2 items-center mb-2">
                                <span class="px-2 py-0.5 rounded text-xs font-bold"
                                    :class="{
                                        'bg-[#EDC416] text-[#B81F1C]': modalSkill.category === 'Physical',
                                        'bg-[#F6E160] text-[#680F0F]': modalSkill.category === 'Special',
                                        'bg-[#EA7514] text-white': modalSkill.category === 'Status'
                                    }"
                                    x-text="modalSkill.category"></span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-[#B81F1C] text-[#F8EE9A] text-xs font-bold">
                                    <img :src="'{{ asset('images/jogos/psoul/types') }}/' + modalSkill.type + '.png'" alt="" class="w-4 h-4 mr-1"> <span x-text="modalSkill.type"></span>
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-[#EDC416] text-[#B81F1C] text-xs font-bold border border-[#B81F1C] ml-4">
                                    <span>Power: </span>
                                    <span class="ml-1" x-text="modalSkill.power || '-'"></span>
                                </span>
                            </div>
                            <div class="text-[#680F0F] mb-2 text-center font-semibold" x-text="modalSkill.description"></div>
                            <div class="mb-2 flex flex-wrap gap-2 justify-center" x-show="modalSkill.ranges && modalSkill.ranges.length">
                                <template x-for="r in modalSkill.ranges" :key="r">
                                    <span class="inline-block bg-[#F8EE9A] text-[#C6241D] rounded px-2 py-0.5 text-xs font-bold border border-[#B81F1C]" x-text="r"></span>
                                </template>
                            </div>
                        </div>
                    </template>
                    <!-- Coluna 2: Moveset -->
                    <template x-if="showMoveset">
                        <div>
                            <div class="bg-white/90 rounded-xl shadow p-4 border-l-8 border-[#B81F1C] flex flex-col max-h-96 overflow-y-auto min-w-0">
                                <div class="font-extrabold text-[#B81F1C] mb-1 text-lg flex items-center gap-2">
                                    <span class="inline-block w-3 h-3 rounded-full bg-[#B81F1C]"></span>
                                    Aprende no Moveset:
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                    <template x-for="poke in modalSkill.moveset_pokemons" :key="poke.id">
                                        <div class="flex flex-col items-center bg-[#F8EE9A] rounded-lg p-2 shadow border border-[#EDC416] hover:bg-[#FFFBE6] transition">
                                            <img :src="'{{ asset('images/jogos/psoul/pokemonsthumb') }}/' + poke.thumb" :alt="poke.name" class="w-12 h-12 object-contain mb-1 rounded-full border-2 border-[#B81F1C] bg-white" loading="lazy">
                                            <span class="text-[#B81F1C] font-bold text-sm text-center" x-text="poke.name"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!-- Coluna 3: EggMove + MoveTutor -->
                    <template x-if="showEgg || showTutor">
                        <div class="flex flex-col gap-4">
                            <template x-if="showEgg">
                                <div class="bg-white/90 rounded-xl shadow p-4 border-l-8 border-[#B81F1C] flex flex-col max-h-44 overflow-y-auto min-w-0">
                                    <div class="font-extrabold text-[#B81F1C] mb-1 text-lg flex items-center gap-2">
                                        <span class="inline-block w-3 h-3 rounded-full bg-[#B81F1C]"></span>
                                        Aprende como EggMove:
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                        <template x-for="poke in modalSkill.eggmove_pokemons" :key="poke.id">
                                            <div class="flex flex-col items-center bg-[#F8EE9A] rounded-lg p-2 shadow border border-[#EDC416] hover:bg-[#FFFBE6] transition">
                                                <img :src="'{{ asset('images/jogos/psoul/pokemonsthumb') }}/' + poke.thumb" :alt="poke.name" class="w-12 h-12 object-contain mb-1 rounded-full border-2 border-[#B81F1C] bg-white" loading="lazy">
                                                <span class="text-[#B81F1C] font-bold text-sm text-center" x-text="poke.name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                            <template x-if="showTutor">
                                <div class="bg-white/90 rounded-xl shadow p-4 border-l-8 border-[#B81F1C] flex flex-col max-h-44 overflow-y-auto min-w-0">
                                    <div class="font-extrabold text-[#B81F1C] mb-1 text-lg flex items-center gap-2">
                                        <span class="inline-block w-3 h-3 rounded-full bg-[#B81F1C]"></span>
                                        Aprende como MoveTutor:
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                        <template x-for="poke in modalSkill.movetutor_pokemons" :key="poke.id">
                                            <div class="flex flex-col items-center bg-[#F8EE9A] rounded-lg p-2 shadow border border-[#EDC416] hover:bg-[#FFFBE6] transition">
                                                <img :src="'{{ asset('images/jogos/psoul/pokemonsthumb') }}/' + poke.thumb" :alt="poke.name" class="w-12 h-12 object-contain mb-1 rounded-full border-2 border-[#B81F1C] bg-white" loading="lazy">
                                                <span class="text-[#B81F1C] font-bold text-sm text-center" x-text="poke.name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
@endsection