<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Parametros\EmpresaController;
use App\Http\Controllers\Parametros\SedeController;
use App\Http\Controllers\Parametros\ClienteController;
use App\Http\Controllers\Parametros\AreaController;
use App\Http\Controllers\Parametros\EquipoController;
use App\Http\Controllers\Parametros\MantenimientoController;
use App\Http\Controllers\Parametros\TipoEquipoController;
use App\Http\Controllers\Parametros\MarcaController;
use App\Http\Controllers\Parametros\ProcesoController;
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
        
        // MANTENIMIENTOS PROGRAMADOS
        Route::post('mantenimientos/{mantenimiento}/realizar', [MantenimientoController::class, 'realizarMantenimiento'])->name('mantenimientos.realizar');
        Route::post('mantenimientos/{mantenimiento}/asignar-tecnico', [MantenimientoController::class, 'asignarTecnico'])->name('mantenimientos.asignar-tecnico');
        Route::post('mantenimientos/{mantenimiento}/cancelar', [MantenimientoController::class, 'cancelar'])->name('mantenimientos.cancelar');
        Route::get('mantenimientos/reportes/programados', [MantenimientoController::class, 'reporteProgramados'])->name('mantenimientos.reportes.programados');
        Route::get('mantenimientos/reportes/realizados', [MantenimientoController::class, 'reporteRealizados'])->name('mantenimientos.reportes.realizados');
        Route::get('mantenimientos/reportes/equipo/{equipo}', [MantenimientoController::class, 'reportePorEquipo'])->name('mantenimientos.reportes.por-equipo');
        Route::get('mantenimientos/reportes/tecnico/{tecnico}', [MantenimientoController::class, 'reportePorTecnico'])->name('mantenimientos.reportes.por-tecnico');
        Route::resource('mantenimientos', MantenimientoController::class);
        
        // TIPOS DE EQUIPO
        Route::resource('tipos-equipos', TipoEquipoController::class);
        
        // MARCAS
        Route::resource('marcas', MarcaController::class);
        
        // PROCESOS
        Route::resource('procesos', ProcesoController::class);
        
        // CATEGORÍAS
        Route::resource('categorias', CategoriaController::class);
        
        // CONTRATOS
        Route::resource('contratos', ContratoController::class);
    
    });
});
