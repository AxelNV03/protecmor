<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Alumno\AlumnoController;

Route::prefix('alumnos')->name('alumnos.')->group(function () {
    
    // GET /alumnos  (Muestra la lista)
    Route::get('/', [AlumnoController::class, 'index'])->name('index');

    // POST /alumnos (Guarda un nuevo alumno)
    Route::post('/', [AlumnoController::class, 'store'])->name('store');
    
    // PUT /alumnos/{alumno} (Actualiza un alumno existente)
    Route::put('/{alumno}', [AlumnoController::class, 'update'])->name('update');
    
    // DELETE /alumnos/{alumno} (Elimina un alumno)
    Route::delete('/{alumno}', [AlumnoController::class, 'destroy'])->name('destroy');
});