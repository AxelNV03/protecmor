<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfeController;

Route::middleware('auth')
    ->prefix('profesores')
    ->name('profesores.')
    ->group(function () {

    Route::get('/', [ProfeController::class, 'index'])->name('index');
    Route::post('/', [ProfeController::class, 'store'])->name('store');
    Route::get('/data', [ProfeController::class, 'data'])->name('data');
    Route::put('/{profesor}', [ProfeController::class, 'update'])->name('update');
    Route::delete('/{profesor}', [ProfeController::class, 'destroy'])->name('destroy');
});