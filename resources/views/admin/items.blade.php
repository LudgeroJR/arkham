@extends('layouts.admin')

@section('admin-content')
<div x-data="itemCrud()" class="w-full max-w-7xl mx-auto h-full overflow-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-extrabold title text-green-400 tracking-wider">Gerenciar Itens</h2>
        <button @click="openModal()" class="bg-green-400 hover:bg-green-600 text-black font-bold px-6 py-2 rounded shadow transition">Adicionar</button>
    </div>
    <div class="mb-4 flex justify-end">
        <input
            type="text"
            placeholder="Buscar item por nome..."
            class="rounded px-4 py-2 border border-green-400 bg-gray-900 text-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 transition"
            x-model="search"
        >
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 my-4">
        <template x-for="item in filteredItems" :key="item.id">
            <div class="bg-black/80 border border-green-400 rounded-xl p-4 flex flex-col gap-2 shadow relative">
                <div class="text-lg font-bold text-green-300 truncate" x-text="item.name"></div>
                <div class="text-green-200">
                    Preço: $<span class="font-mono text-green-400" x-text="item.price"></span>
                </div>
                <div class="flex justify-end gap-2 mt-2">
                    <button class="p-1 rounded hover:bg-green-400 hover:text-green-900 transition"
                        @click="editItem(item.id)" title="Editar">
                        <span class="material-icons text-green-200">edit</span>
                    </button>
                    <button class="p-1 rounded hover:bg-red-500 hover:text-white transition"
                        @click="deleteItem(item.id)" title="Excluir">
                        <span class="material-icons text-green-200">delete</span>
                    </button>
                </div>
            </div>
        </template>
        <template x-if="filteredItems().length === 0">
            <div class="col-span-full text-center py-8 text-green-400">Nenhum item cadastrado ainda.</div>
        </template>
    </div>

    <!-- Modal de Cadastro/Edição -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center overflow-auto">
        <div @click.away="closeModal()" class="bg-black/95 border-4 border-green-400 rounded-2xl shadow-2xl p-10 w-full max-w-5xl mx-4 relative flex flex-col gap-4 overflow-auto">
            <button @click="closeModal()" class="absolute top-4 right-4 text-3xl text-green-400 hover:text-green-600 font-extrabold">&times;</button>
            <h3 class="text-2xl font-bold text-green-400 mb-2" x-text="form.id ? 'Editar Item' : 'Adicionar Item'"></h3>
            <form @submit.prevent="submitItem" class="grid grid-cols-1 md:grid-cols-2 gap-6" autocomplete="off">
                <!-- Dados básicos -->
                <div class="flex flex-col gap-4">
                    <div>
                        <label class="font-bold text-green-100">Nome</label>
                        <input type="text" x-model="form.name" required class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                    </div>
                    <div>
                        <label class="font-bold text-green-100">Preço</label>
                        <input type="number" min="0" step="0.01" x-model="form.price" required class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                    </div>
                </div>
                <!-- Composição (materiais) -->
                <div class="flex flex-col gap-4">
                    <div class="border border-green-400 rounded-xl p-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-green-200">Composição (Para Craft)</span>
                            <button type="button" @click="addMaterial" class="text-green-400 hover:text-green-600 flex items-center gap-1">
                                <span class="material-icons text-lg">add_circle</span> Adicionar
                            </button>
                        </div>
                        <template x-for="(mat, idx) in form.materials" :key="idx">
                            <div class="flex items-center gap-2 mb-1">
                                <select x-model="mat.material_id"
                                        class="rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200 flex-1 tom-autocomplete"
                                        x-init="$nextTick(() => new TomSelect($el, {create: false, maxOptions: 100}))">
                                    <option value="">Selecione o material</option>
                                    @foreach($items as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                                <input type="number" min="1" x-model="mat.amount" class="w-16 rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200 text-center" placeholder="Qtd">
                                <button type="button" @click="removeMaterial(idx)" class="text-red-400 hover:text-red-600 text-xl font-bold px-2" title="Remover">
                                    <span class="material-icons">remove_circle</span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
                <!-- Mensagens -->
                <div class="col-span-2">
                    <div x-show="errorMsg" class="text-red-400 text-sm mt-2" x-text="errorMsg"></div>
                    <div x-show="successMsg" class="text-green-400 text-sm mt-2" x-text="successMsg"></div>
                </div>
                <div class="col-span-2 flex gap-3 justify-end">
                    <button type="submit" class="bg-green-400 hover:bg-green-600 text-black font-bold rounded px-8 py-2 transition">
                        Salvar
                    </button>
                    <button type="button" @click="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white rounded px-8 py-2 transition">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Material Icons CDN -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.default.min.css" rel="stylesheet">
<!-- Alpine.js CDN -->
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script>
function itemCrud() {
    return {
        showModal: false,
        items: @json($items),
        search: '',
        filteredItems() {
            if (!this.search) return this.items;
            return this.items.filter(item =>
                item.name.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        form: {
            id: null,
            name: '',
            price: '',
            materials: []
        },
        errorMsg: '',
        successMsg: '',
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
                id: null, name: '', price: '', materials: []
            };
        },
        addMaterial() { this.form.materials.push({ material_id: '', amount: '' }); },
        removeMaterial(idx) { this.form.materials.splice(idx, 1); },
        async submitItem() {
            this.errorMsg = '';
            this.successMsg = '';
            const isEdit = !!this.form.id;
            const url = isEdit
                ? `/admin/psoul/items/${this.form.id}`
                : "{{ route('admin.psoul.items.store') }}";
                const method = isEdit ? 'PUT' : 'POST';
            try {
                const resp = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });
                const data = await resp.json();
                if (data.success) {
                    this.successMsg = isEdit ? 'Item atualizado com sucesso!' : 'Item cadastrado com sucesso!';
                    if (isEdit) {
                        // Atualiza item na lista local
                        const idx = this.items.findIndex(i => i.id === data.item.id);
                        if (idx >= 0) this.items[idx] = data.item;
                        this.closeModal();
                    } else {
                        this.items.push(data.item);
                        this.resetForm();
                    }
                } else if (data.errors) {
                    this.errorMsg = Object.values(data.errors).join(' ');
                } else {
                    this.errorMsg = 'Erro ao cadastrar item.';
                }
            } catch (e) {
                this.errorMsg = 'Erro de conexão ou servidor.';
            }
        },

        async editItem(id) {
            this.errorMsg = '';
            this.successMsg = '';
            // Busca o item na lista local
            const item = this.items.find(i => i.id === id);
            if (!item) return;

            // Busca composições do item (materiais usados para craftar esse item)
            let materials = [];
            // Se já tem materiais na lista local, use-os; senão, requisita ao backend
            try {
                const resp = await fetch(`/admin/psoul/items/${id}/compositions`);
                if (resp.ok) {
                    materials = await resp.json();
                }
            } catch(e) {}

            // Preenche o form
            this.form = {
                id: item.id,
                name: item.name,
                price: item.price,
                materials: (materials.length ? materials : (item.materials || [])).map(mat => ({
                    material_id: mat.material_id || mat.id,
                    amount: mat.amount,
                }))
            };
            this.showModal = true;
        },

        async deleteItem(id) {
            if (!confirm('Tem certeza que deseja excluir este item? Esta ação não pode ser desfeita!')) return;
            this.errorMsg = '';
            this.successMsg = '';
            try {
                const resp = await fetch(`/admin/psoul/items/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await resp.json();
                if (data.success) {
                    this.successMsg = data.message;
                    // Remove o item da lista local para sumir da tela sem recarregar
                    this.items = this.items.filter(i => i.id !== id);
                } else {
                    this.errorMsg = data.message || 'Erro ao excluir item.';
                }
            } catch (e) {
                this.errorMsg = 'Erro de conexão ou servidor.';
            }
        }
        
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
.ts-dropdown, .ts-control, .ts-dropdown .option, .ts-dropdown .active, .ts-dropdown .selected {
    background: #161b22 !important;
    color: #a3e635 !important;
}
.ts-dropdown .option:hover, .ts-dropdown .active {
    background: #22c55e !important;
    color: #000 !important;
}
.ts-control {
    border-color: #22c55e !important;
}
</style>
@endsection