<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            if (!Schema::hasColumn('servicios', 'sla_horas_respuesta')) {
                $table->integer('sla_horas_respuesta')->nullable()->after('prioridad');
            }
            if (!Schema::hasColumn('servicios', 'sla_horas_solucion')) {
                $table->integer('sla_horas_solucion')->nullable()->after('sla_horas_respuesta');
            }
            if (!Schema::hasColumn('servicios', 'sla_fecha_limite_respuesta')) {
                $table->dateTime('sla_fecha_limite_respuesta')->nullable()->after('sla_horas_solucion');
            }
            if (!Schema::hasColumn('servicios', 'sla_fecha_limite_solucion')) {
                $table->dateTime('sla_fecha_limite_solucion')->nullable()->after('sla_fecha_limite_respuesta');
            }
            if (!Schema::hasColumn('servicios', 'alerta_enviada_respuesta')) {
                $table->boolean('alerta_enviada_respuesta')->default(false)->after('sla_fecha_limite_solucion');
            }
            if (!Schema::hasColumn('servicios', 'alerta_enviada_solucion')) {
                $table->boolean('alerta_enviada_solucion')->default(false)->after('alerta_enviada_respuesta');
            }
            if (!Schema::hasColumn('servicios', 'tecnico_asignado_id')) {
                $table->foreignId('tecnico_asignado_id')->nullable()->constrained('users')->after('alerta_enviada_solucion');
            }
            if (!Schema::hasColumn('servicios', 'fecha_asignacion')) {
                $table->dateTime('fecha_asignacion')->nullable()->after('tecnico_asignado_id');
            }
            if (!Schema::hasColumn('servicios', 'fecha_inicio_atencion')) {
                $table->dateTime('fecha_inicio_atencion')->nullable()->after('fecha_asignacion');
            }
            if (!Schema::hasColumn('servicios', 'fecha_resolucion')) {
                $table->dateTime('fecha_resolucion')->nullable()->after('fecha_inicio_atencion');
            }
            if (!Schema::hasColumn('servicios', 'fecha_cierre_real')) {
                $table->dateTime('fecha_cierre_real')->nullable()->after('fecha_resolucion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $columns = [
                'sla_horas_respuesta', 'sla_horas_solucion', 'sla_fecha_limite_respuesta',
                'sla_fecha_limite_solucion', 'alerta_enviada_respuesta', 'alerta_enviada_solucion',
                'tecnico_asignado_id', 'fecha_asignacion', 'fecha_inicio_atencion', 
                'fecha_resolucion', 'fecha_cierre_real'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('servicios', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
