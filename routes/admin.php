<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

Route::prefix('admins')->name('admin.')->group(function () {
    
    // GET /admins (Shows the list of admins)
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // POST /admins (Saves a new admin)
    Route::post('/', [AdminController::class, 'store'])->name('store');
    
    // PUT /admins/{admin} (Updates an existing admin)
    Route::put('/{admin}', [AdminController::class, 'update'])->name('update');
    
    // DELETE /admins/{admin} (Deletes an admin)
    Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('destroy');
});