 {{-- Menu Psoul fixo no topo da área de conteúdo --}}
 <nav class="w-full max-w-4xl mx-auto bg-gradient-to-r from-[#B81F1C] via-[#C6241D] to-[#EA7514] rounded-xl shadow-lg mb-4 sticky top-0 z-20">
    <div class="flex items-center gap-2 px-6 py-3">
        <img src="{{ asset('images/jogos/psoul/logoAnimadaPsoul.png') }}" alt="Psoul" class="h-10 drop-shadow-xl mr-3">
        {{-- Botão sanduíche para mobile --}}
        <button id="psoul-menu-toggle" class="md:hidden ml-auto text-[#F8EE9A] focus:outline-none" aria-label="Abrir menu">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <ul id="psoul-menu" class="flex gap-2 text-base font-bold items-center w-full md:flex md:static md:flex-row md:gap-6 md:w-full md:bg-transparent absolute left-0 top-full bg-gradient-to-r from-[#B81F1C] via-[#C6241D] to-[#EA7514] rounded-b-xl shadow-lg md:shadow-none md:rounded-none px-6 py-4 md:px-0 md:py-0 transition-all duration-200 z-30
            hidden">
            <li><a href="{{ route('psoul.home') }}" class="text-[#F8EE9A] hover:text-[#EDC416] transition">Home</a></li>
            <li><a href="{{ route('psoul.pokedex') }}" class="text-[#F8EE9A] hover:text-[#EDC416] transition">Pokedex</a></li>
            <li><a href="{{ route('psoul.itens') }}" class="text-[#F8EE9A] hover:text-[#EDC416] transition">Itens</a></li>
            <li><a href="{{ route('psoul.skills') }}" class="text-[#F8EE9A] hover:text-[#EDC416] transition">Skills</a></li>
            <li><a href="{{ route('psoul.quests') }}" class="text-[#F8EE9A] hover:text-[#EDC416] transition">Quests</a></li>
        </ul>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('psoul-menu-toggle');
            const menu = document.getElementById('psoul-menu');
            toggle?.addEventListener('click', function () {
                menu.classList.toggle('hidden');
            });
            // Fecha o menu ao clicar fora (opcional)
            document.addEventListener('click', function(e) {
                if (!toggle.contains(e.target) && !menu.contains(e.target) && !menu.classList.contains('md:flex')) {
                    menu.classList.add('hidden');
                }
            });
        });
    </script>
    <style>
        @media (min-width: 768px) {
            #psoul-menu { display: flex !important; position: static !important; background: none !important; box-shadow: none !important; border-radius: 0 !important; padding: 0 !important;}
        }
    </style>
</nav>