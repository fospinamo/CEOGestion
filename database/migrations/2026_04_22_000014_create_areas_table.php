<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla de áreas dentro de una sede.
     * Permite organizar equipos por departamento o zona (ej: Contabilidad, TI, Ventas).
     * Cada área puede tener un responsable y un nivel de riesgo.
     */
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            
            // Relación con sede
            $table->foreignId('sede_id')
                ->constrained('sedes')
                ->onDelete('cascade')
                ->comment('Sede a la que pertenece el área');
            
            // Información del área
            $table->string('nombre')->comment('Nombre del área o departamento');
            $table->text('descripcion')->nullable()->comment('Descripción del área');
            
            // Responsable del área
            $table->string('responsable_nombre')->nullable()->comment('Nombre del responsable');
            $table->string('responsable_contacto', 20)->nullable()->comment('Teléfono o email del responsable');
            
            // Clasificación de riesgo
            $table->enum('nivel_riesgo', [
                'BAJO',
                'MEDIO',
                'ALTO',
                'CRITICO'
            ])->default('BAJO')->comment('Nivel de riesgo del área');
            
            // Estado
            $table->boolean('estado')->default(true)->comment('Área activa');
            
            $table->timestamps();
            
            // Índices
            $table->index('sede_id');
            $table->index('nivel_riesgo');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
