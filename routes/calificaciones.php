<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalificacionController;

Route::middleware('auth')
    ->prefix('calificaciones')
    ->name('calificaciones.')
    ->group(function () {

    // GET /calificaciones -> Muestra la lista de calificaciones
    // POST /calificaciones -> Guarda una nueva calificación
    
    Route::post('/', [CalificacionController::class, 'store'])->name('store');
    Route::put('/{calificacion}', [CalificacionController::class, 'update'])->name('update');


    Route::get('/mis-calificaciones', [CalificacionController::class, 'calificacionesAlumno'])->name('calificacionesAlumno');

    // Aquí podrías añadir más rutas como update, destroy, etc.
});