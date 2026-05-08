<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Parametros\EmpresaController;
use App\Http\Controllers\Parametros\SedeController;
use App\Http\Controllers\Parametros\ClienteController;
use App\Http\Controllers\Parametros\AreaController;
use App\Http\Controllers\Parametros\EquipoController;
use App\Http\Controllers\Parametros\TipoEquipoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ContratoController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('parametros')->name('parametros.')->group(function () {
        
        // EMPRESA
        Route::resource('empresas', EmpresaController::class);
        
        // SEDES
        Route::resource('sedes', SedeController::class);
        
        // CLIENTES
        Route::resource('clientes', ClienteController::class);
        
        // ÁREAS
        Route::resource('areas', AreaController::class);
        
        // EQUIPOS - Rutas específicas PRIMERO (antes que resource)
        Route::get('equipos/exportar/excel', [EquipoController::class, 'exportarExcel'])->name('equipos.exportar.excel');
        Route::get('equipos/exportar/pdf', [EquipoController::class, 'exportarPdf'])->name('equipos.exportar.pdf');
        Route::resource('equipos', EquipoController::class);
        
        // TIPOS DE EQUIPO
        Route::resource('tipos-equipos', TipoEquipoController::class);
        
        // CATEGORÍAS
        Route::resource('categorias', CategoriaController::class);
        
        // CONTRATOS
        Route::resource('contratos', ContratoController::class);
    
    });
});
