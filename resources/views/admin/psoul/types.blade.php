@extends('layouts.admin')

@section('admin-content')
    <div x-data="typeCrud()" class="w-full max-w-7xl mx-auto h-full overflow-auto">
        <h2 class="text-3xl font-extrabold title text-green-400 tracking-wider mb-6">Gerenciar Types</h2>

        <!-- Formulário rápido para adicionar novo type -->
        <form @submit.prevent="addType" class="mb-8 flex gap-3 items-end">
            <div class="flex-1">
                <label class="font-bold text-green-100">Nome do Type</label>
                <input type="text" x-model="newType" required
                    class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200"
                    placeholder="Nome do type">
            </div>
            <button type="submit"
                class="bg-green-400 hover:bg-green-600 text-black font-bold rounded px-6 py-2 transition h-11">Adicionar</button>
        </form>

        <!-- Lista de Types já cadastrados -->
        <table class="min-w-full bg-black/80 border border-green-400 text-green-200 rounded-lg overflow-hidden">
            <thead>
                <tr>
                    <th class="py-3 px-4 text-left">Nome</th>
                    <th class="py-3 px-4 text-center w-32">Ações</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="type in types" :key="type.id">
                    <tr class="border-t border-green-700 hover:bg-green-950/50 transition">
                        <td class="py-3 px-4">
                            <span x-text="editId === type.id ? '' : type.name"></span>
                            <template x-if="editId === type.id">
                                <input type="text" x-model="editName"
                                    class="rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200" />
                            </template>
                        </td>
                        <td class="py-3 px-4 flex justify-center gap-2">
                            <template x-if="editId !== type.id">
                                <button class="p-1 rounded hover:bg-green-400 hover:text-black transition"
                                    @click="startEdit(type.id, type.name)" title="Editar">
                                    <span class="material-icons">edit</span>
                                </button>
                            </template>
                            <template x-if="editId === type.id">
                                <button class="p-1 rounded hover:bg-green-400 hover:text-black transition"
                                    @click="saveEdit(type.id)">
                                    <span class="material-icons">check</span>
                                </button>
                                <button class="p-1 rounded hover:bg-gray-500 hover:text-white transition"
                                    @click="cancelEdit">
                                    <span class="material-icons">close</span>
                                </button>
                            </template>
                            <button class="p-1 rounded hover:bg-red-500 hover:text-white transition"
                                @click="deleteType(type.id)" title="Excluir">
                                <span class="material-icons">delete</span>
                            </button>
                        </td>
                    </tr>
                </template>
                <tr x-show="types.length === 0">
                    <td colspan="2" class="py-6 text-center text-green-400">Nenhum Type cadastrado ainda.</td>
                </tr>
            </tbody>
        </table>

        <div x-show="errorMsg" class="text-red-400 text-sm mt-2" x-text="errorMsg"></div>
        <div x-show="successMsg" class="text-green-400 text-sm mt-2" x-text="successMsg"></div>
    </div>

    <!-- Material Icons CDN -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Alpine.js CDN -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        function typeCrud() {
            return {
                types: @json($types),
                newType: '',
                editId: null,
                editName: '',
                errorMsg: '',
                successMsg: '',
                addType() {
                    this.errorMsg = '';
                    this.successMsg = '';
                    fetch('{{ route('admin.psoul.types.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name: this.newType
                        })
                    }).then(async resp => {
                        let d = await resp.json();
                        if (!resp.ok || !d.success) throw new Error(d.message || 'Erro ao salvar');
                        this.types.push(d.type);
                        this.successMsg = 'Type cadastrado com sucesso!';
                        this.newType = '';
                    }).catch(e => {
                        this.errorMsg = e.message || 'Erro ao cadastrar Type.';
                    });
                },
                startEdit(id, name) {
                    this.editId = id;
                    this.editName = name;
                },
                cancelEdit() {
                    this.editId = null;
                    this.editName = '';
                },
                saveEdit(id) {
                    this.errorMsg = '';
                    this.successMsg = '';
                    fetch(`/admin/psoul/types/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name: this.editName
                        })
                    }).then(async resp => {
                        let d = await resp.json();
                        if (!resp.ok || !d.success) throw new Error(d.message || 'Erro ao salvar');
                        const idx = this.types.findIndex(t => t.id === id);
                        if (idx !== -1) this.types[idx].name = this.editName;
                        this.successMsg = 'Type atualizado com sucesso!';
                        this.cancelEdit();
                    }).catch(e => {
                        this.errorMsg = e.message || 'Erro ao atualizar Type.';
                    });
                },
                deleteType(id) {
                    if (!confirm('Tem certeza que deseja excluir este Type?')) return;
                    this.errorMsg = '';
                    this.successMsg = '';
                    fetch(`/admin/psoul/types/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(async resp => {
                        let d = await resp.json();
                        if (!resp.ok || !d.success) throw new Error(d.message || 'Erro ao excluir');
                        this.types = this.types.filter(t => t.id !== id);
                        this.successMsg = 'Type excluído com sucesso!';
                    }).catch(e => {
                        this.errorMsg = e.message || 'Erro ao excluir Type.';
                    });
                }
            }
        }
    </script>
    <style>
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
