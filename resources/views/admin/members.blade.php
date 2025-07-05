@extends('layouts.admin')

@section('admin-content')
<div x-data="memberCrud()" class="w-full max-w-5xl mx-auto h-full overflow-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-extrabold title text-green-400 tracking-wider">Gerenciar Membros</h2>
        <button @click="openModal()" class="bg-green-400 hover:bg-green-600 text-black font-bold px-6 py-2 rounded shadow transition">Adicionar</button>
    </div>
    <div class="overflow-x-auto rounded-xl shadow">
        <table class="min-w-full bg-black/80 border border-green-400 text-green-200">
            <thead>
                <tr>
                    <th class="py-3 px-4 text-left">Nome</th>
                    <th class="py-3 px-4 text-left">Whatsapp</th>
                    <th class="py-3 px-4 text-left">Discord</th>
                    <th class="py-3 px-4 text-left">Cargo</th>
                    <th class="py-3 px-4 text-center w-32">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr class="border-t border-green-700 hover:bg-green-950/50 transition">
                        <td class="py-3 px-4 flex items-center gap-3">
                            @if($member->avatar)
                                <img src="{{ asset('images/avatars/'.$member->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border-2 border-green-400">
                            @else
                                <span class="material-icons text-green-400">person</span>
                            @endif
                            <span>{{ $member->name }}</span>
                        </td>
                        <td class="py-3 px-4">{{ $member->whatsapp ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $member->discord ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $roles[$member->role_id] ?? '-' }}</td>
                        <td class="py-3 px-4 flex justify-center gap-2">
                            <button class="p-1 rounded hover:bg-green-400 hover:text-black transition"
                                @click="editMember({{ $member->id }})" title="Editar">
                                <span class="material-icons">edit</span>
                            </button>
                            <button class="p-1 rounded hover:bg-red-500 hover:text-white transition"
                                @click="deleteMember({{ $member->id }})" title="Excluir">
                                <span class="material-icons">delete</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
                @if($members->isEmpty())
                    <tr>
                        <td colspan="5" class="py-6 text-center text-green-400">Nenhum membro cadastrado ainda.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Modal de Cadastro/Edição -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center">
        <div @click.away="closeModal()" class="bg-black/95 border-4 border-green-400 rounded-2xl shadow-2xl p-8 w-full max-w-lg mx-4 relative flex flex-col gap-4">
            <button @click="closeModal()" class="absolute top-4 right-4 text-3xl text-green-400 hover:text-green-600 font-extrabold">&times;</button>
            <h3 class="text-2xl font-bold text-green-400 mb-2" x-text="modalTitle"></h3>
            <form @submit.prevent="submitMember" class="flex flex-col gap-4" enctype="multipart/form-data" autocomplete="off">
                <div>
                    <label class="font-bold text-green-100">Nome</label>
                    <input type="text" x-model="form.name" required class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200 focus:ring-2 focus:ring-green-400">
                </div>
                <div>
                    <label class="font-bold text-green-100">Avatar</label>
                    <input type="file" @change="handleAvatar" accept="image/*" class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                    <template x-if="form.avatar_preview">
                        <img :src="form.avatar_preview" class="w-16 h-16 rounded-full mt-2 border-2 border-green-400 object-cover">
                    </template>
                </div>
                <div>
                    <label class="font-bold text-green-100">Whatsapp</label>
                    <input type="text" x-model="form.whatsapp" class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                </div>
                <div>
                    <label class="font-bold text-green-100">Discord</label>
                    <input type="text" x-model="form.discord" class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                </div>
                <div>
                    <label class="font-bold text-green-100">Cargo</label>
                    <select x-model="form.role_id" required class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                        <option value="">Selecione o cargo</option>
                        @foreach($roles as $id => $role)
                            <option value="{{ $id }}">{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 mt-4">
                    <button type="submit" class="bg-green-400 hover:bg-green-600 text-black font-bold rounded px-6 py-2 transition">
                        Salvar
                    </button>
                    <button type="button" @click="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white rounded px-6 py-2 transition">Cancelar</button>
                </div>
                <template x-if="errorMsg">
                    <div class="text-red-400 text-sm mt-2" x-text="errorMsg"></div>
                </template>
                <template x-if="successMsg">
                    <div class="text-green-400 text-sm mt-2" x-text="successMsg"></div>
                </template>
            </form>
        </div>
    </div>
</div>

<!-- Material Icons CDN -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- Alpine.js CDN -->
<script src="//unpkg.com/alpinejs" defer></script>
<script>
function memberCrud() {
    return {
        showModal: false,
        modalTitle: 'Adicionar Membro',
        form: {
            id: null,
            name: '',
            avatar: null,
            avatar_preview: null,
            whatsapp: '',
            discord: '',
            role_id: ''
        },
        errorMsg: '',
        successMsg: '',
        openModal() {
            this.resetForm();
            this.modalTitle = 'Adicionar Membro';
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.resetForm();
            this.errorMsg = '';
            this.successMsg = '';
        },
        resetForm() {
            this.form = { id: null, name: '', avatar: null, avatar_preview: null, whatsapp: '', discord: '', role_id: '' };
            this.errorMsg = '';
            this.successMsg = '';
        },
        handleAvatar(e) {
            const file = e.target.files[0];
            if (file) {
                this.form.avatar = file;
                this.form.avatar_preview = URL.createObjectURL(file);
            }
        },
        submitMember() {
            // Na próxima etapa, implementaremos o envio AJAX!
        },
        editMember(id) {
            // Na próxima etapa
        },
        deleteMember(id) {
            // Na próxima etapa
        }
    }
}
</script>

<style>
/* Estiliza a barra de rolagem do container principal */
.w-full.max-w-5xl.mx-auto.h-full.overflow-auto::-webkit-scrollbar {
    width: 10px;
    background: #0B2C21;
    border-radius: 8px;
}
.w-full.max-w-5xl.mx-auto.h-full.overflow-auto::-webkit-scrollbar-thumb {
    background: #22c55e;
    border-radius: 8px;
    border: 2px solid #0B2C21;
}
.w-full.max-w-5xl.mx-auto.h-full.overflow-auto::-webkit-scrollbar-thumb:hover {
    background: #16a34a;
}
</style>
@endsection