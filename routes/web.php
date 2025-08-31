<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController; // ✅ Import correcto


Route::get('/', function () {
    return view('main');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rutas de administración
require base_path('routes/admin.php');
