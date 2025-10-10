<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventoController; 
use App\Http\Controllers\ProductoController;

Route::middleware('auth') // <-- AÑADE ESTA LÍNEA
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    
    // GET /admins (Shows the list of admins)
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // GET /admins/data (Fetches admin data for DataTables or similar)
    Route::get('/data', [AdminController::class, 'data'])->name('data'); // <-- AÑADE ESTA LÍNEA

    // POST /admins (Saves a new admin)
    Route::post('/', [AdminController::class, 'store'])->name('store');
    
    // PUT /admins/{admin} (Updates an existing admin)
    Route::put('/{admin}', [AdminController::class, 'update'])->name('update');
    
    // DELETE /admins/{admin} (Deletes an admin)
    Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('destroy');

    // Rutas para la Gestión de Eventos
    Route::get('eventos/data', [EventoController::class, 'data'])->name('eventos.data');
    Route::resource('eventos', EventoController::class);

    // Rutas para la Gestión de Productos
    Route::get('productos/data', [ProductoController::class, 'data'])->name('productos.data'); // 🚩 NUEVA RUTA AJAX
    Route::resource('productos', ProductoController::class); 

});