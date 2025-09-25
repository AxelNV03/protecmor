<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrupoController;

Route::middleware('auth')
    ->prefix('grupos')
    ->name('grupos.')
    ->group(function () {

    Route::get('/', [GrupoController::class, 'index'])->name('index');
    Route::post('/', [GrupoController::class, 'store'])->name('store');
    Route::get('/data', [GrupoController::class, 'data'])->name('data');
    Route::put('/{grupo}', [GrupoController::class, 'update'])->name('update');
    Route::delete('/{grupo}', [GrupoController::class, 'destroy'])->name('destroy');
});