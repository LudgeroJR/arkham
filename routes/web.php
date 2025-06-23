<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembroController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/membros', [MembroController::class, 'index']);
