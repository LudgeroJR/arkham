@extends('layouts.app')

@php
$shieldColors = [
    1 => 'from-gray-600 via-gray-900 to-gray-800 border-green-300',    // Líder Diamante
    2 => 'from-gray-600 via-gray-900 to-gray-800 border-yellow-300', // Vice Ouro
    3 => 'from-gray-600 via-gray-900 to-gray-800 border-gray-300',      // Membro Prata
    4 => 'from-gray-600 via-gray-900 to-gray-800 border-orange-300', // Em Teste Bronze
];
$shieldborder = [
    1 => 'bg-green-300',    // Líder Diamante
    2 => 'bg-yellow-300', // Vice Ouro
    3 => 'bg-gray-300',      // Membro Prata
    4 => 'bg-orange-300', // Em Teste Bronze
];
$shieldText = [
    1 => 'text-green-400',
    2 => 'text-yellow-400',
    3 => 'text-gray-400',
    4 => 'text-orange-300',
];
@endphp

@section('content')
<div class="container mx-auto max-w-6xl py-12 px-4">
    <h2 class="text-4xl font-bold text-center text-green-400 mb-10 drop-shadow">Membros da Guild ARKHAM</h2>
    @foreach($roles as $role_id => $role_name)
        @if(isset($members[$role_id]) && $members[$role_id]->count())
            <div class="my-8">
                <div class="text-2xl font-bold text-center {{ $shieldText[$role_id] }} mb-4 uppercase tracking-widest">{{ $role_name }}</div>
                <div class="flex flex-wrap justify-center gap-8">
                    @foreach($members[$role_id] as $member)
                        <div class="relative flex flex-col items-center w-64">
                            <!-- Escudo estilizado -->
                            <div class="{{ $shieldborder[$member->role_id] }} rounded-b-3xl rounded-t-xl p-1 shadow-2xl flex flex-col items-center min-h-[340px] w-full shield-shape">
                                <div class="bg-gradient-to-b {{ $shieldColors[$member->role_id] }} rounded-b-3xl rounded-t-xl p-4 shadow-2xl flex flex-col items-center min-h-[340px] w-full shield-shape">
                                    <div class="w-24 h-24 rounded-full border-4 border-white overflow-hidden bg-gray-800 -mt-0 mb-2 shadow-lg">
                                        <img src="{{ asset('images/avatars/' . $member->avatar) }}"
                                            alt="{{ $member->name }}"
                                            class="object-cover w-full h-full">
                                    </div>
                                    <div class="text-xl font-bold mt-1 mb-2 {{ $shieldText[$member->role_id] }} text-center w-full">{{ $member->name }}</div>
                                    <div class="text-gray-400 text-sm mb-1 flex items-center gap-2">
                                        <svg class="inline w-5 h-5 text-indigo-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M20.317 4.369A19.791 19.791 0 0 0 16.885 3.1a.074.074 0 0 0-.079.037c-.34.607-.719 1.396-.984 2.025a18.524 18.524 0 0 0-5.59 0 12.51 12.51 0 0 0-.997-2.025.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.369a.069.069 0 0 0-.032.027C.533 9.09-.32 13.579.099 18.021a.082.082 0 0 0 .031.056c2.104 1.548 4.145 2.489 6.13 3.13a.077.077 0 0 0 .084-.027c.472-.65.893-1.34 1.248-2.065a.076.076 0 0 0-.041-.104c-.669-.253-1.304-.558-1.917-.892a.077.077 0 0 1-.008-.128c.129-.098.258-.2.381-.304a.074.074 0 0 1 .077-.01c4.014 1.83 8.36 1.83 12.331 0a.075.075 0 0 1 .078.009c.123.104.252.206.382.304a.077.077 0 0 1-.006.128 12.298 12.298 0 0 1-1.918.892.076.076 0 0 0-.04.105c.36.724.782 1.414 1.247 2.064a.076.076 0 0 0 .084.028c1.987-.641 4.028-1.582 6.132-3.13a.077.077 0 0 0 .03-.055c.5-5.177-.838-9.637-3.548-13.625a.061.061 0 0 0-.03-.028zM8.02 15.331c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.418 2.157-2.418 1.21 0 2.175 1.094 2.157 2.418 0 1.334-.955 2.419-2.157 2.419zm7.974 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.418 2.157-2.418 1.21 0 2.175 1.094 2.157 2.418 0 1.334-.947 2.419-2.157 2.419z"/>
                                        </svg>
                                        
                                        {{ $member->discord }} 
                                    </div>
                                    <div class="text-gray-200 text-sm mb-1">Jogos:</div>
                                    <ul class="text-gray-200 text-xs flex flex-col items-center gap-1 mb-2">
                                        @forelse($member->games as $game)
                                            <li class="flex items-center gap-2">
                                                <span>
                                                    <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4" fill="#fff"/></svg>
                                                </span>
                                                <span class="font-semibold">{{ $game->name }}</span> - <span class="font-mono text-green-300">{{ $game->nick }}</span>
                                            </li>
                                        @empty
                                            <li class="text-gray-400 italic">Sem jogos</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @if($role_id !== 4)
                <div class="w-full border-t border-gray-600 my-10"></div>
            @endif
        @endif
    @endforeach
</div>
<style>
.shield-shape {
    clip-path: polygon(20% 0%, 80% 0%, 100% 20%, 100% 95%, 50% 100%, 0% 95%, 0% 20%);
}
</style>
@endsection