<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Incidencias\ServicioController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('incidencias')->name('incidencias.')->group(function () {
        
        // ===== RUTAS ESPECÍFICAS (SIN PARÁMETROS) - PRIMERO =====
        // Deben estar antes del resource para tener prioridad
        
        // RUTAS AJAX para carga dinámica
        Route::get('servicios/equipos-area/{area_id}', [ServicioController::class, 'getEquiposByArea'])
            ->name('servicios.equipos-area');
        
        Route::get('servicios/contrato-activo/{cliente_id}', [ServicioController::class, 'getContratoActivo'])
            ->name('servicios.contrato-activo');
        
        // Panel de servicios asignados (Admin/Coordinador)
        Route::get('servicios/panel', [ServicioController::class, 'adminAssignedPanel'])
            ->name('servicios.panel');
        
        // Panel del técnico (servicios del usuario logueado)
        Route::get('servicios/mi-panel', [ServicioController::class, 'technicianPanel'])
            ->name('servicios.mi-panel');
        
        // Estadísticas (Admin/Agente)
        Route::get('servicios/estadisticas', [ServicioController::class, 'estadisticas'])
            ->name('servicios.estadisticas')
            ->middleware('can:servicios.estadisticas');
        
        // ===== RUTAS PARA SERVICIOS CON PARÁMETROS =====
        
        // Resource CRUD
        Route::resource('servicios', ServicioController::class);
        
        // Acciones adicionales en un servicio específico
        Route::get('servicios/{servicio}/informe', [ServicioController::class, 'report'])
            ->name('servicios.report');
        
        Route::post('servicios/{servicio}/informe', [ServicioController::class, 'storeAttendance'])
            ->name('servicios.store-report');
        
        Route::get('servicios/{servicio}/asignar', [ServicioController::class, 'assign'])
            ->name('servicios.assign');
        
        Route::post('servicios/{servicio}/asignar', [ServicioController::class, 'storeAssign'])
            ->name('servicios.store-assign');
        
        Route::get('servicios/{servicio}/panel', [ServicioController::class, 'panel'])
            ->name('servicios.panel-detail');
        
        Route::get('servicios/{servicio}/informe-pdf/descargar', [ServicioController::class, 'downloadInformePDF'])
            ->name('servicios.download-informe-pdf');
        
        Route::get('servicios/{servicio}/informe-pdf/ver', [ServicioController::class, 'viewInformePDF'])
            ->name('servicios.view-informe-pdf');
    });
});

