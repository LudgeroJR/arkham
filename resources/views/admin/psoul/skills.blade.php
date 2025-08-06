@extends('layouts.admin')

@section('admin-content')
    <div x-data="skillCrud()" class="w-full max-w-7xl mx-auto h-full overflow-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold title text-green-400 tracking-wider">Gerenciar Skills</h2>
            <div class="flex gap-2 items-center">
                <input type="text" x-model="search" placeholder="Buscar skill por nome, categoria ou tipo..."
                    class="rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200 w-72 focus:outline-none focus:ring-2 focus:ring-green-400" />
                <button class="bg-green-400 hover:bg-green-600 text-black font-bold px-6 py-2 rounded shadow transition"
                    @click="openModal()">Adicionar</button>
            </div>
        </div>

        <!-- Lista de Skills -->
        <table class="min-w-full bg-black/80 border border-green-400 text-green-200 rounded-lg overflow-hidden">
            <thead>
                <tr>
                    <th class="py-3 px-4 text-left">Nome</th>
                    <th class="py-3 px-4 text-left">Categoria</th>
                    <th class="py-3 px-4 text-left">Tipo</th>
                    <th class="py-3 px-4 text-left">Poder</th>
                    <th class="py-3 px-4 text-left">Descrição</th>
                    <th class="py-3 px-4 text-left">Ranges</th>
                    <th class="py-3 px-4 w-32 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="skill in filteredSkills()" :key="skill.id">
                    <tr class="border-t border-green-700 hover:bg-green-950/50 transition">
                        <td class="py-3 px-4" x-text="skill.name"></td>
                        <td class="py-3 px-4" x-text="skill.category"></td>
                        <td class="py-3 px-4" x-text="skill.type_name"></td>
                        <td class="py-3 px-4" x-text="skill.power"></td>
                        <td class="py-3 px-4" x-text="skill.description"></td>
                        <td class="py-3 px-4">
                            <template x-for="range in skill.ranges" :key="range.name + Math.random()">
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
                                    <template x-if="!['Target','Frontal','Area','Gap Closed'].includes(range.name)">
                                        <span class="bg-green-700 text-green-100 rounded px-2 py-1 text-xs mr-1"
                                            x-text="range.name"></span>
                                    </template>
                                </span>
                            </template>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <!-- Aqui você pode adicionar botões de editar/excluir futuramente -->
                        </td>
                    </tr>
                </template>
                <tr x-show="skills.length === 0">
                    <td colspan="7" class="py-6 text-center text-green-400">Nenhuma Skill cadastrada ainda.</td>
                </tr>
            </tbody>
        </table>

        <!-- Modal de Cadastro -->
        <div x-show="modalOpen" style="display: none;"
            class="fixed inset-0 bg-black/80 flex items-center justify-center z-50">
            <div class="bg-gray-900 p-8 rounded-lg w-[500px] relative border border-green-600">
                <button class="absolute top-2 right-3 text-green-400 hover:text-green-200 text-xl"
                    @click="closeModal()">&times;</button>
                <h3 class="text-2xl font-bold text-green-400 mb-4">Cadastrar Skill</h3>
                <form @submit.prevent="addSkill">
                    <div class="mb-3">
                        <label class="font-bold text-green-100">Nome</label>
                        <input type="text" x-model="form.name" required
                            class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200" />
                    </div>
                    <div class="mb-3">
                        <label class="font-bold text-green-100">Categoria</label>
                        <select x-model="form.category" required
                            class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                            <option value="">Selecione...</option>
                            <option value="Physical">Physical</option>
                            <option value="Special">Special</option>
                            <option value="Status">Status</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="font-bold text-green-100">Tipo</label>
                        <select x-model="form.type_id" required
                            class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200">
                            <option value="">Selecione...</option>
                            <template x-for="type in types" :key="type.id">
                                <option :value="type.id" x-text="type.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="font-bold text-green-100">Poder</label>
                        <input type="number" x-model="form.power" required min="0"
                            class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200" />
                    </div>
                    <div class="mb-3">
                        <label class="font-bold text-green-100">Descrição</label>
                        <textarea x-model="form.description" required
                            class="w-full rounded border border-green-400 px-3 py-2 bg-gray-900 text-green-200"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="font-bold text-green-100">Ranges</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="(range, idx) in form.ranges" :key="range.id">
                                <span class="bg-green-700 text-green-100 rounded px-2 py-1 text-xs flex items-center gap-1">
                                    <span x-text="range.name"></span>
                                    <button type="button" class="ml-1 text-xs hover:text-red-400"
                                        @click="removeRange(idx)">&times;</button>
                                </span>
                            </template>
                        </div>
                        <div class="flex gap-2">
                            <select x-model="selectedRangeId"
                                class="rounded border border-green-400 px-2 py-1 bg-gray-900 text-green-200">
                                <option value="">Adicionar Range...</option>
                                <template x-for="range in ranges" :key="range.id">
                                    <option :value="range.id" x-text="range.name"></option>
                                </template>
                            </select>
                            <button type="button" class="bg-green-400 hover:bg-green-600 text-black px-3 rounded"
                                @click="addRange()" :disabled="!selectedRangeId">Adicionar</button>
                        </div>
                    </div>
                    <button type="submit"
                        class="bg-green-400 hover:bg-green-600 text-black font-bold rounded px-6 py-2 mt-4 w-full">Salvar
                        Skill</button>
                    <div x-show="errorMsg" class="text-red-400 text-sm mt-2" x-text="errorMsg"></div>
                    <div x-show="successMsg" class="text-green-400 text-sm mt-2" x-text="successMsg"></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Alpine.js CDN -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        function skillCrud() {
            return {
                skills: @json($skills),
                types: @json($types),
                ranges: @json($ranges),
                search: '',
                modalOpen: false,
                form: {
                    name: '',
                    category: '',
                    type_id: '',
                    power: '',
                    description: '',
                    ranges: []
                },
                selectedRangeId: '',
                errorMsg: '',
                successMsg: '',
                openModal() {
                    this.form = {
                        name: '',
                        category: '',
                        type_id: '',
                        power: '',
                        description: '',
                        ranges: []
                    };
                    this.selectedRangeId = '';
                    this.errorMsg = '';
                    this.successMsg = '';
                    this.modalOpen = true;
                },
                closeModal() {
                    this.modalOpen = false;
                },
                addRange() {
                    // Verifica se já foi adicionado
                    if (this.selectedRangeId && !this.form.ranges.find(r => r.id == this.selectedRangeId)) {
                        const rangeObj = this.ranges.find(r => r.id == this.selectedRangeId);
                        if (rangeObj)
                            this.form.ranges.push({
                                id: rangeObj.id,
                                name: rangeObj.name
                            });
                    }
                    this.selectedRangeId = '';
                },
                removeRange(idx) {
                    this.form.ranges.splice(idx, 1);
                },
                filteredSkills() {
                    if (!this.search) return this.skills;
                    return this.skills.filter(skill =>
                        skill.name.toLowerCase().includes(this.search.toLowerCase()) ||
                        skill.category.toLowerCase().includes(this.search.toLowerCase()) ||
                        skill.type_name.toLowerCase().includes(this.search.toLowerCase())
                    );
                },
                addSkill() {
                    this.errorMsg = '';
                    this.successMsg = '';
                    // Prepara os dados para envio
                    const payload = {
                        name: this.form.name,
                        category: this.form.category,
                        type_id: parseInt(this.form.type_id),
                        power: parseInt(this.form.power),
                        description: this.form.description,
                        ranges: this.form.ranges.map(r => parseInt(r.id))
                    };
                    fetch('{{ route('admin.psoul.skills.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    }).then(async resp => {
                        let d = await resp.json();
                        if (!resp.ok || !d.success) throw new Error(d.message || 'Erro ao salvar');
                        this.skills.push(d.skill);
                        this.successMsg = 'Skill cadastrada com sucesso!';
                        this.closeModal();
                    }).catch(e => {
                        this.errorMsg = e.message || 'Erro ao cadastrar Skill.';
                    });
                }
            }
        }
    </script>
@endsection
