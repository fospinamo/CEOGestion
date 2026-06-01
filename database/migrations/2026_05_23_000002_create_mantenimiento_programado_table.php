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
        Schema::create('mantenimiento_programado', function (Blueprint $table) {
            $table->id();
            
            // Relación con equipo
            $table->foreignId('equipo_id')
                ->constrained('equipos')
                ->onDelete('cascade')
                ->comment('Equipo asociado');
            
            // Tipo de mantenimiento
            $table->enum('tipo', ['MANTENIMIENTO', 'CALIBRACION'])
                ->default('MANTENIMIENTO')
                ->comment('Tipo: mantenimiento o calibración');
            
            // Fechas programación
            $table->date('fecha_programada')
                ->comment('Fecha en que está programado el mantenimiento');
            
            $table->timestamp('fecha_realizacion')->nullable()
                ->comment('Fecha en que se realizó (si ya se hizo)');
            
            // Estado del mantenimiento
            $table->enum('estado', ['PENDIENTE', 'REALIZADO', 'CANCELADO'])
                ->default('PENDIENTE')
                ->comment('Estado actual del mantenimiento');
            
            // Técnico asignado (opcional)
            $table->foreignId('tecnico_id')->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Técnico asignado para realizar el mantenimiento');
            
            // Relación con servicio generado (opcional)
            $table->foreignId('servicio_id')->nullable()
                ->constrained('servicios')
                ->nullOnDelete()
                ->comment('Servicio generado si se asignó a técnico');
            
            // Información adicional
            $table->text('notas')->nullable()
                ->comment('Notas sobre el mantenimiento');
            
            $table->text('resultado')->nullable()
                ->comment('Resultado del mantenimiento realizado');
            
            // Auditoría
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para búsquedas
            $table->index('equipo_id');
            $table->index('tecnico_id');
            $table->index('servicio_id');
            $table->index('tipo');
            $table->index('estado');
            $table->index('fecha_programada');
            $table->index(['equipo_id', 'estado']);
            $table->index(['equipo_id', 'tipo', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenimiento_programado');
    }
};
