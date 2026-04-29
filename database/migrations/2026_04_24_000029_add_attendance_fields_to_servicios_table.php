<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agregar campos para atención de servicios con firma
     */
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            // Información de la persona receptora
            $table->string('persona_receptora_nombre', 100)->nullable()->after('comentarios_cliente');
            $table->string('persona_receptora_apellido', 100)->nullable()->after('persona_receptora_nombre');
            $table->string('persona_receptora_documento', 50)->nullable()->after('persona_receptora_apellido');
            
            // Firma digital (almacenaremos base64 o URL del archivo)
            $table->longText('firma_persona_receptora')->nullable()->after('persona_receptora_documento');
            
            // Descripción de lo realizado
            $table->longText('descripcion_atencion')->nullable()->after('firma_persona_receptora');
            
            // Equipos adicionales atendidos (JSON)
            $table->json('equipos_adicionales_atendidos')->nullable()->after('descripcion_atencion');
            
            // Fecha de firma
            $table->timestamp('fecha_firma')->nullable()->after('equipos_adicionales_atendidos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn([
                'persona_receptora_nombre',
                'persona_receptora_apellido',
                'persona_receptora_documento',
                'firma_persona_receptora',
                'descripcion_atencion',
                'equipos_adicionales_atendidos',
                'fecha_firma',
            ]);
        });
    }
};
