<?php

Route::prefix('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.index');
    // Route::resource('admins', App\Http\Controllers\Admin\AdminController::class);
    // Route::get('/admins/data', [AdminController::class, 'index'])->name('admins.data'); // ← Nueva ruta
});