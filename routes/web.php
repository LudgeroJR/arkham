<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;

// ROTAS DO PSOUL (suas páginas públicas)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::prefix('psoul')->name('psoul.')->group(function () {
    Route::get('/', fn() => view('psoul.home'))->name('home');
    Route::get('/pokedex', [\App\Http\Controllers\PokedexController::class, 'index'])->name('pokedex');
    Route::get('/pokedex/{id}/json', [\App\Http\Controllers\PokedexController::class, 'showJson'])->name('pokedex.showJson');
    Route::get('/itens', [\App\Http\Controllers\ItemController::class, 'index'])->name('itens');
    Route::get('/itens/{id}/json', [\App\Http\Controllers\ItemController::class, 'showJson'])->name('itens.json');
    Route::get('/skills', [\App\Http\Controllers\SkillController::class, 'index'])->name('skills');
    Route::get('/skills/{id}/json', [\App\Http\Controllers\SkillController::class, 'showJson'])->name('skills.json');
    Route::get('/quests', [\App\Http\Controllers\QuestController::class, 'index'])->name('quests');
});

// ROTAS DE AUTENTICAÇÃO E PERFIL (do Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Inclui as rotas de autenticação do Breeze (login, registro, etc)
require __DIR__.'/auth.php';
