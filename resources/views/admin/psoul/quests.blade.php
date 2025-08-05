@extends('layouts.admin')

@section('admin-content')
    <div x-data="questCrud()" class="w-full max-w-7xl mx-auto h-full overflow-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold title text-green-400 tracking-wider">Gerenciar Quests</h2>
            <div class="flex gap-2 items-center">
                <input type="text" placeholder="Buscar Quest por nome ou requisito..."
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
                        <th class="py-3 px-4 text-left">Nome</th>
                        <th class="py-3 px-4 text-left">Requisitos</th>
                        <th class="py-3 px-4 text-left">Recompensas</th>
                        <th class="py-3 px-4 text-left">Link</th>
                        <th class="py-3 px-4 text-center w-32">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="quest in filteredQuests()" :key="quest.id">
                        <tr class="border-t border-green-700 hover:bg-green-950/50 transition">
                            <td class="py-3 px-4" x-text="quest.name"></td>
                            <td class="py-3 px-4" x-text="quest.requirements"></td>
                            <td class="py-3 px-4">
                                <template x-if="quest.rewards && quest.rewards.length">
                                    <span>
                                        <template x-for="reward in quest.rewards" :key="reward.id">
                                            <span>
                                                <span x-text="reward.name"></span>
                                                <span class="text-green-300 font-mono"
                                                    x-show="reward.pivot && reward.pivot.amount">x <span
                                                        x-text="reward.pivot.amount"></span></span>
                                                <span x-show="!$last">, </span>
                                            </span>
                                        </template>
                                    </span>
                                </template>
                                <template x-if="!quest.rewards || !quest.rewards.length">
                                    <span>-</span>
                                </template>
                            </td>
                            <td class="py-3 px-4">
                                <a :href="quest.link" x-text="quest.link" class="text-green-400 underline"
                                    target="_blank" x-show="quest.link"></a>
                                <span x-show="!quest.link">-</span>
                            </td>
                            <td class="py-3 px-4 flex justify-center gap-2">
                                <button class="p-1 rounded hover:bg-green-400 hover:text-black transition"
                                    @click="editQuest(quest.id)" title="Editar">
                                    <span class="material-icons">edit</span>
                                </button>
                                <button class="p-1 rounded hover:bg-red-500 hover:text-white transition"
                                    @click="deleteQuest(quest.id)" title="Excluir">
                                    <span class="material-icons">delete</span>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredQuests().length === 0">
                        <td colspan="5" class="py-6 text-center text-green-400">Nenhuma Quest encontrada.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Modal de Cadastro/Edição de Quest -->
        <div x-show="showModal" x-transition
            class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center overflow-auto">
            <div @click.away="closeModal()"
                class="bg-black/95 border-4 border-green-400 rounded-2xl shadow-2xl p-10 w-full max-w-xl mx-4 relative flex flex-col gap-4 overflow-auto">
                <button @click="closeModal()"
                    class="absolute top-4 right-4 text-3xl text-green-400 hover:text-green-600 font-extrabold">&times;</button>
                <h3 class="text-2xl font-bold text-green-400 mb-2" x-text="form.id ? 'Editar Quest' : 'Adicionar Quest'">
                </h3>
                <form @submit.prevent="submitQuest" autocomplete="off" class="flex flex-col gap-4">
                    <div>
                        <label class="font-bold text-green-100">Nome</label>
                        <input type="text" x-model="form.name" required
                            class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                    </div>
                    <div>
                        <label class="font-bold text-green-100">Requisitos</label>
                        <textarea x-model="form.requirements" rows="2" required
                            class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200"></textarea>
                    </div>
                    <div>
                        <label class="font-bold text-green-100">Recompensas (itens)</label>
                        <div class="flex gap-2 mb-2">
                            <select x-model="rewardToAdd.item_id"
                                class="rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200 flex-1 tom-autocomplete"
                                x-ref="rewardSelect" x-init="$nextTick(() => new TomSelect($el, { create: false, maxOptions: 100 }))">
                                <option value="">Selecione o item</option>
                                <template x-for="item in items" :key="item.id">
                                    <option :value="item.id" x-text="item.name"></option>
                                </template>
                            </select>
                            <input type="number" min="1" x-model="rewardToAdd.amount" placeholder="Qtd"
                                class="w-20 rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200 text-center">
                            <button type="button" @click="addReward"
                                class="bg-green-400 hover:bg-green-600 text-black font-bold rounded px-4 py-1 transition">Adicionar</button>
                        </div>
                        <div class="mt-2 flex flex-col gap-2">
                            <template x-for="(reward, idx) in form.rewards" :key="reward.item_id">
                                <div
                                    class="flex items-center gap-2 bg-green-900/50 text-green-300 font-mono rounded px-2 py-1 text-sm">
                                    <span x-text="itemName(reward.item_id)" class="mr-1"></span>
                                    <span>x <span x-text="reward.amount"></span></span>
                                    <button type="button" @click="removeReward(idx)"
                                        class="ml-1 text-red-400 hover:text-red-600 font-bold">&times;</button>
                                </div>
                            </template>
                            <div x-show="!form.rewards.length" class="text-green-400 text-xs px-1">Nenhuma recompensa
                                adicionada ainda.</div>
                        </div>
                    </div>
                    <div>
                        <label class="font-bold text-green-100">Link (documentação externa)</label>
                        <input type="url" x-model="form.link" placeholder="https://..."
                            class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                    </div>
                    <div x-show="errorMsg" class="text-red-400 text-sm mt-2" x-text="errorMsg"></div>
                    <div x-show="successMsg" class="text-green-400 text-sm mt-2" x-text="successMsg"></div>
                    <div class="flex gap-3 justify-end">
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
    <!-- Tom Select JS/CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.default.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <!-- Alpine.js CDN -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        function questCrud() {
            return {
                quests: @json($quests),
                items: @json($items),
                search: '',
                showModal: false,
                rewardToAdd: {
                    item_id: '',
                    amount: 1
                },
                form: {
                    id: null,
                    name: '',
                    requirements: '',
                    rewards: [],
                    link: ''
                },
                errorMsg: '',
                successMsg: '',
                filteredQuests() {
                    if (!this.search) return this.quests;
                    let term = this.search.toLowerCase();
                    return this.quests.filter(q =>
                        (q.name && q.name.toLowerCase().includes(term)) ||
                        (q.requirements && q.requirements.toLowerCase().includes(term))
                    );
                },
                itemName(id) {
                    let item = this.items.find(i => i.id == id);
                    return item ? item.name : id;
                },
                addReward() {
                    if (!this.rewardToAdd.item_id || !this.rewardToAdd.amount || this.rewardToAdd.amount < 1) return;
                    if (this.form.rewards.some(r => r.item_id == this.rewardToAdd.item_id)) {
                        this.errorMsg = 'Esse item já foi adicionado!';
                        return;
                    }
                    this.form.rewards.push({
                        item_id: this.rewardToAdd.item_id,
                        amount: this.rewardToAdd.amount
                    });
                    this.rewardToAdd = {
                        item_id: '',
                        amount: 1
                    };
                    if (this.$refs.rewardSelect && this.$refs.rewardSelect.tomselect) this.$refs.rewardSelect.tomselect
                        .clear();
                    this.errorMsg = '';
                },
                removeReward(idx) {
                    this.form.rewards.splice(idx, 1);
                },
                openModal() {
                    this.resetForm();
                    this.showModal = true;
                    this.$nextTick(() => {
                        if (this.$refs.rewardSelect && this.$refs.rewardSelect.tomselect) {
                            this.$refs.rewardSelect.tomselect.clear();
                        }
                    });
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
                        name: '',
                        requirements: '',
                        rewards: [],
                        link: ''
                    };
                    this.rewardToAdd = {
                        item_id: '',
                        amount: 1
                    };
                },
                submitQuest() {
                    this.errorMsg = '';
                    this.successMsg = '';
                    const isEdit = !!this.form.id;
                    const url = isEdit ?
                        `/admin/psoul/quests/${this.form.id}` :
                        '{{ route('admin.psoul.quests.store') }}';

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                name: this.form.name,
                                requirements: this.form.requirements,
                                rewards: this.form.rewards,
                                link: this.form.link
                            })
                        })
                        .then(async response => {
                            let d = await response.json();
                            if (!response.ok) throw new Error(d.message || 'Erro ao salvar');
                            return d;
                        })
                        .then(d => {
                            this.successMsg = d.message;
                            setTimeout(() => {
                                this.successMsg = '';
                                this.closeModal();
                                location.reload();
                            }, 800);
                        })
                        .catch(e => {
                            this.errorMsg = e.message || 'Erro ao cadastrar Quest.';
                        });
                },
                editQuest(id) {
                    this.resetForm();
                    this.errorMsg = '';
                    this.successMsg = '';
                    fetch(`/admin/psoul/quests/ajax/${id}`)
                        .then(async response => {
                            let d = await response.json();
                            if (!d.success) throw new Error(d.message || 'Erro ao buscar Quest');
                            return d.quest;
                        })
                        .then(q => {
                            this.form.id = q.id;
                            this.form.name = q.name;
                            this.form.requirements = q.requirements;
                            this.form.link = q.link;
                            this.form.rewards = Array.isArray(q.rewards) ? q.rewards : [];
                            this.showModal = true;
                            this.$nextTick(() => {
                                if (this.$refs.rewardSelect && this.$refs.rewardSelect.tomselect) {
                                    this.$refs.rewardSelect.tomselect.clear();
                                }
                            });
                        })
                        .catch(e => {
                            this.errorMsg = e.message || 'Erro ao carregar dados para edição.';
                        });
                },
                deleteQuest(id) {
                    if (!confirm('Tem certeza que deseja excluir esta quest? Essa ação não pode ser desfeita!')) return;
                    this.errorMsg = '';
                    this.successMsg = '';
                    fetch(`/admin/psoul/quests/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(async response => {
                            let d = await response.json();
                            if (!response.ok || !d.success) throw new Error(d.message || 'Erro ao excluir Quest.');
                            this.successMsg = d.message;
                            setTimeout(() => {
                                location.reload();
                            }, 600);
                        })
                        .catch(e => {
                            this.errorMsg = e.message || 'Erro ao excluir Quest.';
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

        .overflow-auto {
            scrollbar-width: thin;
            scrollbar-color: #22c55e #0B2C21;
        }
    </style>
@endsection
