<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar la columna enum para agregar 'INFORME_TECNICO'
        Schema::table('seguimientos_servicios', function (Blueprint $table) {
            $table->enum('accion', [
                'CREACION', 'ASIGNACION', 'INICIO_ATENCION', 'DIAGNOSTICO', 
                'SOLUCION', 'CAMBIO_ESTADO', 'SOLICITUD_REPUESTO', 'CIERRE', 'CALIFICACION', 'INFORME_TECNICO'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seguimientos_servicios', function (Blueprint $table) {
            $table->enum('accion', [
                'CREACION', 'ASIGNACION', 'INICIO_ATENCION', 'DIAGNOSTICO', 
                'SOLUCION', 'CAMBIO_ESTADO', 'SOLICITUD_REPUESTO', 'CIERRE', 'CALIFICACION'
            ])->change();
        });
    }
};
