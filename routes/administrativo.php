<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\PaisController;
use App\Http\Controllers\Administrativo\DepartamentoController;
use App\Http\Controllers\Administrativo\MunicipioController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('administrativo')->name('administrativo.')->group(function () {
        
        // PAÍSES
        Route::resource('paises', PaisController::class);
        
        // DEPARTAMENTOS
        Route::resource('departamentos', DepartamentoController::class);
        
        // MUNICIPIOS
        Route::resource('municipios', MunicipioController::class);
    });
});
