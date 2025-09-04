@extends('layouts.admin')

@section('admin-content')
    <div x-data="pokedexCrud()" class="w-full max-w-7xl mx-auto h-full overflow-auto">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold title text-green-400 tracking-wider">Gerenciar Pokédex</h2>
            <div class="flex gap-2 items-center">
                <input type="text" placeholder="Buscar Pokémon por nome, dex ou descrição..."
                    class="rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200 w-72 focus:outline-none focus:ring-2 focus:ring-green-400"
                    x-model="search">
                <button @click="openModal()"
                    class="bg-green-400 hover:bg-green-600 text-black font-bold px-6 py-2 rounded shadow transition">Adicionar</button>
            </div>
        </div>
        <div class="overflow-x-auto rounded-xl shadow">
            <table class="min-w-full bg-black/80 border border-green-400 text-green-200">
                <thead>
                    <tr>
                        <th class="py-3 px-4 text-left">Dex</th>
                        <th class="py-3 px-4 text-left">Nome</th>
                        <th class="py-3 px-4 text-left">Descrição</th>
                        <th class="py-3 px-4 text-left">Thumb</th>
                        <th class="py-3 px-4 text-center w-32">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="poke in filteredPokemons()" :key="poke.id">
                        <tr class="border-t border-green-700 hover:bg-green-950/50 transition" :data-pokedex-id="poke.id">
                            <td class="py-3 px-4" x-text="poke.dex"></td>
                            <td class="py-3 px-4" x-text="poke.name"></td>
                            <td class="py-3 px-4" x-text="poke.description ? poke.description.substring(0, 30) : '-'"></td>
                            <td class="py-3 px-4">
                                <template x-if="poke.thumb">
                                    <img :src="'{{ asset('images/jogos/psoul/pokemonsthumb/') }}/' + poke.thumb"
                                        alt="Thumb"
                                        class="w-10 h-10 object-contain border-2 border-green-400 rounded bg-black">
                                </template>
                                <template x-if="!poke.thumb">
                                    <span class="text-green-200">-</span>
                                </template>
                            </td>
                            <td class="py-3 px-4 flex justify-center gap-2">
                                <button class="p-1 rounded hover:bg-green-400 hover:text-black transition"
                                    @click="editPokedex(poke.id)" title="Editar">
                                    <span class="material-icons">edit</span>
                                </button>
                                <button class="p-1 rounded hover:bg-red-500 hover:text-white transition"
                                    @click="deletePokedex(poke.id)" title="Excluir">
                                    <span class="material-icons">delete</span>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredPokemons().length === 0">
                        <td colspan="5" class="py-6 text-center text-green-400">Nenhum Pokémon encontrado.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal de Cadastro/Edição -->
        <div x-show="showModal" x-transition
            class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center overflow-auto">
            <div @click.away="closeModal()"
                class="bg-black/95 border-4 border-green-400 rounded-2xl shadow-2xl p-10 w-full max-w-[95rem] mx-4 relative flex flex-col gap-4 overflow-auto"
                style="max-height: 90vh;">
                <button @click="closeModal()"
                    class="absolute top-4 right-4 text-3xl text-green-400 hover:text-green-600 font-extrabold">&times;</button>
                <h3 class="text-2xl font-bold text-green-400 mb-2"
                    x-text="form.id ? 'Editar Pokémon' : 'Adicionar Pokémon'"></h3>
                <form @submit.prevent="submitPokedex" class="grid grid-cols-1 md:grid-cols-4 gap-6"
                    enctype="multipart/form-data" autocomplete="off">
                    <!-- Coluna 1: Dados básicos + Abilities -->
                    <div class="flex flex-col gap-4">
                        <div class="flex gap-2">
                            <div class="w-24">
                                <label class="font-bold text-green-100">Dex</label>
                                <input type="number" min="1" x-model="form.dex" required
                                    class="w-full rounded border border-green-400 px-2 py-2 bg-gray-900 text-green-200">
                            </div>
                            <div class="flex-1">
                                <label class="font-bold text-green-100">Nome</label>
                                <input type="text" x-model="form.name" required
                                    class="w-full rounded border border-green-400 px-2 py-2 bg-gray-900 text-green-200">
                            </div>
                        </div>
                        <div>
                            <label class="font-bold text-green-100">Descrição</label>
                            <textarea x-model="form.description" rows="3"
                                class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200"></textarea>
                        </div>
                        <div>
                            <label class="font-bold text-green-100">Thumb (nome do arquivo)</label>
                            <input type="text" x-model="form.thumb" placeholder="ex: bulbasaur.png"
                                class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                        </div>
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label class="font-bold text-green-100">Tipo Primário</label>
                                <select x-model="form.primary_type"
                                    class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                                    <option value="">Selecione</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="font-bold text-green-100">Tipo Secundário</label>
                                <select x-model="form.secondary_type"
                                    class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                                    <option value="">Nenhum</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Abilities -->
                        <div class="border border-green-400 rounded-xl p-3 mt-2">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-green-200">Abilities</span>
                                <button type="button" @click="addAbility"
                                    class="text-green-400 hover:text-green-600 flex items-center gap-1">
                                    <span class="material-icons text-lg">add_circle</span> Adicionar
                                </button>
                            </div>
                            <template x-for="(ability, idx) in form.abilities" :key="idx">
                                <div class="flex items-center gap-2 mb-1">
                                    <select x-model="ability.id"
                                        class="rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200 flex-1 tom-autocomplete"
                                        x-init="$nextTick(() => new TomSelect($el, { create: false, maxOptions: 100 }))">
                                        <option value="">Selecione</option>
                                        @foreach ($abilities as $ab)
                                            <option value="{{ $ab->id }}">{{ $ab->name }}</option>
                                        @endforeach
                                    </select>
                                    <label class="flex items-center gap-1 text-green-300">
                                        <input type="checkbox" x-model="ability.hidden"> Hidden
                                    </label>
                                    <button type="button" @click="removeAbility(idx)"
                                        class="text-red-400 hover:text-red-600 text-xl font-bold px-2" title="Remover">
                                        <span class="material-icons">remove_circle</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                    <!-- Coluna 2: Moveset -->
                    <div class="flex flex-col gap-4">
                        <div class="border border-green-400 rounded-xl p-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-green-200">Moveset</span>
                                <button type="button" @click="addMove"
                                    class="text-green-400 hover:text-green-600 flex items-center gap-1">
                                    <span class="material-icons text-lg">add_circle</span> Adicionar
                                </button>
                            </div>
                            <template x-for="(move, idx) in form.moveset" :key="idx">
                                <div class="flex items-center gap-2 mb-1">
                                    <input type="number" min="1" max="99" x-model="move.position" readonly
                                        class="w-12 rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200 text-center"
                                        placeholder="Pos">
                                    <select x-model="move.skill_id"
                                        class="flex-1 rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200 tom-autocomplete"
                                        x-init="$nextTick(() => new TomSelect($el, { create: false, maxOptions: 100, maxItems: 1 }))">
                                        <option value="">Skill</option>
                                        @foreach ($skills as $sk)
                                            <option value="{{ $sk->id }}">{{ $sk->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" min="1" max="99" x-model="move.level"
                                        class="w-14 rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200 text-center"
                                        placeholder="Level">
                                    <button type="button" @click="removeMove(idx)"
                                        class="text-red-400 hover:text-red-600 text-xl font-bold px-2" title="Remover">
                                        <span class="material-icons">remove_circle</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                    <!-- Coluna 3: Eggmoves + Movetutors -->
                    <div class="flex flex-col gap-4">
                        <!-- Eggmove -->
                        <div class="border border-green-400 rounded-xl p-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-green-200">Eggmoves</span>
                                <button type="button" @click="addEggmove"
                                    class="text-green-400 hover:text-green-600 flex items-center gap-1">
                                    <span class="material-icons text-lg">add_circle</span> Adicionar
                                </button>
                            </div>
                            <template x-for="(eggmove, idx) in form.eggmoves" :key="idx">
                                <div class="flex items-center gap-2 mb-1">
                                    <select x-model="eggmove.skill_id"
                                        class="flex-1 rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200 tom-autocomplete"
                                        x-init="$nextTick(() => new TomSelect($el, { create: false, maxOptions: 100 }))">
                                        <option value="">Skill</option>
                                        @foreach ($skills as $sk)
                                            <option value="{{ $sk->id }}">{{ $sk->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" @click="removeEggmove(idx)"
                                        class="text-red-400 hover:text-red-600 text-xl font-bold px-2" title="Remover">
                                        <span class="material-icons">remove_circle</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <!-- Movetutor -->
                        <div class="border border-green-400 rounded-xl p-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-green-200">Movetutors</span>
                                <button type="button" @click="addMovetutor"
                                    class="text-green-400 hover:text-green-600 flex items-center gap-1">
                                    <span class="material-icons text-lg">add_circle</span> Adicionar
                                </button>
                            </div>
                            <template x-for="(movetutor, idx) in form.movetutors" :key="idx">
                                <div class="flex items-center gap-2 mb-1">
                                    <select x-model="movetutor.skill_id"
                                        class="flex-1 rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200 tom-autocomplete"
                                        x-init="$nextTick(() => new TomSelect($el, { create: false, maxOptions: 100 }))">
                                        <option value="">Skill</option>
                                        @foreach ($skills as $sk)
                                            <option value="{{ $sk->id }}">{{ $sk->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" @click="removeMovetutor(idx)"
                                        class="text-red-400 hover:text-red-600 text-xl font-bold px-2" title="Remover">
                                        <span class="material-icons">remove_circle</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                    <!-- Coluna 4: Loot -->
                    <div class="flex flex-col gap-4">
                        <div class="border border-green-400 rounded-xl p-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-green-200">Loot</span>
                                <button type="button" @click="addLoot"
                                    class="text-green-400 hover:text-green-600 flex items-center gap-1">
                                    <span class="material-icons text-lg">add_circle</span> Adicionar
                                </button>
                            </div>
                            <template x-for="(loot, idx) in form.loot" :key="idx">
                                <div class="flex items-center gap-2 mb-1">
                                    <select x-model="loot.item_id"
                                        class="flex-1 rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200 tom-autocomplete"
                                        x-init="$nextTick(() => new TomSelect($el, { create: false, maxOptions: 100 }))">
                                        <option value="">Item</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" min="1" max="99" x-model="loot.min"
                                        class="w-12 rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200 text-center"
                                        placeholder="Min">
                                    <input type="number" min="1" max="99" x-model="loot.max"
                                        class="w-12 rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200 text-center"
                                        placeholder="Max">
                                    <button type="button" @click="removeLoot(idx)"
                                        class="text-red-400 hover:text-red-600 text-xl font-bold px-2" title="Remover">
                                        <span class="material-icons">remove_circle</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                    <!-- Mensagens -->
                    <div class="col-span-4">
                        <div x-show="errorMsg" class="text-red-400 text-sm mt-2" x-text="errorMsg"></div>
                        <div x-show="successMsg" class="text-green-400 text-sm mt-2" x-text="successMsg"></div>
                    </div>
                    <div class="col-span-4 flex gap-3 justify-end">
                        <button type="submit"
                            class="bg-green-400 hover:bg-green-600 text-black font-bold rounded px-8 py-2 transition">
                            Salvar
                        </button>
                        <button type="button" @click="closeModal()"
                            class="bg-gray-500 hover:bg-gray-700 text-white rounded px-8 py-2 transition">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Material Icons CDN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Tom Select JS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.default.min.css" rel="stylesheet">
    <!-- Alpine.js CDN -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        function pokedexCrud() {
            return {
                pokemons: @json($pokemons),
                showModal: false,
                form: {
                    id: null,
                    dex: '',
                    name: '',
                    description: '',
                    thumb: null,
                    thumb_preview: null,
                    primary_type: '',
                    secondary_type: '',
                    abilities: [],
                    moveset: [],
                    eggmoves: [],
                    movetutors: [],
                    loot: []
                },
                errorMsg: '',
                successMsg: '',
                search: '',
                filteredPokemons() {
                    if (!this.search) return this.pokemons;
                    let term = this.search.toLowerCase();
                    return this.pokemons.filter(poke =>
                        String(poke.dex).includes(term) ||
                        (poke.name && poke.name.toLowerCase().includes(term)) ||
                        (poke.description && poke.description.toLowerCase().includes(term))
                    );
                },
                openModal() {
                    this.resetForm();
                    this.showModal = true;
                },
                closeModal() {
                    this.showModal = false;
                    this.resetForm();
                    this.errorMsg = '';
                    this.successMsg = '';
                },
                resetForm() {
                    this.form = {
                        id: null,
                        dex: '',
                        name: '',
                        description: '',
                        thumb: null,
                        thumb_preview: null,
                        primary_type: '',
                        secondary_type: '',
                        abilities: [],
                        moveset: [],
                        eggmoves: [],
                        movetutors: [],
                        loot: []
                    };
                },
                handleThumb(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.form.thumb = file;
                        this.form.thumb_preview = URL.createObjectURL(file);
                    }
                },
                addAbility() {
                    this.form.abilities.push({
                        id: '',
                        hidden: false
                    });
                },
                removeAbility(idx) {
                    this.form.abilities.splice(idx, 1);
                },
                addMove() {
                    const nextPos = this.form.moveset.length + 1;
                    let nextLevel = 1;
                    if (this.form.moveset.length > 0) {
                        // Último level + 5
                        const lastLevel = parseInt(this.form.moveset[this.form.moveset.length - 1].level) || 1;
                        if (lastLevel === 1) {
                            nextLevel = lastLevel + 4;
                        } else {
                            nextLevel = lastLevel + 5;
                        }
                    }
                    this.form.moveset.push({
                        position: nextPos,
                        skill_id: '',
                        level: nextLevel
                    });
                },
                removeMove(idx) {
                    this.form.moveset.splice(idx, 1);
                    // Recalcula posições
                    this.form.moveset.forEach((m, i) => m.position = i + 1);
                },
                addEggmove() {
                    this.form.eggmoves.push({
                        skill_id: ''
                    });
                },
                removeEggmove(idx) {
                    this.form.eggmoves.splice(idx, 1);
                },
                addMovetutor() {
                    this.form.movetutors.push({
                        skill_id: ''
                    });
                },
                removeMovetutor(idx) {
                    this.form.movetutors.splice(idx, 1);
                },
                addLoot() {
                    this.form.loot.push({
                        item_id: '',
                        min: '1',
                        max: ''
                    });
                },
                removeLoot(idx) {
                    this.form.loot.splice(idx, 1);
                },
                editPokedex(id) {
                    this.resetForm();
                    this.errorMsg = '';
                    this.successMsg = '';
                    fetch(`/admin/psoul/pokedex/ajax/${id}`)
                        .then(async response => {
                            if (response.status === 401 || response.status === 419 || response.status === 302) {
                                throw new Error('Sessão expirada. Faça login novamente.');
                            }
                            let d = await response.json();
                            if (!d.success) throw new Error(d.message || 'Erro ao buscar Pokémon');
                            return d.pokemon;
                        })
                        .then(pokemon => {
                            // Preenche o form com os dados recebidos
                            this.form.id = pokemon.id;
                            this.form.dex = pokemon.dex;
                            this.form.name = pokemon.name;
                            this.form.description = pokemon.description || '';
                            this.form.thumb = typeof pokemon.thumb === 'string' ? pokemon.thumb : '';
                            this.form.primary_type = pokemon.primary_type || '';
                            this.form.secondary_type = pokemon.secondary_type || '';
                            this.form.abilities = Array.isArray(pokemon.abilities) ? pokemon.abilities : [];
                            this.form.moveset = Array.isArray(pokemon.moveset) ? pokemon.moveset : [];
                            this.form.eggmoves = Array.isArray(pokemon.eggmoves) ? pokemon.eggmoves : [];
                            this.form.movetutors = Array.isArray(pokemon.movetutors) ? pokemon.movetutors : [];
                            this.form.loot = Array.isArray(pokemon.loot) ? pokemon.loot : [];
                            this.showModal = true;
                        })
                        .catch(e => {
                            this.errorMsg = e.message || 'Erro ao carregar dados para edição.';
                        });
                },
                submitPokedex() {
                    this.errorMsg = '';
                    this.successMsg = '';

                    let data = {
                        dex: this.form.dex,
                        name: this.form.name,
                        description: this.form.description,
                        thumb: typeof this.form.thumb === 'string' ? this.form.thumb : '',
                        primary_type: this.form.primary_type,
                        secondary_type: this.form.secondary_type,
                        abilities: this.form.abilities,
                        moveset: this.form.moveset,
                        eggmoves: this.form.eggmoves,
                        movetutors: this.form.movetutors,
                        loot: this.form.loot.map(l => ({
                            item_id: l.item_id,
                            min: l.min,
                            max: l.max === '' ? null : l.max
                        }))
                    };

                    const isEdit = !!this.form.id;
                    const url = isEdit ?
                        `{{ url('admin/psoul/pokedex/ajax') }}/${this.form.id}` :
                        `{{ route('admin.psoul.pokedex.ajax.store') }}`;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(async response => {
                            let d = await response.json();
                            if (!response.ok) throw new Error(d.message || 'Erro ao salvar');
                            return d;
                        })
                        .then(d => {
                            this.successMsg = d.message;

                            if (isEdit) {
                                // Se for edição, fecha o modal após salvar
                                setTimeout(() => {
                                    this.successMsg = '';
                                    this.closeModal();
                                    location.reload();
                                }, 800);
                            } else {
                                // Se for novo cadastro, limpa os campos e mantém o modal aberto para próximo cadastro
                                this.resetForm();
                                // Opcional: recarrega a tabela em tela para mostrar o novo cadastro
                                // setTimeout(() => { this.successMsg = ''; location.reload(); }, 800);
                                // Se preferir NÃO recarregar a tabela, remova o location.reload() acima.
                            }
                        })
                        .catch(e => {
                            // Se for erro de validação Laravel, tente extrair as mensagens
                            if (e && e.message && e.message.includes('The given data was invalid')) {
                                this.errorMsg = 'Dados inválidos. Verifique os campos obrigatórios.';
                            } else {
                                this.errorMsg = e.message || 'Erro ao cadastrar Pokémon.';
                            }
                        });
                },
                deletePokedex(id) {
                    if (!confirm('Tem certeza que deseja excluir este Pokémon? Esta ação não pode ser desfeita!')) return;
                    fetch(`/admin/psoul/pokedex/ajax/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(async response => {
                            let d = await response.json();
                            if (!response.ok) throw new Error(d.message || 'Erro ao excluir');
                            return d;
                        })
                        .then(d => {
                            alert(d.message);
                            // Remove da tabela visualmente (opcional) ou recarrega a página
                            location.reload();
                        })
                        .catch(e => {
                            alert(e.message || 'Erro ao excluir Pokémon.');
                        });
                },
            }
        }
    </script>

    <style>
        /* Remove os spinners dos campos number (Chrome, Safari, Edge, Opera) */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Remove os spinners dos campos number (Firefox) */
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Estiliza a barra de rolagem do container principal/modal */
        .overflow-auto::-webkit-scrollbar {
            width: 10px;
            background: #0B2C21;
            border-radius: 8px;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
            background: #22c55e;
            border-radius: 8px;
            border: 2px solid #0B2C21;
        }

        .overflow-auto::-webkit-scrollbar-thumb:hover {
            background: #16a34a;
        }

        /* Firefox */
        .overflow-auto {
            scrollbar-width: thin;
            scrollbar-color: #22c55e #0B2C21;
        }

        /* Tom Select custom para tema escuro */
        .ts-dropdown,
        .ts-control,
        .ts-dropdown .option,
        .ts-dropdown .active,
        .ts-dropdown .selected {
            background: #161b22 !important;
            color: #a3e635 !important;
        }

        .ts-dropdown .option:hover,
        .ts-dropdown .active {
            background: #22c55e !important;
            color: #000 !important;
        }

        .ts-control {
            border-color: #22c55e !important;
        }
    </style>
@endsection
