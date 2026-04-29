<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agregar campos para técnico, facturación y estado de servicio
     */
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            // Relación con técnico (User)
            $table->foreignId('tecnico_id')->nullable()->after('tecnico_asignado')->constrained('users')->onDelete('set null');
            
            // Estado del servicio (relación con tabla estado_servicios)
            $table->foreignId('estado_servicio_id')->nullable()->after('tecnico_id')->constrained('estado_servicios')->onDelete('set null');
            
            // Indicadores de facturación
            $table->boolean('puede_facturarse')->default(true)->after('estado_servicio_id')->comment('Si el servicio puede ser facturado');
            $table->boolean('es_soporte_contrato')->default(false)->after('puede_facturarse')->comment('Si es soporte incluido en el contrato');
            
            // Almacenar imágenes del servicio (JSON array de paths)
            $table->json('imagenes_servicio')->nullable()->after('es_soporte_contrato');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropForeignIdFor('users', 'tecnico_id');
            $table->dropForeignIdFor('estado_servicios', 'estado_servicio_id');
            $table->dropColumn([
                'tecnico_id',
                'estado_servicio_id',
                'puede_facturarse',
                'es_soporte_contrato',
                'imagenes_servicio',
            ]);
        });
    }
};
