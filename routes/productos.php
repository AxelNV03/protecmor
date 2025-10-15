<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

Route::middleware('auth') // Solo usuarios autenticados
    ->prefix('admin/productos') // Prefijo de URL
    ->name('admin.productos.') // Prefijo de nombre de ruta
    ->group(function () {

        // GET /admin/productos/data → lista de productos JSON para Alpine.js
        Route::get('data', [ProductoController::class, 'data'])->name('data');

        // GET /admin/productos → index (puede redirigir al dashboard o mostrar lista)
        Route::get('/', [ProductoController::class, 'index'])->name('index');

        // POST /admin/productos → crear producto
        Route::post('/', [ProductoController::class, 'store'])->name('store');

        // PUT /admin/productos/{producto} → actualizar producto
        Route::put('{producto}', [ProductoController::class, 'update'])->name('update');

        // DELETE /admin/productos/{producto} → eliminar producto
        Route::delete('{producto}', [ProductoController::class, 'destroy'])->name('destroy');
    });
