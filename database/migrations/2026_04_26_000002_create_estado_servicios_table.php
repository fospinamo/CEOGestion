<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla de estados de servicio
     * Permite que técnicos seleccionen estados personalizados
     */
    public function up(): void
    {
        Schema::create('estado_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->string('color', 50)->default('#cccccc'); // Hexadecimal para UI
            $table->boolean('es_cierre')->default(false); // Si marca como cerrado
            $table->boolean('es_pendiente_repuesto')->default(false); // Si está pendiente repuesto
            $table->boolean('es_en_proceso')->default(false); // Si está en proceso
            $table->integer('orden')->default(0); // Para ordenar en select
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_servicios');
    }
};
