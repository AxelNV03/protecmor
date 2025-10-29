<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CamposFormativoController;

Route::middleware('auth')
    ->prefix('campos')
    ->name('campos.')
    ->group(function () {

    Route::get('/data', [CamposFormativoController::class, 'data'])->name('data');
    Route::post('/', [CamposFormativoController::class, 'store'])->name('store');
    Route::put('/{campo_formativo}', [CamposFormativoController::class, 'update'])->name('update');
    Route::delete('/{campo_formativo}', [CamposFormativoController::class, 'destroy'])->name('destroy');
});