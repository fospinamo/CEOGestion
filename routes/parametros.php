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
use App\Http\Controllers\Parametros\InformeFormatoController;
use App\Http\Controllers\Parametros\CargoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ContratoController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('parametros')->name('parametros.')->group(function () {
        
        // EMPRESA
        Route::get('empresas', [EmpresaController::class, 'index'])->name('empresas.index')->middleware('can:empresas.ver');
        Route::get('empresas/create', [EmpresaController::class, 'create'])->name('empresas.create')->middleware('can:empresas.crear');
        Route::post('empresas', [EmpresaController::class, 'store'])->name('empresas.store')->middleware('can:empresas.crear');
        Route::get('empresas/{empresa}', [EmpresaController::class, 'show'])->name('empresas.show')->middleware('can:empresas.ver');
        Route::get('empresas/{empresa}/edit', [EmpresaController::class, 'edit'])->name('empresas.edit')->middleware('can:empresas.editar');
        Route::put('empresas/{empresa}', [EmpresaController::class, 'update'])->name('empresas.update')->middleware('can:empresas.editar');
        Route::delete('empresas/{empresa}', [EmpresaController::class, 'destroy'])->name('empresas.destroy')->middleware('can:empresas.eliminar');
        
        // SEDES
        Route::get('sedes', [SedeController::class, 'index'])->name('sedes.index')->middleware('can:sedes.ver');
        Route::get('sedes/create', [SedeController::class, 'create'])->name('sedes.create')->middleware('can:sedes.crear');
        Route::post('sedes', [SedeController::class, 'store'])->name('sedes.store')->middleware('can:sedes.crear');
        Route::get('sedes/{sede}', [SedeController::class, 'show'])->name('sedes.show')->middleware('can:sedes.ver');
        Route::get('sedes/{sede}/edit', [SedeController::class, 'edit'])->name('sedes.edit')->middleware('can:sedes.editar');
        Route::put('sedes/{sede}', [SedeController::class, 'update'])->name('sedes.update')->middleware('can:sedes.editar');
        Route::delete('sedes/{sede}', [SedeController::class, 'destroy'])->name('sedes.destroy')->middleware('can:sedes.eliminar');
        
        // CLIENTES
        Route::get('clientes', [ClienteController::class, 'index'])->name('clientes.index')->middleware('can:clientes.ver');
        Route::get('clientes/create', [ClienteController::class, 'create'])->name('clientes.create')->middleware('can:clientes.crear');
        Route::post('clientes', [ClienteController::class, 'store'])->name('clientes.store')->middleware('can:clientes.crear');
        Route::get('clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show')->middleware('can:clientes.ver');
        Route::get('clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit')->middleware('can:clientes.editar');
        Route::put('clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update')->middleware('can:clientes.editar');
        Route::delete('clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy')->middleware('can:clientes.eliminar');
        
        // ÁREAS
        Route::get('areas', [AreaController::class, 'index'])->name('areas.index')->middleware('can:areas.ver');
        Route::get('areas/create', [AreaController::class, 'create'])->name('areas.create')->middleware('can:areas.crear');
        Route::post('areas', [AreaController::class, 'store'])->name('areas.store')->middleware('can:areas.crear');
        Route::get('areas/{area}', [AreaController::class, 'show'])->name('areas.show')->middleware('can:areas.ver');
        Route::get('areas/{area}/edit', [AreaController::class, 'edit'])->name('areas.edit')->middleware('can:areas.editar');
        Route::put('areas/{area}', [AreaController::class, 'update'])->name('areas.update')->middleware('can:areas.editar');
        Route::delete('areas/{area}', [AreaController::class, 'destroy'])->name('areas.destroy')->middleware('can:areas.eliminar');
        
        // EQUIPOS - Rutas específicas PRIMERO (antes que resource)
        Route::get('equipos/exportar/excel', [EquipoController::class, 'exportarExcel'])->name('equipos.exportar.excel')->middleware('can:equipos.exportar');
        Route::get('equipos/exportar/pdf', [EquipoController::class, 'exportarPdf'])->name('equipos.exportar.pdf')->middleware('can:equipos.exportar');
        Route::get('equipos', [EquipoController::class, 'index'])->name('equipos.index')->middleware('can:equipos.ver');
        Route::get('equipos/create', [EquipoController::class, 'create'])->name('equipos.create')->middleware('can:equipos.crear');
        Route::post('equipos', [EquipoController::class, 'store'])->name('equipos.store')->middleware('can:equipos.crear');
        Route::get('equipos/{equipo}', [EquipoController::class, 'show'])->name('equipos.show')->middleware('can:equipos.ver');
        Route::get('equipos/{equipo}/edit', [EquipoController::class, 'edit'])->name('equipos.edit')->middleware('can:equipos.editar');
        Route::put('equipos/{equipo}', [EquipoController::class, 'update'])->name('equipos.update')->middleware('can:equipos.editar');
        Route::delete('equipos/{equipo}', [EquipoController::class, 'destroy'])->name('equipos.destroy')->middleware('can:equipos.eliminar');
        
        // MANTENIMIENTOS PROGRAMADOS
        Route::get('mantenimientos', [MantenimientoController::class, 'index'])->name('mantenimientos.index')->middleware('can:mantenimientos.ver');
        Route::get('mantenimientos/create', [MantenimientoController::class, 'create'])->name('mantenimientos.create')->middleware('can:mantenimientos.crear');
        Route::post('mantenimientos', [MantenimientoController::class, 'store'])->name('mantenimientos.store')->middleware('can:mantenimientos.crear');
        Route::get('mantenimientos/{mantenimiento}', [MantenimientoController::class, 'show'])->name('mantenimientos.show')->middleware('can:mantenimientos.ver');
        Route::get('mantenimientos/{mantenimiento}/edit', [MantenimientoController::class, 'edit'])->name('mantenimientos.edit')->middleware('can:mantenimientos.editar');
        Route::put('mantenimientos/{mantenimiento}', [MantenimientoController::class, 'update'])->name('mantenimientos.update')->middleware('can:mantenimientos.editar');
        Route::delete('mantenimientos/{mantenimiento}', [MantenimientoController::class, 'destroy'])->name('mantenimientos.destroy')->middleware('can:mantenimientos.eliminar');
        Route::post('mantenimientos/{mantenimiento}/realizar', [MantenimientoController::class, 'realizarMantenimiento'])->name('mantenimientos.realizar')->middleware('can:mantenimientos.editar');
        Route::post('mantenimientos/{mantenimiento}/asignar-tecnico', [MantenimientoController::class, 'asignarTecnico'])->name('mantenimientos.asignar-tecnico')->middleware('can:mantenimientos.editar');
        Route::post('mantenimientos/{mantenimiento}/cancelar', [MantenimientoController::class, 'cancelar'])->name('mantenimientos.cancelar')->middleware('can:mantenimientos.editar');
        Route::get('mantenimientos/reportes/programados', [MantenimientoController::class, 'reporteProgramados'])->name('mantenimientos.reportes.programados')->middleware('can:mantenimientos.ver');
        Route::get('mantenimientos/reportes/realizados', [MantenimientoController::class, 'reporteRealizados'])->name('mantenimientos.reportes.realizados')->middleware('can:mantenimientos.ver');
        Route::get('mantenimientos/reportes/equipo/{equipo}', [MantenimientoController::class, 'reportePorEquipo'])->name('mantenimientos.reportes.por-equipo')->middleware('can:mantenimientos.ver');
        Route::get('mantenimientos/reportes/tecnico/{tecnico}', [MantenimientoController::class, 'reportePorTecnico'])->name('mantenimientos.reportes.por-tecnico')->middleware('can:mantenimientos.ver');
        
        // TIPOS DE EQUIPO
        Route::get('tipos-equipos', [TipoEquipoController::class, 'index'])->name('tipos-equipos.index')->middleware('can:tipos-equipos.ver');
        Route::get('tipos-equipos/create', [TipoEquipoController::class, 'create'])->name('tipos-equipos.create')->middleware('can:tipos-equipos.crear');
        Route::post('tipos-equipos', [TipoEquipoController::class, 'store'])->name('tipos-equipos.store')->middleware('can:tipos-equipos.crear');
        Route::get('tipos-equipos/{tipo_equipo}', [TipoEquipoController::class, 'show'])->name('tipos-equipos.show')->middleware('can:tipos-equipos.ver');
        Route::get('tipos-equipos/{tipo_equipo}/edit', [TipoEquipoController::class, 'edit'])->name('tipos-equipos.edit')->middleware('can:tipos-equipos.editar');
        Route::put('tipos-equipos/{tipo_equipo}', [TipoEquipoController::class, 'update'])->name('tipos-equipos.update')->middleware('can:tipos-equipos.editar');
        Route::delete('tipos-equipos/{tipo_equipo}', [TipoEquipoController::class, 'destroy'])->name('tipos-equipos.destroy')->middleware('can:tipos-equipos.eliminar');
        
        // MARCAS
        Route::get('marcas', [MarcaController::class, 'index'])->name('marcas.index')->middleware('can:marcas.ver');
        Route::get('marcas/create', [MarcaController::class, 'create'])->name('marcas.create')->middleware('can:marcas.crear');
        Route::post('marcas', [MarcaController::class, 'store'])->name('marcas.store')->middleware('can:marcas.crear');
        Route::get('marcas/{marca}', [MarcaController::class, 'show'])->name('marcas.show')->middleware('can:marcas.ver');
        Route::get('marcas/{marca}/edit', [MarcaController::class, 'edit'])->name('marcas.edit')->middleware('can:marcas.editar');
        Route::put('marcas/{marca}', [MarcaController::class, 'update'])->name('marcas.update')->middleware('can:marcas.editar');
        Route::delete('marcas/{marca}', [MarcaController::class, 'destroy'])->name('marcas.destroy')->middleware('can:marcas.eliminar');
        
        // PROCESOS
        Route::get('procesos', [ProcesoController::class, 'index'])->name('procesos.index')->middleware('can:procesos.ver');
        Route::get('procesos/create', [ProcesoController::class, 'create'])->name('procesos.create')->middleware('can:procesos.crear');
        Route::post('procesos', [ProcesoController::class, 'store'])->name('procesos.store')->middleware('can:procesos.crear');
        Route::get('procesos/{proceso}', [ProcesoController::class, 'show'])->name('procesos.show')->middleware('can:procesos.ver');
        Route::get('procesos/{proceso}/edit', [ProcesoController::class, 'edit'])->name('procesos.edit')->middleware('can:procesos.editar');
        Route::put('procesos/{proceso}', [ProcesoController::class, 'update'])->name('procesos.update')->middleware('can:procesos.editar');
        Route::delete('procesos/{proceso}', [ProcesoController::class, 'destroy'])->name('procesos.destroy')->middleware('can:procesos.eliminar');
        
        // FORMATOS DE INFORME
        Route::get('informe-formatos', [InformeFormatoController::class, 'index'])->name('informe-formatos.index')->middleware('can:informe-formatos.ver');
        Route::get('informe-formatos/create', [InformeFormatoController::class, 'create'])->name('informe-formatos.create')->middleware('can:informe-formatos.crear');
        Route::post('informe-formatos', [InformeFormatoController::class, 'store'])->name('informe-formatos.store')->middleware('can:informe-formatos.crear');
        Route::get('informe-formatos/{informe_formato}', [InformeFormatoController::class, 'show'])->name('informe-formatos.show')->middleware('can:informe-formatos.ver');
        Route::get('informe-formatos/{informe_formato}/edit', [InformeFormatoController::class, 'edit'])->name('informe-formatos.edit')->middleware('can:informe-formatos.editar');
        Route::put('informe-formatos/{informe_formato}', [InformeFormatoController::class, 'update'])->name('informe-formatos.update')->middleware('can:informe-formatos.editar');
        Route::delete('informe-formatos/{informe_formato}', [InformeFormatoController::class, 'destroy'])->name('informe-formatos.destroy')->middleware('can:informe-formatos.eliminar');
        
        // CATEGORÍAS
        Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index')->middleware('can:categorias.ver');
        Route::get('categorias/create', [CategoriaController::class, 'create'])->name('categorias.create')->middleware('can:categorias.crear');
        Route::post('categorias', [CategoriaController::class, 'store'])->name('categorias.store')->middleware('can:categorias.crear');
        Route::get('categorias/{categoria}', [CategoriaController::class, 'show'])->name('categorias.show')->middleware('can:categorias.ver');
        Route::get('categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit')->middleware('can:categorias.editar');
        Route::put('categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update')->middleware('can:categorias.editar');
        Route::delete('categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy')->middleware('can:categorias.eliminar');
        
        // CONTRATOS
        Route::get('contratos', [ContratoController::class, 'index'])->name('contratos.index')->middleware('can:contratos.ver');
        Route::get('contratos/create', [ContratoController::class, 'create'])->name('contratos.create')->middleware('can:contratos.crear');
        Route::post('contratos', [ContratoController::class, 'store'])->name('contratos.store')->middleware('can:contratos.crear');
        Route::get('contratos/{contrato}', [ContratoController::class, 'show'])->name('contratos.show')->middleware('can:contratos.ver');
        Route::get('contratos/{contrato}/edit', [ContratoController::class, 'edit'])->name('contratos.edit')->middleware('can:contratos.editar');
        Route::put('contratos/{contrato}', [ContratoController::class, 'update'])->name('contratos.update')->middleware('can:contratos.editar');
        Route::delete('contratos/{contrato}', [ContratoController::class, 'destroy'])->name('contratos.destroy')->middleware('can:contratos.eliminar');
        
        // CARGOS
        Route::get('cargos', [CargoController::class, 'index'])->name('cargos.index')->middleware('can:cargos.ver');
        Route::get('cargos/create', [CargoController::class, 'create'])->name('cargos.create')->middleware('can:cargos.crear');
        Route::post('cargos', [CargoController::class, 'store'])->name('cargos.store')->middleware('can:cargos.crear');
        Route::get('cargos/{cargo}', [CargoController::class, 'show'])->name('cargos.show')->middleware('can:cargos.ver');
        Route::get('cargos/{cargo}/edit', [CargoController::class, 'edit'])->name('cargos.edit')->middleware('can:cargos.editar');
        Route::put('cargos/{cargo}', [CargoController::class, 'update'])->name('cargos.update')->middleware('can:cargos.editar');
        Route::delete('cargos/{cargo}', [CargoController::class, 'destroy'])->name('cargos.destroy')->middleware('can:cargos.eliminar');
    
    });
});
