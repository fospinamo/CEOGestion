<?php

use App\Http\Controllers\Documentacion\DigitalizacionController;
use App\Http\Controllers\Documentacion\DocumentoController;
use App\Http\Controllers\Documentacion\RadicacionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::prefix('documentacion')->name('documentacion.')->group(function () {
        Route::resource('digitalizaciones', DigitalizacionController::class);
        Route::resource('documentos', DocumentoController::class);
        Route::resource('radicaciones', RadicacionController::class);
    });
});
