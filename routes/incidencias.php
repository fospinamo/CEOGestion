<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Incidencias\ServicioController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('incidencias')->name('incidencias.')->group(function () {
        
        // ===== RUTAS ESPECÍFICAS (SIN PARÁMETROS) - PRIMERO =====
        // Deben estar antes del resource para tener prioridad
        
        // RUTAS AJAX para carga dinámica
        Route::get('servicios/equipos-area/{area_id}', [ServicioController::class, 'getEquiposByArea'])
            ->name('servicios.equipos-area')
            ->middleware('can:servicios.ver');
        
        Route::post('servicios/crear-equipo', [ServicioController::class, 'crearEquipo'])
            ->name('servicios.crear-equipo')
            ->middleware('can:servicios.crear');
        
        Route::get('servicios/contrato-activo/{cliente_id}', [ServicioController::class, 'getContratoActivo'])
            ->name('servicios.contrato-activo')
            ->middleware('can:servicios.ver');
        
        // API para procesar texto dictado con IA (Deepseek)
        Route::post('servicios/procesar-voz', [\App\Http\Controllers\Incidencias\InformeController::class, 'procesarVozConIA'])
            ->name('servicios.procesar-voz')
            ->middleware('can:servicios.reportar');
        
        // Panel de servicios asignados (Admin/Coordinador)
        Route::get('servicios/panel', [ServicioController::class, 'adminAssignedPanel'])
            ->name('servicios.panel')
            ->middleware('can:servicios.panel-admin');
        
        // Panel del técnico (servicios del usuario logueado)
        Route::get('servicios/mi-panel', [ServicioController::class, 'technicianPanel'])
            ->name('servicios.mi-panel')
            ->middleware('can:servicios.panel-tech');
        
        // Estadísticas (Admin/Agente)
        Route::get('servicios/estadisticas', [ServicioController::class, 'estadisticas'])
            ->name('servicios.estadisticas')
            ->middleware('can:servicios.estadisticas');
        
        // ===== RUTAS PARA SERVICIOS CON PARÁMETROS =====
        
        // Resource CRUD - cada acción con su permiso
        Route::get('servicios', [ServicioController::class, 'index'])
            ->name('servicios.index')
            ->middleware('can:servicios.ver');
        Route::get('servicios/create', [ServicioController::class, 'create'])
            ->name('servicios.create')
            ->middleware('can:servicios.crear');
        Route::post('servicios', [ServicioController::class, 'store'])
            ->name('servicios.store')
            ->middleware('can:servicios.crear');
        Route::get('servicios/{servicio}', [ServicioController::class, 'show'])
            ->name('servicios.show')
            ->middleware('can:servicios.ver');
        Route::get('servicios/{servicio}/edit', [ServicioController::class, 'edit'])
            ->name('servicios.edit')
            ->middleware('can:servicios.editar');
        Route::put('servicios/{servicio}', [ServicioController::class, 'update'])
            ->name('servicios.update')
            ->middleware('can:servicios.editar');
        Route::delete('servicios/{servicio}', [ServicioController::class, 'destroy'])
            ->name('servicios.destroy')
            ->middleware('can:servicios.eliminar');
        
        // Acciones adicionales en un servicio específico
        Route::get('servicios/{servicio}/informe', [ServicioController::class, 'report'])
            ->name('servicios.report')
            ->middleware('can:servicios.reportar');
        
        Route::post('servicios/{servicio}/informe', [ServicioController::class, 'storeAttendance'])
            ->name('servicios.store-report')
            ->middleware('can:servicios.reportar');
        
        Route::get('servicios/{servicio}/asignar', [ServicioController::class, 'assign'])
            ->name('servicios.assign')
            ->middleware('can:servicios.asignar');
        
        Route::post('servicios/{servicio}/asignar', [ServicioController::class, 'storeAssign'])
            ->name('servicios.store-assign')
            ->middleware('can:servicios.asignar');
        
        Route::get('servicios/{servicio}/panel', [ServicioController::class, 'panel'])
            ->name('servicios.panel-detail')
            ->middleware('can:servicios.ver');

        Route::get('servicios/{servicio}/documentos/{documento}/ver', [ServicioController::class, 'verDocumentoAdjunto'])
            ->name('servicios.documento.ver')
            ->middleware('can:servicios.ver');

        Route::get('servicios/{servicio}/documentos/{documento}/descargar', [ServicioController::class, 'descargarDocumentoAdjunto'])
            ->name('servicios.documento.descargar')
            ->middleware('can:servicios.ver');
        
        Route::get('servicios/{servicio}/informe-pdf/descargar', [ServicioController::class, 'downloadInformePDF'])
            ->name('servicios.download-informe-pdf')
            ->middleware('can:servicios.imprimir-pdf');
        
        Route::get('servicios/{servicio}/informe-pdf/ver', [ServicioController::class, 'viewInformePDF'])
            ->name('servicios.view-informe-pdf')
            ->middleware('can:servicios.imprimir-pdf');

        // REPUESTOS INSTALADOS
        Route::post('servicios/{servicio}/repuestos', [ServicioController::class, 'storeRepuesto'])
            ->name('servicios.repuestos.store')
            ->middleware('can:servicios.reportar');
        
        Route::put('servicios/{servicio}/repuestos/{repuesto}', [ServicioController::class, 'updateRepuesto'])
            ->name('servicios.repuestos.update')
            ->middleware('can:servicios.reportar');
        
        Route::delete('servicios/{servicio}/repuestos/{repuesto}', [ServicioController::class, 'destroyRepuesto'])
            ->name('servicios.repuestos.destroy')
            ->middleware('can:servicios.reportar');
    });
});
