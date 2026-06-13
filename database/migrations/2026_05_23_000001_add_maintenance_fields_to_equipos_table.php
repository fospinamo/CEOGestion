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
            // Cantidad de mantenimientos y calibraciones por año
            $table->unsignedTinyInteger('mantenimientos_por_ano')->default(0)->after('contrato_id')
                ->comment('Cantidad de mantenimientos programados por año');
            
            $table->unsignedTinyInteger('calibraciones_por_ano')->default(0)->after('mantenimientos_por_ano')
                ->comment('Cantidad de calibraciones programadas por año');
            
            // Fechas informativas de último mantenimiento
            $table->timestamp('ultimo_mantenimiento_at')->nullable()->after('calibraciones_por_ano')
                ->comment('Fecha del último mantenimiento realizado');
            
            $table->timestamp('ultimo_calibracion_at')->nullable()->after('ultimo_mantenimiento_at')
                ->comment('Fecha de la última calibración realizada');
            
            $table->timestamp('proximo_mantenimiento_at')->nullable()->after('ultimo_calibracion_at')
                ->comment('Fecha del próximo mantenimiento programado (calculada)');
            
            // Índices para búsquedas rápidas
            $table->index('mantenimientos_por_ano');
            $table->index('proximo_mantenimiento_at');
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
