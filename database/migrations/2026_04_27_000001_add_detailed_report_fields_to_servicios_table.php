<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agregar campos para informe técnico detallado
     */
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            // Hora de atención
            $table->time('hora_inicio_atencion')->nullable()->after('fecha_atencion')->comment('Hora de inicio de la atención');
            $table->time('hora_fin_atencion')->nullable()->after('hora_inicio_atencion')->comment('Hora de fin de la atención');
            
            // Tipo de servicio específico del informe
            $table->enum('tipo_servicio_informe', [
                'INSTALACION',
                'MANTENIMIENTO_PREVENTIVO',
                'MANTENIMIENTO_CORRECTIVO',
                'SOPORTE',
            ])->nullable()->after('tipo_servicio')->comment('Tipo de servicio específico del informe técnico');
            
            // Campo para descripción del problema reportado (completar detalles)
            $table->text('descripcion_solicitud')->nullable()->after('descripcion_problema')->comment('Descripción detallada de la solicitud');
            
            // Diagnóstico/validación del servicio (adicional al diagnóstico existente)
            $table->text('diagnostico_validacion')->nullable()->after('diagnostico')->comment('Validación/diagnóstico del servicio');
            
            // Pendientes
            $table->text('pendientes')->nullable()->after('diagnostico_validacion')->comment('Actividades o ítems pendientes');
            
            // Observaciones (adicionales a las existentes)
            $table->text('observaciones_informe')->nullable()->after('observaciones')->comment('Observaciones adicionales del informe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn([
                'hora_inicio_atencion',
                'hora_fin_atencion',
                'tipo_servicio_informe',
                'descripcion_solicitud',
                'diagnostico_validacion',
                'pendientes',
                'observaciones_informe',
            ]);
        });
    }
};
