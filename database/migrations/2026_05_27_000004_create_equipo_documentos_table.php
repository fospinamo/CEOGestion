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
        Schema::create('equipo_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade')->comment('Equipo relacionado');
            $table->enum('tipo', [
                'visual',
                'hojas_vida',
                'reportes_anexos',
                'facturas',
                'certificados',
                'actas'
            ])->comment('Tipo de documento');
            $table->string('nombre_original')->comment('Nombre original del archivo');
            $table->string('archivo_path')->comment('Ruta almacenada en disco');
            $table->string('mime_type')->nullable()->comment('Tipo MIME del archivo');
            $table->integer('tamaño_bytes')->nullable()->comment('Tamaño en bytes');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('set null')->nullable()->comment('Usuario que cargó el archivo');
            $table->text('descripcion')->nullable()->comment('Descripción del documento');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('equipo_id');
            $table->index('tipo');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipo_documentos');
    }
};
