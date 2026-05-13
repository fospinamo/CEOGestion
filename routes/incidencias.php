<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Incidencias\ServicioController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('incidencias')->name('incidencias.')->group(function () {
        
        // RUTAS AJAX PARA CARGA DINÁMICA (ANTES del resource para evitar conflictos de routing)
        Route::get('servicios/equipos-area/{area_id}', [ServicioController::class, 'getEquiposByArea'])->name('servicios.equipos-area');
        Route::get('servicios/contrato-activo/{cliente_id}', [ServicioController::class, 'getContratoActivo'])->name('servicios.contrato-activo');
        Route::get('servicios/panel', [ServicioController::class, 'adminAssignedPanel'])->name('servicios.panel');
        Route::get('servicios/estadisticas', [ServicioController::class, 'estadisticas'])
            ->name('servicios.estadisticas')
            ->middleware('can:servicios.estadisticas');
        Route::get('servicios-panel/tecnico', [ServicioController::class, 'technicianPanel'])->name('servicios.technician-panel');
        
        // SERVICIOS RESOURCE (después de rutas específicas)
        Route::resource('servicios', ServicioController::class);
        
        // ACCIONES ESPECIALES DE SERVICIOS (requieren parámetro {servicio})
        Route::prefix('servicios')->group(function () {
            Route::get('{servicio}/informe', [ServicioController::class, 'report'])->name('servicios.report');
            Route::post('{servicio}/informe', [ServicioController::class, 'storeAttendance'])->name('servicios.store-report');
            
            Route::get('{servicio}/asignar', [ServicioController::class, 'assign'])->name('servicios.assign');
            Route::post('{servicio}/asignar', [ServicioController::class, 'storeAssign'])->name('servicios.store-assign');
            
            Route::get('{servicio}/panel', [ServicioController::class, 'panel'])->name('servicios.panel-detail');
            
            // PDF
            Route::get('{servicio}/informe-pdf/descargar', [ServicioController::class, 'downloadInformePDF'])->name('servicios.download-informe-pdf');
            Route::get('{servicio}/informe-pdf/ver', [ServicioController::class, 'viewInformePDF'])->name('servicios.view-informe-pdf');
        });
    });
});
