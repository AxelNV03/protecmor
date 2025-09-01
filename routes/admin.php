<?php

Route::prefix('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.index');
    route::post('/store', [App\Http\Controllers\Admin\AdminController::class, 'store'])->name('admin.store');
    Route::put('/update/{id}', [App\Http\Controllers\Admin\AdminController::class, 'update'])->name('admin.update');
    Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\AdminController::class, 'destroy'])->name('admin.destroy');
    // Route::resource('admins', App\Http\Controllers\Admin\AdminController::class);
    // Route::get('/admins/data', [AdminController::class, 'index'])->name('admins.data'); // ← Nueva ruta
});
