<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            if (!Schema::hasColumn('contratos', 'cobertura_servicios')) {
                $table->enum('cobertura_servicios', ['SOLO_PREVENTIVO', 'SOLO_CORRECTIVO', 'AMBOS'])->default('AMBOS')->after('estado');
            }
            if (!Schema::hasColumn('contratos', 'sla_default_horas_respuesta')) {
                $table->integer('sla_default_horas_respuesta')->default(4)->after('cobertura_servicios');
            }
            if (!Schema::hasColumn('contratos', 'sla_default_horas_solucion')) {
                $table->integer('sla_default_horas_solucion')->default(24)->after('sla_default_horas_respuesta');
            }
            if (!Schema::hasColumn('contratos', 'atencion_24x7')) {
                $table->boolean('atencion_24x7')->default(false)->after('sla_default_horas_solucion');
            }
            if (!Schema::hasColumn('contratos', 'horarios_atencion')) {
                $table->json('horarios_atencion')->nullable()->after('atencion_24x7');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            if (Schema::hasColumn('contratos', 'cobertura_servicios')) {
                $table->dropColumn('cobertura_servicios');
            }
            if (Schema::hasColumn('contratos', 'sla_default_horas_respuesta')) {
                $table->dropColumn('sla_default_horas_respuesta');
            }
            if (Schema::hasColumn('contratos', 'sla_default_horas_solucion')) {
                $table->dropColumn('sla_default_horas_solucion');
            }
            if (Schema::hasColumn('contratos', 'atencion_24x7')) {
                $table->dropColumn('atencion_24x7');
            }
            if (Schema::hasColumn('contratos', 'horarios_atencion')) {
                $table->dropColumn('horarios_atencion');
            }
        });
    }
};
