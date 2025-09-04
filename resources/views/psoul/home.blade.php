@extends('layouts.app')

@section('content')
    @include('partials.psoul-menu')
    <div
        class="bg-white/80 rounded-2xl shadow-2xl p-4 max-w-4xl mx-auto min-h-[440px] flex flex-col items-center relative overflow-x-auto">
        <img src="{{ asset('images/jogos/psoul/logoAnimadaPsoul.png') }}" alt="Logo Psoul"
            class="h-16 mb-4 drop-shadow-2xl animate-bounce-slow">

        <h1 class="text-3xl md:text-4xl font-extrabold text-[#B81F1C] text-center mb-2 tracking-tight drop-shadow"
            style="font-family: 'Bebas Neue', Oswald, Arial, sans-serif;">ARKHAM + Psoul<br>Mais que uma parceria, uma
            jornada!</h1>

        <div class="w-full flex flex-col md:flex-row gap-4 justify-center items-stretch mb-4">
            {{-- Crie sua Conta --}}
            <div
                class="flex-1 bg-gradient-to-br from-[#B81F1C] to-[#C6241D] rounded-xl shadow-lg p-4 flex flex-col items-center text-center min-w-[180px]">
                <h2 class="text-xl font-bold text-[#F8EE9A] mb-1">Novo no Psoul?</h2>
                <p class="text-[#F6E160] mb-2 text-sm">Crie sua conta agora mesmo!</p>
                <a href="https://psoul.gg/pt/r/21000" target="_blank" rel="noopener noreferrer"
                    class="inline-block px-4 py-2 bg-[#EDC416] text-[#B81F1C] font-bold rounded-full shadow-md hover:bg-[#F6E160] hover:text-[#680F0F] transition text-sm">
                    Criar Conta
                </a>
            </div>
            {{-- Discord Psoul --}}
            <div
                class="flex-1 bg-gradient-to-br from-[#EA7514] to-[#C6241D] rounded-xl shadow-lg p-4 flex flex-col items-center text-center min-w-[180px]">
                <h2 class="text-xl font-bold text-[#F8EE9A] mb-1">Comunidade</h2>
                <p class="text-[#F6E160] mb-2 text-sm">Junte-se ao Discord oficial do Psoul!</p>
                <a href="https://discord.gg/psoul" target="_blank" rel="noopener noreferrer"
                    class="whitespace-nowrap px-4 py-2 bg-[#EDC416] text-[#B81F1C] font-bold rounded-full shadow-md hover:bg-[#F6E160] hover:text-[#680F0F] transition flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5 text-[#B81F1C]" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M20.317 4.369a19.791 19.791 0 00-4.885-1.515.07.07 0 00-.074.035c-.21.372-.444.858-.608 1.245-1.844-.276-3.68-.276-5.486 0-.163-.401-.413-.873-.624-1.245a.07.07 0 00-.073-.035A19.736 19.736 0 003.68 4.369a.064.064 0 00-.03.027C.533 9.09-.32 13.578.099 18.015a.082.082 0 00.031.056c2.052 1.51 4.053 2.42 6.032 3.029a.077.077 0 00.084-.027c.464-.635.877-1.304 1.226-2.013a.076.076 0 00-.041-.104c-.662-.251-1.297-.552-1.918-.892a.076.076 0 01-.008-.128c.129-.097.259-.199.382-.302a.07.07 0 01.071-.01c4.016 1.836 8.374 1.836 12.346 0a.07.07 0 01.073.009c.123.103.253.205.383.302a.076.076 0 01-.007.128c-.621.34-1.256.641-1.918.893a.076.076 0 00-.041.103c.36.709.773 1.378 1.227 2.013a.077.077 0 00.084.028c1.979-.609 3.98-1.52 6.032-3.03a.077.077 0 00.031-.055c.5-5.177-.838-9.637-3.549-13.619a.061.061 0 00-.028-.028zM8.02 15.331c-1.183 0-2.156-1.085-2.156-2.419 0-1.333.955-2.418 2.156-2.418 1.21 0 2.174 1.095 2.156 2.419 0 1.333-.955 2.418-2.156 2.418zm7.974 0c-1.183 0-2.156-1.085-2.156-2.419 0-1.333.955-2.418 2.156-2.418 1.21 0 2.174 1.095 2.156 2.419 0 1.333-.946 2.418-2.156 2.418Z" />
                    </svg>
                    Discord Psoul
                </a>
            </div>
            {{-- Up Guide --}}
            <div
                class="flex-1 bg-gradient-to-br from-[#EDC416] to-[#F8EE9A] rounded-xl shadow-lg p-4 flex flex-col items-center text-center min-w-[180px]">
                <h2 class="text-xl font-bold text-[#B81F1C] mb-1">Guia de UP</h2>
                <p class="text-[#C6241D] mb-2 text-sm">Descubra onde upar cada Pokémon!</p>
                <a href="https://psoul.gg/upguide/" target="_blank" rel="noopener noreferrer"
                    class="inline-block px-4 py-2 bg-[#B81F1C] text-[#F8EE9A] font-bold rounded-full shadow-md hover:bg-[#EA7514] hover:text-[#EDC416] transition text-sm">
                    Acessar Up Guide
                </a>
            </div>
        </div>
        {{-- Instagram Psoul --}}
        <div class="w-full flex flex-col md:flex-row gap-4 justify-center items-stretch mb-4">
            <div
                class="flex-1 bg-gradient-to-br from-[#F6E160] to-[#EDC416] rounded-xl shadow-lg p-4 flex flex-col items-center text-center min-w-[180px]">
                <h2 class="text-xl font-bold text-[#B81F1C] mb-1">Siga no Instagram</h2>
                <p class="text-[#C6241D] mb-2 text-sm">Conteúdos, novidades, eventos e mais!</p>
                <a href="https://www.instagram.com/psoul_mmo/" target="_blank" rel="noopener noreferrer"
                    class="whitespace-nowrap px-4 py-2 bg-[#B81F1C] text-[#F8EE9A] font-bold rounded-full shadow-md hover:bg-[#EA7514] hover:text-[#EDC416] transition flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 448 512">
                        <path
                            d="M224.1 141c-63.6 0-115 51.4-115 115 0 63.6 51.4 115 115 115 63.6 0 115-51.4 115-115 0-63.6-51.4-115-115-115zm0 190c-41.4 0-75-33.6-75-75 0-41.4 33.6-75 75-75 41.4 0 75 33.6 75 75 0 41.4-33.6 75-75 75zm146.4-194.3c0 14.9-12.1 27-27 27s-27-12.1-27-27 12.1-27 27-27 27 12.1 27 27zm76.1 27.2c-1.7-35.3-9.9-66.7-36.2-92.9C385.8 9.9 354.4 1.7 319.1 0 280.3-1.8 167.7-1.8 128.9 0 93.6 1.7 62.2 9.9 36 36.2 9.9 62.2 1.7 93.6 0 128.9c-1.8 38.8-1.8 151.4 0 190.2 1.7 35.3 9.9 66.7 36.2 92.9 26.1 26.1 57.5 34.4 92.9 36.2 38.8 1.8 151.4 1.8 190.2 0 35.3-1.7 66.7-9.9 92.9-36.2 26.1-26.1 34.4-57.5 36.2-92.9 1.8-38.8 1.8-151.4 0-190.2zM398.8 388c-7.8 19.5-23 34.7-42.5 42.5-29.4 11.7-99.2 9-132.3 9s-102.9 2.6-132.3-9c-19.5-7.8-34.7-23-42.5-42.5-11.7-29.4-9-99.2-9-132.3s-2.6-102.9 9-132.3c7.8-19.5 23-34.7 42.5-42.5 29.4-11.7 99.2-9 132.3-9s102.9-2.6 132.3 9c19.5 7.8 34.7 23 42.5 42.5 11.7 29.4 9 99.2 9 132.3s2.6 102.9-9 132.3z" />
                    </svg>
                    Instagram Psoul
                </a>
            </div>
        </div>
        <div class="text-center mt-4">
            <span class="text-[#C6241D] text-base font-semibold">Explore o menu acima para acessar tudo que a Arkham tem a
                oferecer sobre o Psoul!</span>
        </div>
        <style>
            @keyframes bounce-slow {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-12px);
                }
            }

            .animate-bounce-slow {
                animation: bounce-slow 2.5s infinite;
            }
        </style>
    </div>
@endsection
