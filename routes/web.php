<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/members', [MemberController::class, 'index'])->name('members.index');

