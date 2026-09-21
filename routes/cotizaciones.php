<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CotizacionController;

Route::middleware(['auth'])->group(function () {
    // COTIZACIONES - rutas específicas primero
    Route::get('cotizaciones', [CotizacionController::class, 'index'])->name('cotizaciones.index')->middleware('can:cotizaciones.ver');
    Route::get('cotizaciones/create', [CotizacionController::class, 'create'])->name('cotizaciones.create')->middleware('can:cotizaciones.crear');
    Route::post('cotizaciones', [CotizacionController::class, 'store'])->name('cotizaciones.store')->middleware('can:cotizaciones.crear');
    Route::get('cotizaciones/{cotizacion}', [CotizacionController::class, 'show'])->name('cotizaciones.show')->middleware('can:cotizaciones.ver');
    Route::get('cotizaciones/{cotizacion}/edit', [CotizacionController::class, 'edit'])->name('cotizaciones.edit')->middleware('can:cotizaciones.editar');
    Route::put('cotizaciones/{cotizacion}', [CotizacionController::class, 'update'])->name('cotizaciones.update')->middleware('can:cotizaciones.editar');
    Route::delete('cotizaciones/{cotizacion}', [CotizacionController::class, 'destroy'])->name('cotizaciones.destroy')->middleware('can:cotizaciones.eliminar');
});
