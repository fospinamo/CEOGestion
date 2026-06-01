<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla de marcas de equipos
     * 
     * SEGURIDAD: Esta migración NO dañará datos existentes
     * - Crea una tabla nueva parametrizada
     * - Los datos de marcas se migrarán desde equipos.marca en la siguiente migración
     * 
     * ROLLBACK: Elimina la tabla (seguro, se poblará después en reversa)
     */
    public function up(): void
    {
        // Crear tabla de marcas
        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique()->comment('Nombre de la marca/fabricante');
            $table->string('descripcion')->nullable()->comment('Descripción de la marca');
            $table->string('logo_url')->nullable()->comment('URL del logo');
            $table->boolean('estado')->default(true)->index()->comment('Marca activa o inactiva');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marcas');
    }
};
