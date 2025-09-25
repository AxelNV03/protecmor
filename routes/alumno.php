<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;

Route::middleware('auth')
    ->prefix('alumnos')
    ->name('alumnos.')
    ->group(function () {
    // GET /alumnos  (Manda al dashboard de alumnos)
    Route::get('/', [AlumnoController::class, 'index'])->name('index');

    // GET /alumnos/data (Obtiene los datos de los alumnos en formato JSON)
    Route::get('/data', [AlumnoController::class, 'data'])->name('data');

    // POST /alumnos (Guarda un nuevo alumno)
    Route::post('/', [AlumnoController::class, 'store'])->name('store');
    
    // PUT /alumnos/{alumno} (Actualiza un alumno existente)
    Route::put('/{alumno}', [AlumnoController::class, 'update'])->name('update');    

    // DELETE /alumnos/{alumno} (Elimina un alumno)
    Route::delete('/{alumno}', [AlumnoController::class, 'destroy'])->name('destroy');
});