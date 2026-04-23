<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla catálogo de tipos de equipos disponibles en el sistema TI.
     * Permite categorizar equipos por tipo (hardware, software, red, etc.)
     */
    public function up(): void
    {
        Schema::create('tipos_equipos', function (Blueprint $table) {
            $table->id();
            
            // Información básica del tipo de equipo
            $table->string('nombre')->unique()->comment('Nombre único del tipo de equipo');
            $table->string('descripcion')->nullable()->comment('Descripción detallada');
            
            // Categoría para clasificación
            $table->enum('categoria', ['HARDWARE', 'SOFTWARE', 'RED', 'PERIFERICO', 'OTRO'])
                ->default('HARDWARE')
                ->comment('Categoría del equipo');
            
            // Ícono para UI
            $table->string('icono')->nullable()->comment('Ícono Font Awesome (ej: fa-desktop)');
            
            $table->timestamps();
            
            // Índices
            $table->index('categoria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_equipos');
    }
};
