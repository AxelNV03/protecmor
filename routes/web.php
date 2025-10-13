<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController; // ✅ Import correcto
use App\Http\Controllers\ProfeController; // ✅ Import correcto
use App\Http\Controllers\GrupoController; // ✅ Import correcto
use App\Http\Controllers\EventoController; // ✅ Import correcto
use App\Http\Controllers\ClaseController; // ✅ Import correcto
use App\Http\Controllers\CalificacionController; // ✅ Import correcto


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



// Rutas de Clases
require base_path('routes/clases.php');

// Página pública de la agenda académica
Route::get('/agenda-academica', [EventoController::class, 'indexPublic'])
    ->name('eventos.public.index');

// Datos de eventos para la vista pública (puede usar la misma función de admin)
Route::get('/eventos/data', [EventoController::class, 'data'])
    ->name('eventos.public.data');

// Ruta para el catálogo público de productos
Route::get('/productos', [App\Http\Controllers\Admin\ProductoController::class, 'catalogoPublico'])
    ->name('productos.public');





Route::get('/test-clase', function() {
    // Tomamos la primera clase que exista en tu base de datos
    $clase = Clase::first();

    // Si no hay clases, nos detenemos
    if (!$clase) {
        return 'No hay clases en la base de datos para probar.';
    }

    // Intentamos cargar su relación 'grupo' y la mostramos
    dd($clase);
});