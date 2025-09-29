<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CamposFormativoController;

Route::middleware('auth')
    ->prefix('campos')
    ->name('campos.')
    ->group(function () {

    Route::get('/data', [CamposFormativoController::class, 'data'])->name('data');




});