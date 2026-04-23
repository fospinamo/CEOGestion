<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla de contratos de servicios TI.
     * Registra los acuerdos entre empresa y cliente.
     * Incluye información de valor, términos, alcance y documentación.
     */
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            
            // Relación con cliente
            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->onDelete('restrict')
                ->comment('Cliente contratante');
            
            // Identificación del contrato
            $table->string('numero_contrato')->unique()->comment('Número único del contrato');
            
            // Fechas importantes
            $table->date('fecha_inicio')->comment('Fecha de inicio del contrato');
            $table->date('fecha_fin')->comment('Fecha de finalización prevista');
            $table->date('fecha_firma')->nullable()->comment('Fecha de firma del contrato');
            $table->date('fecha_terminacion')->nullable()->comment('Fecha de terminación real');
            
            // Tipo y modalidad
            $table->enum('tipo_contrato', [
                'SOPORTE_TI',
                'MANTENIMIENTO',
                'INFRAESTRUCTURA',
                'CONSULTORIA'
            ])->comment('Tipo de contrato');
            
            $table->enum('modalidad', [
                'MENSUAL',
                'TRIMESTRAL',
                'SEMESTRAL',
                'ANUAL'
            ])->comment('Período de facturación');
            
            // Valores económicos
            $table->decimal('valor_contrato', 12, 2)->comment('Valor total del contrato');
            $table->enum('moneda', ['COP', 'USD', 'EUR'])
                ->default('COP')
                ->comment('Moneda del contrato');
            
            // Términos y condiciones
            $table->text('condiciones_pago')->nullable()->comment('Condiciones de pago');
            $table->text('alcance_servicios')->nullable()->comment('Alcance y descripción de servicios');
            $table->text('clausulas_especiales')->nullable()->comment('Cláusulas especiales o notas');
            
            // Documentación
            $table->string('documento_pdf')->nullable()->comment('Ruta del archivo PDF del contrato');
            $table->boolean('documento_firmado')->default(false)->comment('Indica si está digitalizado y firmado');
            
            // Estado del contrato
            $table->enum('estado', [
                'BORRADOR',
                'ACTIVO',
                'VENCIDO',
                'TERMINADO',
                'RENOVADO'
            ])->default('BORRADOR')->comment('Estado actual');
            
            $table->boolean('renovacion_automatica')->default(false)->comment('¿Renovación automática?');
            
            // Auditoría
            $table->foreignId('created_by')
                ->constrained('users')
                ->onDelete('restrict')
                ->comment('Usuario que creó el contrato');
            
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->comment('Último usuario que modificó');
            
            $table->softDeletes();
            $table->timestamps();
            
            // Índices
            $table->index('cliente_id');
            $table->index('numero_contrato');
            $table->index('estado');
            $table->index('fecha_inicio');
            $table->index('fecha_fin');
            $table->index('tipo_contrato');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
