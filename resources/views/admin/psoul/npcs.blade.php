@extends('layouts.admin')

@section('admin-content')
    <div x-data="npcCrud()" class="w-full max-w-7xl mx-auto h-full overflow-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold title text-green-400 tracking-wider">Gerenciar NPCs</h2>
            <div class="flex gap-2 items-center">
                <input type="text" placeholder="Buscar NPC por nome ou localização..."
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
                        <th class="py-3 px-4 text-left">Localização</th>
                        <th class="py-3 px-4 text-center">Vende</th>
                        <th class="py-3 px-4 text-center">Compra</th>
                        <th class="py-3 px-4 text-center w-32">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="npc in filteredNpcs()" :key="npc.id">
                        <tr class="border-t border-green-700 hover:bg-green-950/50 transition">
                            <td class="py-3 px-4" x-text="npc.name"></td>
                            <td class="py-3 px-4" x-text="npc.localization"></td>
                            <td class="py-3 px-4 text-center group">
                                <span x-text="npc.sells?.length || 0 + ' itens'" class="underline cursor-pointer"
                                    :title="npc.sells?.map(i => i.name).join(', ') || 'Nenhum'"></span>
                            </td>
                            <td class="py-3 px-4 text-center group">
                                <span x-text="npc.buys?.length || 0 + ' itens'" class="underline cursor-pointer"
                                    :title="npc.buys?.map(i => i.name).join(', ') || 'Nenhum'"></span>
                            </td>
                            <td class="py-3 px-4 flex justify-center gap-2">
                                <button class="p-1 rounded hover:bg-green-400 hover:text-green-900 transition"
                                    @click="editNpc(npc.id)" title="Editar">
                                    <span class="material-icons text-green-200">edit</span>
                                </button>
                                <button class="p-1 rounded hover:bg-red-500 hover:text-white transition"
                                    @click="deleteNpc(npc.id)" title="Excluir">
                                    <span class="material-icons text-green-200">delete</span>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredNpcs().length === 0">
                        <td colspan="5" class="py-6 text-center text-green-400">Nenhum NPC encontrado.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Modal cadastro/edição -->
        <div x-show="showModal" x-transition
            class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center overflow-auto">
            <div @click.away="closeModal()"
                class="bg-black/95 border-4 border-green-400 rounded-2xl shadow-2xl p-10 w-full max-w-7xl mx-4 relative flex flex-col gap-4 overflow-auto">
                <button @click="closeModal()"
                    class="absolute top-4 right-4 text-3xl text-green-400 hover:text-green-600 font-extrabold">&times;</button>
                <h3 class="text-2xl font-bold text-green-400 mb-2" x-text="form.id ? 'Editar NPC' : 'Adicionar NPC'"></h3>
                <form @submit.prevent="submitNpc" autocomplete="off" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Primeira coluna: Nome e Localização -->
                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="font-bold text-green-100">Nome</label>
                            <input type="text" x-model="form.name" required
                                class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                        </div>
                        <div>
                            <label class="font-bold text-green-100">Localização</label>
                            <input type="text" x-model="form.localization" required
                                class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                        </div>
                    </div>
                    <!-- Segunda coluna: Itens que vende -->
                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="font-bold text-green-100">Itens que vende</label>
                            <select x-model="form.sells" multiple
                                class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200 tom-autocomplete"
                                style="max-height: 38em; overflow-y: auto;" x-init="$nextTick(() => new TomSelect($el, { maxOptions: 100, create: false }))">
                                <template x-for="item in items" :key="item.id">
                                    <option :value="item.id" x-text="item.name"></option>
                                </template>
                            </select>
                            <div class="mt-2 flex flex-wrap gap-2">
                                {{-- <template x-for="itemId in form.sells" :key="itemId">
                                    <span class="bg-green-900/50 text-green-300 px-2 py-1 rounded text-sm">
                                        <span x-text="itemName(itemId)"></span>
                                    </span>
                                </template> --}}
                                <div x-show="!form.sells.length" class="text-green-400 text-xs px-1">Nenhum item
                                    selecionado.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Terceira coluna: Itens que compra -->
                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="font-bold text-green-100">Itens que compra</label>
                            <select x-model="form.buys" multiple
                                class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200 tom-autocomplete"
                                style="max-height: 38em; overflow-y: auto;" x-init="$nextTick(() => new TomSelect($el, { maxOptions: 100, create: false }))">
                                <template x-for="item in items" :key="item.id">
                                    <option :value="item.id" x-text="item.name"></option>
                                </template>
                            </select>
                            <div class="mt-2 flex flex-wrap gap-2">
                                {{-- <template x-for="itemId in form.buys" :key="itemId">
                                    <span class="bg-green-900/50 text-green-300 px-2 py-1 rounded text-sm">
                                        <span x-text="itemName(itemId)"></span>
                                    </span>
                                </template> --}}
                                <div x-show="!form.buys.length" class="text-green-400 text-xs px-1">Nenhum item selecionado.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Mensagens de erro/sucesso e botões -->
                    <div class="col-span-1 md:col-span-3 flex flex-col gap-2 mt-2">
                        <div x-show="errorMsg" class="text-red-400 text-sm" x-text="errorMsg"></div>
                        <div x-show="successMsg" class="text-green-400 text-sm" x-text="successMsg"></div>
                        <div class="flex gap-3 justify-end mt-2">
                            <button type="submit"
                                class="bg-green-400 hover:bg-green-600 text-black font-bold rounded px-8 py-2 transition">
                                Salvar
                            </button>
                            <button type="button" @click="closeModal()"
                                class="bg-gray-500 hover:bg-gray-700 text-white rounded px-8 py-2 transition">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tom Select CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.default.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <!-- Material Icons CDN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Alpine.js CDN -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        function npcCrud() {
            return {
                npcs: @json($npcs ?? []),
                items: @json($items ?? []),
                search: '',
                showModal: false,
                form: {
                    id: null,
                    name: '',
                    localization: '',
                    sells: [],
                    buys: []
                },
                errorMsg: '',
                successMsg: '',
                filteredNpcs() {
                    if (!this.search) return this.npcs;
                    let term = this.search.toLowerCase();
                    return this.npcs.filter(n =>
                        (n.name && n.name.toLowerCase().includes(term)) ||
                        (n.localization && n.localization.toLowerCase().includes(term))
                    );
                },
                itemName(id) {
                    let item = this.items.find(i => i.id == id);
                    return item ? item.name : id;
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
                        name: '',
                        localization: '',
                        sells: [],
                        buys: []
                    };
                },
                submitNpc() {
                    this.errorMsg = '';
                    this.successMsg = '';
                    let payload = {
                        id: this.form.id,
                        name: this.form.name,
                        localization: this.form.localization,
                        sells: this.form.sells,
                        buys: this.form.buys
                    };
                    fetch("{{ route('admin.psoul.npcs') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify(payload),
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.successMsg = data.message;
                                // Atualiza a lista de NPCs
                                if (this.form.id) {
                                    // Editar: atualiza na lista local
                                    let idx = this.npcs.findIndex(n => n.id === this.form.id);
                                    if (idx !== -1) {
                                        this.npcs[idx].name = this.form.name;
                                        this.npcs[idx].localization = this.form.localization;
                                        // Atualize outros campos se necessário
                                    }
                                } else {
                                    // Cadastro: recarrega a página ou adiciona localmente
                                    // Para simplicidade, recarregue a página:
                                    window.location.reload();
                                    return;
                                }
                                setTimeout(() => {
                                    this.closeModal();
                                }, 1200);
                            } else {
                                this.errorMsg = data.message || 'Ocorreu um erro ao salvar o NPC.';
                            }
                        })
                        .catch(() => {
                            this.errorMsg = 'Erro na comunicação com o servidor.';
                        });
                    window.location.reload();
                },
                editNpc(id) {
                    let npc = this.npcs.find(n => n.id === id);
                    if (!npc) {
                        this.errorMsg = 'NPC não encontrado!';
                        return;
                    }
                    this.form.id = npc.id;
                    this.form.name = npc.name;
                    this.form.localization = npc.localization;
                    this.form.sells = Array.isArray(npc.sells) ? npc.sells.map(i => i.id) : [];
                    this.form.buys = Array.isArray(npc.buys) ? npc.buys.map(i => i.id) : [];
                    this.showModal = true;
                    this.errorMsg = '';
                    this.successMsg = '';
                    // Atualiza TomSelect após abrir modal
                    this.$nextTick(() => {
                        if (window.TomSelect) {
                            let sellsSelect = document.querySelector('select[x-model="form.sells"]');
                            let buysSelect = document.querySelector('select[x-model="form.buys"]');
                            if (sellsSelect && sellsSelect.tomselect) {
                                sellsSelect.tomselect.setValue(this.form.sells);
                            }
                            if (buysSelect && buysSelect.tomselect) {
                                buysSelect.tomselect.setValue(this.form.buys);
                            }
                        }
                    });
                },
                deleteNpc(id) {
                    if (!confirm('Tem certeza que deseja excluir este NPC?')) return;
                    fetch(`/admin/psoul/npcs/${id}`, {
                            method: "DELETE",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Remove da lista local sem recarregar a página
                                this.npcs = this.npcs.filter(n => n.id !== id);
                                this.successMsg = data.message;
                            } else {
                                this.errorMsg = data.message || 'Erro ao excluir NPC.';
                            }
                        })
                        .catch(() => {
                            this.errorMsg = 'Erro na comunicação com o servidor.';
                        });
                }
            }
        }
    </script>
@endsection
