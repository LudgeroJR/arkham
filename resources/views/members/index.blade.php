@extends('layouts.app')

@php
$shieldColors = [
    1 => 'from-gray-700 via-gray-900 to-green-400 border-green-400',    // Líder Diamante
    2 => 'from-yellow-400 via-yellow-600 to-gray-700 border-yellow-400', // Vice Ouro
    3 => 'from-slate-200 via-gray-400 to-gray-600 border-gray-300',      // Membro Prata
    4 => 'from-orange-400 via-yellow-800 to-gray-600 border-orange-400', // Em Teste Bronze
];
$shieldText = [
    1 => 'text-green-400',
    2 => 'text-yellow-400',
    3 => 'text-gray-200',
    4 => 'text-orange-300',
];
@endphp

@section('content')
<div class="container mx-auto py-12 px-4">
    <h2 class="text-4xl font-bold text-center text-green-400 mb-10 drop-shadow">Membros da Guild ARKHAM</h2>
    @foreach($roles as $role_id => $role_name)
        @if(isset($members[$role_id]) && $members[$role_id]->count())
            <div class="my-8">
                <div class="text-2xl font-bold text-center {{ $shieldText[$role_id] }} mb-4 uppercase tracking-widest">{{ $role_name }}</div>
                <div class="flex flex-wrap justify-center gap-8">
                    @foreach($members[$role_id] as $member)
                        <div class="relative flex flex-col items-center w-64">
                            <!-- Escudo estilizado -->
                            <div class="bg-gradient-to-b {{ $shieldColors[$member->role_id] }} border-4 rounded-b-3xl rounded-t-xl p-4 shadow-2xl flex flex-col items-center min-h-[340px] w-full shield-shape">
                                <div class="w-24 h-24 rounded-full border-4 border-white overflow-hidden bg-gray-800 -mt-12 mb-2 shadow-lg">
                                    <img src="{{ asset('avatars/' . $member->avatar) }}"
                                         alt="{{ $member->name }}"
                                         class="object-cover w-full h-full">
                                </div>
                                <div class="text-xl font-bold mt-1 mb-2 {{ $shieldText[$member->role_id] }}">{{ $member->name }}</div>
                                <div class="text-gray-400 text-sm mb-1 flex items-center gap-2">
                                    <svg class="inline w-5 h-5 text-indigo-400" fill="currentColor" viewBox="0 0 24 24"><path d="M20 21V19C20 17.8954 19.1046 17 18 17H6C4.89543 17 4 17.8954 4 19V21"></path><circle cx="12" cy="9" r="4"></circle></svg>
                                    {{ $member->discord }}
                                </div>
                                <div class="text-gray-200 text-sm mb-1">Jogos:</div>
                                <ul class="text-gray-200 text-xs flex flex-col items-center gap-1 mb-2">
                                    @forelse($member->games as $game)
                                        <li class="flex items-center gap-2">
                                            {{-- Ícones SVG ou FontAwesome por jogo (exemplo) --}}
                                            <span>
                                                @if($game->name == 'Psoul')
                                                    <svg class="w-4 h-4 text-purple-400" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                                                @elseif($game->name == 'Arkus')
                                                    <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><rect width="20" height="20" x="2" y="2" rx="5"/></svg>
                                                @elseif($game->name == 'Alliance')
                                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><polygon points="12,2 22,22 2,22"/></svg>
                                                @elseif($game->name == 'PokemonBR')
                                                    <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4" fill="#fff"/></svg>
                                                @endif
                                            </span>
                                            <span class="font-semibold">{{ $game->name }}</span> - <span class="font-mono text-green-300">{{ $game->nick }}</span>
                                        </li>
                                    @empty
                                        <li class="text-gray-400 italic">Sem jogos</li>
                                    @endforelse
                                </ul>
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
{{-- CSS extra para escudo --}}
<style>
.shield-shape {
    clip-path: polygon(20% 0%, 80% 0%, 100% 20%, 100% 95%, 50% 100%, 0% 95%, 0% 20%);
}
</style>
@endsection