<!DOCTYPE html>
<html lang="pt-br" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Guild ARKHAM</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;700&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
        }

        .title {
            font-family: 'Bebas Neue', Oswald, Arial, sans-serif;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-cover bg-center bg-no-repeat min-h-screen flex flex-col"
    style="background-image: url('{{ asset('images/GuildArkhamBg.jpg') }}');">
    <style>
        @media (max-width: 640px) {
            body {
                background-size: 295% auto !important;
            }
        }
    </style>
    {{-- Overlay escuro, só aparece se a variável $overlayOpacity estiver definida --}}
    @if (isset($overlayOpacity))
        <div class="fixed inset-0 z-0 pointer-events-none" style="background: rgba(0,0,0,{{ $overlayOpacity }});"></div>
    @endif

    <header x-data="{ open: false, jogosOpen: false }"
        class="w-full flex items-center justify-between px-6 py-4 bg-black bg-opacity-90 shadow-md fixed z-50 top-0 left-0 right-0">
        <div class="flex items-center gap-3">
            <svg viewBox="0 0 40 40" width="40" height="40" fill="none" class="text-green-400">
                <path fill="currentColor" d="M20,4 L4,36 L20,28 L36,36 Z" />
            </svg>
            <h1
                class="title text-4xl md:text-5xl text-green-400 tracking-widest drop-shadow-lg transition-all hover:scale-105 duration-300 select-none">
                ARKHAM</h1>
        </div>
        <!-- Desktop Menu -->
        <nav class="hidden md:block">
            <ul class="flex gap-6 text-lg font-semibold items-center">
                <li><a href="{{ route('home') }}" class="text-gray-200 hover:text-green-400 transition">HOME</a></li>
                <li><a href="{{ route('members.index') }}"
                        class="text-gray-200 hover:text-green-400 transition">MEMBROS</a></li>
                <!-- Submenu Jogos com Alpine -->
                <div x-data="{ jogosOpen: false }" class="relative">
                    <button type="button" class="text-gray-200 hover:text-green-400 transition flex items-center gap-1"
                        @mouseenter="jogosOpen = true" @mouseleave="jogosOpen = false" @focus="jogosOpen = true"
                        @blur="jogosOpen = false">
                        JOGOS
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': jogosOpen }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <ul x-show="jogosOpen" @mouseenter="jogosOpen = true" @mouseleave="jogosOpen = false" x-transition
                        class="absolute left-0 mt-2 bg-black bg-opacity-95 rounded-lg shadow-lg py-2 min-w-[140px] z-50">
                        <li>
                            <a href="{{ route('psoul.home') }}"
                                class="block px-4 py-2 text-gray-200 hover:bg-green-400 hover:text-black transition">Psoul</a>
                        </li>
                        <!-- Adicione mais jogos aqui -->
                    </ul>
                </div>
                <li><a href="{{ route('login') }}" class="text-gray-200 hover:text-green-400 transition">LOGIN</a></li>
            </ul>
        </nav>
        <!-- Mobile Sanduba -->
        <div class="md:hidden flex items-center">
            <button @click="open = !open"
                class="text-gray-200 focus:outline-none focus:ring-2 focus:ring-green-400 p-2 rounded transition">
                <svg x-show="!open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M4 8h16M4 16h16" stroke-width="2" stroke-linecap="round" />
                </svg>
                <svg x-show="open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </div>
        <!-- Mobile Menu -->
        <nav x-show="open" @click.away="open = false"
            class="absolute top-full left-0 w-full bg-black bg-opacity-95 py-6 px-8 shadow-lg rounded-b-lg z-40 flex flex-col gap-4 md:hidden transition-all duration-300"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4">
            <a href="{{ route('home') }}"
                class="text-gray-200 hover:text-green-400 text-lg font-semibold transition">HOME</a>
            <a href="{{ route('members.index') }}"
                class="text-gray-200 hover:text-green-400 text-lg font-semibold transition">MEMBROS</a>
            <!-- Jogos com submenu -->
            <div x-data="{ jogosOpen: false }">
                <button @click="jogosOpen = !jogosOpen"
                    class="flex items-center gap-2 text-gray-200 hover:text-green-400 text-lg font-semibold w-full transition">
                    JOGOS
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': jogosOpen }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <ul x-show="jogosOpen" x-transition class="ml-4 mt-2 flex flex-col gap-2">
                    <li>
                        <a href="{{ route('psoul.home') }}"
                            class="block px-2 py-1 text-gray-200 hover:bg-green-400 hover:text-black rounded transition">Psoul</a>
                    </li>
                    <!-- Mais jogos futuramente -->
                </ul>
            </div>
            <a href="{{ route('login') }}"
                class="text-gray-200 hover:text-green-400 text-lg font-semibold transition">LOGIN</a>
        </nav>
    </header>
    <main class="flex-1 w-full flex flex-col items-center justify-start pt-28 pb-10 relative z-10 overflow-x-auto">
        {{-- Seção de conteúdo principal --}}
        {{-- Conteúdo principal --}}
        @yield('content')
    </main>
    <footer class="w-full text-center py-4 text-gray-500 text-sm bg-black bg-opacity-80 relative z-20">
        &copy; {{ date('Y') }} Guild Arkham - <a href="https://discord.gg/QqTX6KTFUQ"
            class="text-white-600 hover:text-green-500 transition"><b>Discord</b></a>.<br>Desenvolvido por <a
            href="https://www.ludgerojunior.com.br" class="text-white-600 hover:text-green-500 transition">Ludgero
            Junior</a>
    </footer>
</body>

</html>
