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
        Schema::create('mantenimiento_calibraciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade')->comment('Equipo a mantener/calibrar');
            $table->enum('tipo', ['mantenimiento', 'calibracion'])->comment('Tipo de actividad');
            $table->date('fecha_programada')->comment('Fecha cuando está programado');
            $table->date('fecha_realizada')->nullable()->comment('Fecha cuando se realizó');
            $table->string('numero_reporte')->nullable()->unique()->comment('Número de reporte/orden de servicio');
            $table->text('descripcion_trabajo')->nullable()->comment('Descripción del trabajo realizado');
            $table->string('tecnico_responsable')->nullable()->comment('Nombre del técnico que lo realizó');
            $table->string('empresa_tercero')->nullable()->comment('Empresa de terceros que lo realizó');
            $table->string('archivo_pdf_path')->nullable()->comment('Ruta del PDF del reporte/acta');
            $table->decimal('costo', 12, 2)->nullable()->comment('Costo del mantenimiento/calibración');
            $table->enum('estado', ['programado', 'realizado', 'cancelado'])->default('programado')->comment('Estado actual');
            $table->foreignId('usuario_creador')->constrained('users')->onDelete('set null')->nullable()->comment('Usuario que registró');
            $table->foreignId('usuario_realizador')->constrained('users')->onDelete('set null')->nullable()->comment('Usuario que confirmó la realización');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('equipo_id');
            $table->index('tipo');
            $table->index('fecha_programada');
            $table->index('estado');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenimiento_calibraciones');
    }
};
