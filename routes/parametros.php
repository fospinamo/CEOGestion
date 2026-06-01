<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Parametros\EmpresaController;
use App\Http\Controllers\Parametros\SedeController;
use App\Http\Controllers\Parametros\ClienteController;
use App\Http\Controllers\Parametros\AreaController;
use App\Http\Controllers\Parametros\EquipoController;
use App\Http\Controllers\Parametros\EquipoDocumentoController;
use App\Http\Controllers\Parametros\MantenimientoCalibrationController;
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
        
        // DOCUMENTOS DEL EQUIPO
        Route::get('equipos/{equipo}/documentos', [EquipoDocumentoController::class, 'index'])->name('equipos.documentos.index');
        Route::get('equipos/{equipo}/documentos/crear', [EquipoDocumentoController::class, 'create'])->name('equipos.documentos.create');
        Route::post('equipos/{equipo}/documentos', [EquipoDocumentoController::class, 'store'])->name('equipos.documentos.store');
        Route::get('equipos/{equipo}/documentos/{documento}/descargar', [EquipoDocumentoController::class, 'download'])->name('equipos.documentos.download');
        Route::delete('equipos/{equipo}/documentos/{documento}', [EquipoDocumentoController::class, 'destroy'])->name('equipos.documentos.destroy');
        
        // MANTENIMIENTOS Y CALIBRACIONES DEL EQUIPO
        Route::get('equipos/{equipo}/mantenimientos', [MantenimientoCalibrationController::class, 'index'])->name('equipos.mantenimientos.index');
        Route::get('equipos/{equipo}/mantenimientos/crear', [MantenimientoCalibrationController::class, 'create'])->name('equipos.mantenimientos.create');
        Route::post('equipos/{equipo}/mantenimientos', [MantenimientoCalibrationController::class, 'store'])->name('equipos.mantenimientos.store');
        Route::get('equipos/{equipo}/mantenimientos/{mantenimiento}/registrar', [MantenimientoCalibrationController::class, 'registrarRealizacion'])->name('equipos.mantenimientos.registrar');
        Route::put('equipos/{equipo}/mantenimientos/{mantenimiento}', [MantenimientoCalibrationController::class, 'guardarRealizacion'])->name('equipos.mantenimientos.guardarRealizacion');
        Route::get('equipos/{equipo}/mantenimientos/{mantenimiento}/descargar', [MantenimientoCalibrationController::class, 'descargarReporte'])->name('equipos.mantenimientos.descargar');
        Route::delete('equipos/{equipo}/mantenimientos/{mantenimiento}', [MantenimientoCalibrationController::class, 'destroy'])->name('equipos.mantenimientos.destroy');
        
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
        
        // CATEGORÍAS
        Route::resource('categorias', CategoriaController::class);
        
        // CONTRATOS
        Route::resource('contratos', ContratoController::class);

        // PROCESOS
        Route::resource('procesos', ProcesoController::class);
    
    });
});
