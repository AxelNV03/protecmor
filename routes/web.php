<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;           // ✅ Import correcto
use App\Http\Controllers\ProfeController;           // ✅ Import correcto
use App\Http\Controllers\GrupoController;           // ✅ Import correcto
use App\Http\Controllers\EventoController;          // ✅ Import correcto
use App\Http\Controllers\ClaseController;           // ✅ Import correcto
use App\Http\Controllers\CalificacionController;    // ✅ Import correcto
use App\Http\Controllers\RespaldoController;        // ✅ Import correcto
<<<<<<< HEAD
use App\Http\Controllers\ProductoPublicController; // Importa el controlador público de productos
use App\Http\Controllers\ProductoController;
=======

>>>>>>> 238a1af ( crud de prodcutos incompleto)

use App\Models\Clase; // Asegúrate de importar el modelo

Route::get('/', function () {
    return view('main');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rutas de administración
require base_path('routes/admin.php');

// Rutas de alumno
require base_path('routes/alumno.php');

// Rutas de profesores
require base_path('routes/profesor.php');

// Rutas de grupos
require base_path('routes/grupos.php');

// Rutas de campos
require base_path('routes/campos.php');

// Califs
require base_path('routes/calificaciones.php');

// Rutas de productos (admin)
require base_path('routes/productos.php');



// Rutas de Clases
require base_path('routes/clases.php');

// Página pública de la agenda académica
Route::get('/agenda-academica', [EventoController::class, 'indexPublic'])
    ->name('eventos.public.index');

// Datos de eventos para la vista pública (puede usar la misma función de admin)
Route::get('/eventos/data', [EventoController::class, 'data'])
    ->name('eventos.public.data');

// Ruta para la visualización pública de productos
Route::get('/productos', [ProductoPublicController::class, 'index'])->name('productos.public.index');


Route::post('/respaldos/generar', [RespaldoController::class, 'generar'])->name('respaldos.generar');
Route::get('/respaldos/data', [RespaldoController::class, 'data'])->name('respaldos.data');
Route::get('/respaldos/descargar', [RespaldoController::class, 'descargar'])->name('respaldos.descargar');// Reemplaza la ruta de restauración anterior por esta
Route::post('/respaldos/{respaldo}/restaurar', [RespaldoController::class, 'restaurar'])->name('respaldos.restaurar');