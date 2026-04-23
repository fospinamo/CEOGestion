<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla de servicios (tickets de soporte TI).
     * Registra todas las atenciones, reparaciones, mantenimientos y consultas.
     * Incluye diagnóstico, solución, tiempo de trabajo y satisfacción del cliente.
     */
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('equipo_id')
                ->constrained('equipos')
                ->onDelete('restrict')
                ->comment('Equipo relacionado');
            
            $table->foreignId('contrato_id')
                ->nullable()
                ->constrained('contratos')
                ->onDelete('set null')
                ->comment('Contrato bajo el que se ofrece el servicio');
            
            // Clasificación del servicio
            $table->enum('tipo_servicio', [
                'PREVENTIVO',
                'CORRECTIVO',
                'INSTALACION',
                'CONFIGURACION',
                'CAPACITACION',
                'CONSULTA'
            ])->comment('Tipo de servicio solicitado');
            
            $table->enum('prioridad', [
                'BAJA',
                'MEDIA',
                'ALTA',
                'URGENTE'
            ])->default('MEDIA')->comment('Nivel de prioridad');
            
            // Timeline del servicio
            $table->dateTime('fecha_solicitud')->comment('Cuándo se solicitó el servicio');
            $table->dateTime('fecha_atencion')->nullable()->comment('Cuándo se inició la atención');
            $table->dateTime('fecha_cierre')->nullable()->comment('Cuándo se cerró el ticket');
            
            // Información del solicitante
            $table->string('solicitado_por')->comment('Nombre de quien solicitó');
            $table->string('contacto_solicitante', 20)->comment('Teléfono/email del solicitante');
            
            // Descripción del problema y solución
            $table->text('descripcion_problema')->comment('Descripción del problema reportado');
            $table->text('diagnostico')->nullable()->comment('Diagnóstico realizado');
            $table->text('solucion_aplicada')->nullable()->comment('Solución implementada');
            
            // Recursos utilizados
            $table->json('repuestos_utilizados')->nullable()
                ->comment('Array de repuestos: [{nombre, cantidad, valor}, ...]');
            
            $table->decimal('horas_trabajadas', 5, 2)->nullable()
                ->comment('Horas de trabajo invertidas');
            
            // Técnico asignado
            $table->string('tecnico_asignado')->comment('Nombre del técnico');
            $table->string('tecnico_cedula', 20)->nullable()->comment('Cédula del técnico');
            
            // Estado del ticket
            $table->enum('estado', [
                'PENDIENTE',
                'EN_PROCESO',
                'RESUELTO',
                'CERRADO',
                'CANCELADO'
            ])->default('PENDIENTE')->comment('Estado actual del ticket');
            
            // Satisfacción del cliente
            $table->tinyInteger('calificacion_cliente')
                ->nullable()
                ->comment('Calificación 1-5 del cliente');
            
            $table->text('comentarios_cliente')->nullable()
                ->comment('Comentarios/feedback del cliente');
            
            $table->softDeletes();
            $table->timestamps();
            
            // Índices
            $table->index('equipo_id');
            $table->index('contrato_id');
            $table->index('estado');
            $table->index('prioridad');
            $table->index('tipo_servicio');
            $table->index('fecha_solicitud');
            $table->index('fecha_cierre');
            $table->fulltext('descripcion_problema');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
