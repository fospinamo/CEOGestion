<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Incidencias\ServicioController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('incidencias')->name('incidencias.')->group(function () {
        
        // SERVICIOS
        Route::resource('servicios', ServicioController::class);
        
        // ACCIONES ESPECIALES DE SERVICIOS
        Route::prefix('servicios')->group(function () {
            Route::get('{servicio}/informe', [ServicioController::class, 'report'])->name('servicios.report');
            Route::post('{servicio}/informe', [ServicioController::class, 'storeAttendance'])->name('servicios.store-report');
            
            Route::get('{servicio}/asignar', [ServicioController::class, 'assign'])->name('servicios.assign');
            Route::post('{servicio}/asignar', [ServicioController::class, 'storeAssign'])->name('servicios.store-assign');
            
            Route::get('{servicio}/panel', [ServicioController::class, 'panel'])->name('servicios.panel');
            
            // PDF
            Route::get('{servicio}/informe-pdf/descargar', [ServicioController::class, 'downloadInformePDF'])->name('servicios.download-informe-pdf');
            Route::get('{servicio}/informe-pdf/ver', [ServicioController::class, 'viewInformePDF'])->name('servicios.view-informe-pdf');
        });
    });
});
