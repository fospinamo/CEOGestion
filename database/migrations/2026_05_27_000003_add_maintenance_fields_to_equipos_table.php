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
        Schema::table('equipos', function (Blueprint $table) {
            // Campos de mantenimiento y calibración
            $table->integer('mantenimientos_anuales')->nullable()->default(1)->comment('Cuántos mantenimientos al año debe tener');
            $table->integer('calibraciones_anuales')->nullable()->default(0)->comment('Cuántas calibraciones al año debe tener');
            $table->date('fecha_ultimo_mantenimiento')->nullable()->comment('Fecha del último mantenimiento realizado');
            $table->date('fecha_ultima_calibracion')->nullable()->comment('Fecha de la última calibración realizada');
            $table->date('proxima_fecha_mantenimiento')->nullable()->comment('Próxima fecha programada de mantenimiento');
            $table->date('proxima_fecha_calibracion')->nullable()->comment('Próxima fecha programada de calibración');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropColumn([
                'mantenimientos_anuales',
                'calibraciones_anuales',
                'fecha_ultimo_mantenimiento',
                'fecha_ultima_calibracion',
                'proxima_fecha_mantenimiento',
                'proxima_fecha_calibracion',
            ]);
        });
    }
};
