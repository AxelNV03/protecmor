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
    
    
    Route::get('/{grupo}', [GrupoController::class, 'show'])->name('show');
    // Esta ruta recibirá un array de IDs de alumnos para inscribir en un grupo
    Route::post('/{grupo}/attach-alumnos', [GrupoController::class, 'attachAlumnos'])->name('grupos.attachAlumnos'); 


    Route::post('/{grupo}/attach-alumno/{alumno}', [GrupoController::class, 'attachAlumno'])->name('attachAlumno');
    Route::post('/{grupo}/detach-alumno/{alumno}', [GrupoController::class, 'detachAlumno'])->name('detachAlumno');

});// En tu archivo de rutas