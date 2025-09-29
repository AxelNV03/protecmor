<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController; // ✅ Import correcto
use App\Http\Controllers\ProfeController; // ✅ Import correcto
use App\Http\Controllers\GrupoController; // ✅ Import correcto

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
require base_path('routes/camposF.php');