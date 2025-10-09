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
    
    
    Route::get('/{clase}', [ClaseController::class, 'show'])->name('show');
    // Esta ruta recibirá un array de IDs de alumnos para inscribir en un grupo
    Route::post('/{grupo}/attach-alumnos', [ClaseController::class, 'attachAlumnos'])->name('grupos.attachAlumnos'); 



});// En tu archivo de rutas