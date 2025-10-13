<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClaseController;

Route::middleware('auth')
    ->prefix('clases')
    ->name('clases.')
    ->group(function () {

    Route::get('/', [ClaseController::class, 'index'])->name('index');
    
    Route::post('/', [ClaseController::class, 'store'])->name('store');
    Route::get('/data', [ClaseController::class, 'data'])->name('data');    
    Route::put('/{clase}', [ClaseController::class, 'update'])->name('update');
    Route::delete('/{clase}', [ClaseController::class, 'destroy'])->name('destroy');
    
    Route::get('/{clase}/panelCalificaciones', [ClaseController::class, 'panelCalificaciones'])->name('panelCalificaciones');
    
    Route::get('/{clase}', [ClaseController::class, 'show'])->name('show');
});// En tu archivo de rutas