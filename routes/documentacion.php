<?php

use App\Http\Controllers\Documentacion\ClaseDocumentalController;
use App\Http\Controllers\Documentacion\DigitalizacionController;
use App\Http\Controllers\Documentacion\DocumentoController;
use App\Http\Controllers\Documentacion\RadicacionController;
use App\Http\Controllers\Documentacion\TablaRetencionDocumentalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::prefix('documentacion')->name('documentacion.')->group(function () {
        
        // DIGITALIZACIONES
        Route::get('digitalizaciones', [DigitalizacionController::class, 'index'])->name('digitalizaciones.index')->middleware('can:digitalizaciones.ver');
        Route::get('digitalizaciones/create', [DigitalizacionController::class, 'create'])->name('digitalizaciones.create')->middleware('can:digitalizaciones.crear');
        Route::post('digitalizaciones', [DigitalizacionController::class, 'store'])->name('digitalizaciones.store')->middleware('can:digitalizaciones.crear');
        Route::get('digitalizaciones/{digitalizacion}', [DigitalizacionController::class, 'show'])->name('digitalizaciones.show')->middleware('can:digitalizaciones.ver');
        Route::get('digitalizaciones/{digitalizacion}/edit', [DigitalizacionController::class, 'edit'])->name('digitalizaciones.edit')->middleware('can:digitalizaciones.editar');
        Route::put('digitalizaciones/{digitalizacion}', [DigitalizacionController::class, 'update'])->name('digitalizaciones.update')->middleware('can:digitalizaciones.editar');
        Route::delete('digitalizaciones/{digitalizacion}', [DigitalizacionController::class, 'destroy'])->name('digitalizaciones.destroy')->middleware('can:digitalizaciones.eliminar');
        
        // DOCUMENTOS
        Route::get('documentos', [DocumentoController::class, 'index'])->name('documentos.index')->middleware('can:documentos.ver');
        Route::get('documentos/create', [DocumentoController::class, 'create'])->name('documentos.create')->middleware('can:documentos.crear');
        Route::post('documentos', [DocumentoController::class, 'store'])->name('documentos.store')->middleware('can:documentos.crear');
        Route::get('documentos/{documento}', [DocumentoController::class, 'show'])->name('documentos.show')->middleware('can:documentos.ver');
        Route::get('documentos/{documento}/edit', [DocumentoController::class, 'edit'])->name('documentos.edit')->middleware('can:documentos.editar');
        Route::put('documentos/{documento}', [DocumentoController::class, 'update'])->name('documentos.update')->middleware('can:documentos.editar');
        Route::delete('documentos/{documento}', [DocumentoController::class, 'destroy'])->name('documentos.destroy')->middleware('can:documentos.eliminar');
        
        // RADICACIONES
        Route::get('radicaciones', [RadicacionController::class, 'index'])->name('radicaciones.index')->middleware('can:radicaciones.ver');
        Route::get('radicaciones/create', [RadicacionController::class, 'create'])->name('radicaciones.create')->middleware('can:radicaciones.crear');
        Route::post('radicaciones', [RadicacionController::class, 'store'])->name('radicaciones.store')->middleware('can:radicaciones.crear');
        Route::get('radicaciones/{radicacion}', [RadicacionController::class, 'show'])->name('radicaciones.show')->middleware('can:radicaciones.ver');
        Route::get('radicaciones/{radicacion}/edit', [RadicacionController::class, 'edit'])->name('radicaciones.edit')->middleware('can:radicaciones.editar');
        Route::put('radicaciones/{radicacion}', [RadicacionController::class, 'update'])->name('radicaciones.update')->middleware('can:radicaciones.editar');
        Route::delete('radicaciones/{radicacion}', [RadicacionController::class, 'destroy'])->name('radicaciones.destroy')->middleware('can:radicaciones.eliminar');

        // CLASES DOCUMENTALES (Parametros)
        Route::get('parametros/clases-documentales', [ClaseDocumentalController::class, 'index'])->name('parametros.clases_documentales.index')->middleware('can:clases_documentales.ver');
        Route::get('parametros/clases-documentales/create', [ClaseDocumentalController::class, 'create'])->name('parametros.clases_documentales.create')->middleware('can:clases_documentales.crear');
        Route::post('parametros/clases-documentales', [ClaseDocumentalController::class, 'store'])->name('parametros.clases_documentales.store')->middleware('can:clases_documentales.crear');
        Route::get('parametros/clases-documentales/{clase_documental}', [ClaseDocumentalController::class, 'show'])->name('parametros.clases_documentales.show')->middleware('can:clases_documentales.ver');
        Route::get('parametros/clases-documentales/{clase_documental}/edit', [ClaseDocumentalController::class, 'edit'])->name('parametros.clases_documentales.edit')->middleware('can:clases_documentales.editar');
        Route::put('parametros/clases-documentales/{clase_documental}', [ClaseDocumentalController::class, 'update'])->name('parametros.clases_documentales.update')->middleware('can:clases_documentales.editar');
        Route::delete('parametros/clases-documentales/{clase_documental}', [ClaseDocumentalController::class, 'destroy'])->name('parametros.clases_documentales.destroy')->middleware('can:clases_documentales.eliminar');

        // TABLAS DE RETENCION DOCUMENTAL
        Route::get('tablas-retencion', [TablaRetencionDocumentalController::class, 'index'])->name('tablas_retencion.index')->middleware('can:trd.ver');
        Route::get('tablas-retencion/create', [TablaRetencionDocumentalController::class, 'create'])->name('tablas_retencion.create')->middleware('can:trd.crear');
        Route::post('tablas-retencion', [TablaRetencionDocumentalController::class, 'store'])->name('tablas_retencion.store')->middleware('can:trd.crear');
        Route::get('tablas-retencion/{tabla_retencion}', [TablaRetencionDocumentalController::class, 'show'])->name('tablas_retencion.show')->middleware('can:trd.ver');
        Route::get('tablas-retencion/{tabla_retencion}/edit', [TablaRetencionDocumentalController::class, 'edit'])->name('tablas_retencion.edit')->middleware('can:trd.editar');
        Route::put('tablas-retencion/{tabla_retencion}', [TablaRetencionDocumentalController::class, 'update'])->name('tablas_retencion.update')->middleware('can:trd.editar');
        Route::delete('tablas-retencion/{tabla_retencion}', [TablaRetencionDocumentalController::class, 'destroy'])->name('tablas_retencion.destroy')->middleware('can:trd.eliminar');
    });
});
