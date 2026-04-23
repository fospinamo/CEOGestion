<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seguimientos_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('accion', [
                'CREACION', 'ASIGNACION', 'INICIO_ATENCION', 'DIAGNOSTICO', 
                'SOLUCION', 'CAMBIO_ESTADO', 'SOLICITUD_REPUESTO', 'CIERRE', 'CALIFICACION'
            ]);
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo')->nullable();
            $table->text('observacion');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index('servicio_id');
            $table->index('user_id');
            $table->index('accion');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguimientos_servicios');
    }
};
