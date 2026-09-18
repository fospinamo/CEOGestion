<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Eliminar campos legacy: tecnico_asignado_id y tecnico_cedula.
     * tecnico_id es el campo activo que reemplaza a tecnico_asignado_id.
     * La cédula del técnico ya está en la tabla users vía la relación tecnico_id.
     */
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            if (Schema::hasColumn('servicios', 'tecnico_asignado_id')) {
                $table->dropForeign(['tecnico_asignado_id']);
                $table->dropColumn('tecnico_asignado_id');
            }

            if (Schema::hasColumn('servicios', 'tecnico_cedula')) {
                $table->dropColumn('tecnico_cedula');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            if (!Schema::hasColumn('servicios', 'tecnico_asignado_id')) {
                $table->foreignId('tecnico_asignado_id')->nullable()->constrained('users')->after('alerta_enviada_solucion');
            }

            if (!Schema::hasColumn('servicios', 'tecnico_cedula')) {
                $table->string('tecnico_cedula', 20)->nullable()->comment('Cédula del técnico')->after('tecnico_asignado');
            }
        });
    }
};
