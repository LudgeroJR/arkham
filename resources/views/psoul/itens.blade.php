@extends('layouts.app')
@section('content')
    @include('partials.psoul-menu')

    <div 
        x-data="{
            showModal: false,
            modalItem: null,
            search: '',
            openModal(item) {
                this.showModal = false;
                this.modalItem = null;
                fetch(`/psoul/itens/${item.id}/json`)
                    .then(res => {
                        if (!res.ok) throw new Error('Erro ao buscar dados');
                        return res.json();
                    })
                    .then(data => {
                        this.modalItem = data;
                        this.showModal = true;
                    })
                    .catch(e => {
                        console.error(e);
                        alert('Erro ao carregar os dados do item.');
                    });
            },
            closeModal() {
                this.showModal = false;
                this.modalItem = null;
            }
        }"
        @keydown.escape.window="closeModal()"
        class="bg-white/90 rounded-2xl shadow-2xl p-6 max-w-5xl mx-auto w-full min-h-[360px] flex flex-col items-center"
    >
        <h2 class="text-3xl font-bold text-[#B81F1C] mb-6 tracking-wide drop-shadow">Itens</h2>

        <!-- Campo de pesquisa -->
        <input 
            type="text"
            x-model="search"
            placeholder="Pesquisar por nome..."
            class="mb-4 px-4 py-2 rounded border border-[#EDC416] focus:outline-none focus:ring-2 focus:ring-[#EDC416] w-full max-w-md"
        >

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full">
            @foreach($items as $itemLoop)
                <div 
                    class="cursor-pointer bg-yellow-100 rounded p-2 text-center font-bold hover:bg-yellow-200 transition"
                    x-show="!search || '{{ mb_strtolower($itemLoop->name) }}'.includes(search.toLowerCase())"
                    @click="openModal({ id: {{ $itemLoop->id }}, name: @js($itemLoop->name) })"
                >
                    {{ $itemLoop->name }}
                </div>
            @endforeach
        </div>
        <!-- Modal Separado -->
        <div 
            x-show="showModal && modalItem"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 backdrop-blur-sm"
        >
            <div 
                @click.away="closeModal()" 
                class="bg-gradient-to-br from-[#F8EE9A] via-[#EDC416] to-[#EA7514] rounded-2xl shadow-2xl p-8 max-w-xl w-full mx-4 relative overflow-y-auto max-h-[90vh] border-4 border-[#B81F1C] flex flex-col gap-4"
            >
                <button 
                    @click="closeModal()" 
                    class="absolute top-4 right-4 text-3xl text-[#B81F1C] hover:text-[#C6241D] font-extrabold transition"
                    style="text-shadow: 1px 1px 0 #fff, 2px 2px 0 #EDC416;"
                >&times;</button>
                <div class="flex flex-col items-center mb-4">
                    <div class="w-24 h-24 rounded-full bg-[#fff8] flex items-center justify-center shadow-lg border-4 border-[#B81F1C] mb-2">
                        <svg class="w-14 h-14 text-[#B81F1C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="#B81F1C" stroke-width="2" fill="#F8EE9A"/>
                            <text x="50%" y="55%" text-anchor="middle" fill="#B81F1C" font-size="18" font-family="Bebas Neue, Oswald, Arial" dy=".3em">IT</text>
                        </svg>
                    </div>
                    <h3 
                        class="text-3xl font-extrabold mb-2 text-center text-[#B81F1C] drop-shadow-lg tracking-wide"
                        style="font-family: 'Bebas Neue', Oswald, Arial, sans-serif; letter-spacing: 2px;"
                        x-text="modalItem.name"
                    ></h3>
                </div>

                <div class="flex flex-col gap-3">
                    <template x-if="modalItem.materials && modalItem.materials.length">
                        <div class="bg-white/90 rounded-xl shadow p-4 border-l-8 border-[#B81F1C]">
                            <div class="font-extrabold text-[#B81F1C] mb-1 text-lg flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-full bg-[#B81F1C]"></span>
                                Craftado com:
                            </div>
                            <ul class="list-disc list-inside text-[#680F0F] font-medium ml-4">
                                <template x-for="mat in modalItem.materials" :key="mat.id">
                                    <li x-text="mat.name + ' (x'+mat.amount+')'"></li>
                                </template>
                            </ul>
                        </div>
                    </template>

                    <template x-if="modalItem.used_for && modalItem.used_for.length">
                        <div class="bg-white/90 rounded-xl shadow p-4 border-l-8 border-[#B81F1C]">
                            <div class="font-extrabold text-[#B81F1C] mb-1 text-lg flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-full bg-[#B81F1C]"></span>
                                Usado para:
                            </div>
                            <ul class="list-disc list-inside text-[#680F0F] font-medium ml-4">
                                <template x-for="uf in modalItem.used_for" :key="uf.id">
                                    <li x-text="uf.name"></li>
                                </template>
                            </ul>
                        </div>
                    </template>

                    <template x-if="modalItem.dropped_by && modalItem.dropped_by.length">
                        <div class="bg-white/90 rounded-xl shadow p-4 border-l-8 border-[#B81F1C]">
                            <div class="font-extrabold text-[#B81F1C] mb-1 text-lg flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-full bg-[#B81F1C]"></span>
                                Dropado por:
                            </div>
                            <ul class="list-disc list-inside text-[#680F0F] font-medium ml-4">
                                <template x-for="poke in modalItem.dropped_by" :key="poke.id">
                                    <li x-text="poke.name"></li>
                                </template>
                            </ul>
                        </div>
                    </template>

                    <template x-if="modalItem.sold_by && modalItem.sold_by.length">
                        <div class="bg-white/90 rounded-xl shadow p-4 border-l-8 border-[#B81F1C]">
                            <div class="font-extrabold text-[#B81F1C] mb-1 text-lg flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-full bg-[#B81F1C]"></span>
                                Vendido por:
                            </div>
                            <ul class="list-disc list-inside text-[#680F0F] font-medium ml-4">
                                <template x-for="npc in modalItem.sold_by" :key="npc.id">
                                    <li x-text="npc.name"></li>
                                </template>
                            </ul>
                        </div>
                    </template>

                    <template x-if="modalItem.bought_by && modalItem.bought_by.length">
                        <div class="bg-white/90 rounded-xl shadow p-4 border-l-8 border-[#B81F1C]">
                            <div class="font-extrabold text-[#B81F1C] mb-1 text-lg flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-full bg-[#B81F1C]"></span>
                                Comprado por:
                            </div>
                            <ul class="list-disc list-inside text-[#680F0F] font-medium ml-4">
                                <template x-for="npc in modalItem.bought_by" :key="npc.id">
                                    <li x-text="npc.name"></li>
                                </template>
                            </ul>
                        </div>
                    </template>

                    <template x-if="modalItem.quest_rewards && modalItem.quest_rewards.length">
                        <div class="bg-white/90 rounded-xl shadow p-4 border-l-8 border-[#B81F1C]">
                            <div class="font-extrabold text-[#B81F1C] mb-1 text-lg flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-full bg-[#B81F1C]"></span>
                                Recompensa da Quest:
                            </div>
                            <ul class="list-disc list-inside text-[#680F0F] font-medium ml-4">
                                <template x-for="quest in modalItem.quest_rewards" :key="quest.id">
                                    <li>
                                        <template x-if="quest.link">
                                            <a :href="quest.link" class="text-blue-700 underline" target="_blank" x-text="quest.name"></a>
                                        </template>
                                        <template x-if="!quest.link">
                                            <span x-text="quest.name"></span>
                                        </template>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
@endsection