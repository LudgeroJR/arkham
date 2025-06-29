<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::prefix('psoul')->name('psoul.')->group(function () {
    Route::get('/', fn() => view('psoul.home'))->name('home');
    Route::get('/pokedex', [\App\Http\Controllers\PokedexController::class, 'index'])->name('pokedex');
    Route::get('/pokedex/{id}/json', [\App\Http\Controllers\PokedexController::class, 'showJson'])->name('pokedex.showJson');
    Route::get('/itens', fn() => view('psoul.itens'))->name('itens');
    Route::get('/skills', fn() => view('psoul.skills'))->name('skills');
    Route::get('/quests', fn() => view('psoul.quests'))->name('quests');
});


