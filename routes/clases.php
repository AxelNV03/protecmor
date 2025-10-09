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
    
    Route::put('/{grupo}', [ClaseController::class, 'update'])->name('update');
    Route::delete('/{grupo}', [ClaseController::class, 'destroy'])->name('destroy');
    
    
    Route::get('/{grupo}', [ClaseController::class, 'show'])->name('show');
    // Esta ruta recibirá un array de IDs de alumnos para inscribir en un grupo
    Route::post('/{grupo}/attach-alumnos', [ClaseController::class, 'attachAlumnos'])->name('grupos.attachAlumnos'); 


    Route::post('/{grupo}/attach-alumno/{alumno}', [ClaseController::class, 'attachAlumno'])->name('attachAlumno');
    Route::post('/{grupo}/detach-alumno/{alumno}', [ClaseController::class, 'detachAlumno'])->name('detachAlumno');

});// En tu archivo de rutas