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
        // Agregar campos de mantenimiento a tabla equipos
        Schema::table('equipos', function (Blueprint $table) {
            // Renombrar campos existentes si es necesario
            if (Schema::hasColumn('equipos', 'mantenimientos_por_ano')) {
                $table->renameColumn('mantenimientos_por_ano', 'mantenimientos_anuales');
            } else {
                $table->unsignedTinyInteger('mantenimientos_anuales')->default(1)->after('contrato_id')
                    ->comment('Cantidad de mantenimientos programados por año');
            }
            
            if (Schema::hasColumn('equipos', 'calibraciones_por_ano')) {
                $table->renameColumn('calibraciones_por_ano', 'calibraciones_anuales');
            } else {
                $table->unsignedTinyInteger('calibraciones_anuales')->default(0)->after('mantenimientos_anuales')
                    ->comment('Cantidad de calibraciones programadas por año');
            }
            
            // Agregar nuevos campos de fechas si no existen
            if (!Schema::hasColumn('equipos', 'fecha_ultimo_mantenimiento')) {
                $table->date('fecha_ultimo_mantenimiento')->nullable()->after('calibraciones_anuales')
                    ->comment('Fecha del último mantenimiento realizado');
            }
            
            if (!Schema::hasColumn('equipos', 'fecha_ultima_calibracion')) {
                $table->date('fecha_ultima_calibracion')->nullable()->after('fecha_ultimo_mantenimiento')
                    ->comment('Fecha de la última calibración realizada');
            }
            
            if (!Schema::hasColumn('equipos', 'proxima_fecha_mantenimiento')) {
                $table->date('proxima_fecha_mantenimiento')->nullable()->after('fecha_ultima_calibracion')
                    ->comment('Fecha del próximo mantenimiento programado (calculada)');
            }
            
            if (!Schema::hasColumn('equipos', 'proxima_fecha_calibracion')) {
                $table->date('proxima_fecha_calibracion')->nullable()->after('proxima_fecha_mantenimiento')
                    ->comment('Fecha de la próxima calibración programada (calculada)');
            }
            
            // Índices para búsquedas rápidas
            if (!Schema::hasIndex('equipos', 'equipos_mantenimientos_anuales_index')) {
                $table->index('mantenimientos_anuales');
            }
            
            if (!Schema::hasIndex('equipos', 'equipos_proxima_fecha_mantenimiento_index')) {
                $table->index('proxima_fecha_mantenimiento');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropIndex(['mantenimientos_por_ano']);
            $table->dropIndex(['proximo_mantenimiento_at']);
            $table->dropColumn([
                'mantenimientos_por_ano',
                'calibraciones_por_ano',
                'ultimo_mantenimiento_at',
                'ultimo_calibracion_at',
                'proximo_mantenimiento_at',
            ]);
        });
    }
};
