<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\PaisController;
use App\Http\Controllers\Administrativo\DepartamentoController;
use App\Http\Controllers\Administrativo\MunicipioController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('administrativo')->name('administrativo.')->group(function () {
        
        // PAÍSES
        Route::get('paises', [PaisController::class, 'index'])->name('paises.index')->middleware('can:paises.ver');
        Route::get('paises/create', [PaisController::class, 'create'])->name('paises.create')->middleware('can:paises.crear');
        Route::post('paises', [PaisController::class, 'store'])->name('paises.store')->middleware('can:paises.crear');
        Route::get('paises/{pais}', [PaisController::class, 'show'])->name('paises.show')->middleware('can:paises.ver');
        Route::get('paises/{pais}/edit', [PaisController::class, 'edit'])->name('paises.edit')->middleware('can:paises.editar');
        Route::put('paises/{pais}', [PaisController::class, 'update'])->name('paises.update')->middleware('can:paises.editar');
        Route::delete('paises/{pais}', [PaisController::class, 'destroy'])->name('paises.destroy')->middleware('can:paises.eliminar');
        
        // DEPARTAMENTOS
        Route::get('departamentos', [DepartamentoController::class, 'index'])->name('departamentos.index')->middleware('can:departamentos.ver');
        Route::get('departamentos/create', [DepartamentoController::class, 'create'])->name('departamentos.create')->middleware('can:departamentos.crear');
        Route::post('departamentos', [DepartamentoController::class, 'store'])->name('departamentos.store')->middleware('can:departamentos.crear');
        Route::get('departamentos/{departamento}', [DepartamentoController::class, 'show'])->name('departamentos.show')->middleware('can:departamentos.ver');
        Route::get('departamentos/{departamento}/edit', [DepartamentoController::class, 'edit'])->name('departamentos.edit')->middleware('can:departamentos.editar');
        Route::put('departamentos/{departamento}', [DepartamentoController::class, 'update'])->name('departamentos.update')->middleware('can:departamentos.editar');
        Route::delete('departamentos/{departamento}', [DepartamentoController::class, 'destroy'])->name('departamentos.destroy')->middleware('can:departamentos.eliminar');
        
        // MUNICIPIOS
        Route::get('municipios', [MunicipioController::class, 'index'])->name('municipios.index')->middleware('can:municipios.ver');
        Route::get('municipios/create', [MunicipioController::class, 'create'])->name('municipios.create')->middleware('can:municipios.crear');
        Route::post('municipios', [MunicipioController::class, 'store'])->name('municipios.store')->middleware('can:municipios.crear');
        Route::get('municipios/{municipio}', [MunicipioController::class, 'show'])->name('municipios.show')->middleware('can:municipios.ver');
        Route::get('municipios/{municipio}/edit', [MunicipioController::class, 'edit'])->name('municipios.edit')->middleware('can:municipios.editar');
        Route::put('municipios/{municipio}', [MunicipioController::class, 'update'])->name('municipios.update')->middleware('can:municipios.editar');
        Route::delete('municipios/{municipio}', [MunicipioController::class, 'destroy'])->name('municipios.destroy')->middleware('can:municipios.eliminar');
    });
});
