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

// Rotas do painel admin (protegidas por auth)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/members', [\App\Http\Controllers\MemberController::class, 'adminIndex'])->name('members');
    Route::post('/members/ajax', [\App\Http\Controllers\MemberController::class, 'storeAjax'])->name('members.ajax.store');
    Route::post('/members/ajax/{member}', [\App\Http\Controllers\MemberController::class, 'updateAjax'])->name('members.ajax.update');
    Route::delete('/members/ajax/{member}', [\App\Http\Controllers\MemberController::class, 'destroyAjax'])->name('members.ajax.destroy');
    Route::get('/members/ajax/{member}', [\App\Http\Controllers\MemberController::class, 'showAjax'])->name('members.ajax.show');
    Route::get('/psoul/pokedex', [\App\Http\Controllers\PokedexController::class, 'adminIndex'])->name('psoul.pokedex');
    Route::post('/psoul/pokedex/ajax', [\App\Http\Controllers\PokedexController::class, 'storeAjax'])->name('psoul.pokedex.ajax.store');
    Route::post('/psoul/pokedex/ajax/{pokedex}', [\App\Http\Controllers\PokedexController::class, 'updateAjax'])->name('psoul.pokedex.ajax.update');
    Route::get('/psoul/pokedex/ajax/{pokedex}', [\App\Http\Controllers\PokedexController::class, 'showAjax'])->name('psoul.pokedex.ajax.show');
    // Outras rotas do admin...
});

// Rota dashboard para pós-login Breeze/Fortify
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ROTAS DE AUTENTICAÇÃO E PERFIL (do Breeze)
// Remova ou ajuste esta rota se não for necessária
// Route::get('/admin', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Inclui as rotas de autenticação do Breeze (login, registro, etc)
require __DIR__.'/auth.php';
