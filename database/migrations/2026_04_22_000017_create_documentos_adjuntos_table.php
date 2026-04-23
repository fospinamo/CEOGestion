<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla polimórfica para adjuntos.
     * Permite digitalizar y almacenar documentos relacionados con cualquier entidad:
     * - Contratos (PDF firmados, anexos)
     * - Servicios (diagnósticos, facturas, reportes)
     * - Otros documentos según necesidad
     */
    public function up(): void
    {
        Schema::create('documentos_adjuntos', function (Blueprint $table) {
            $table->id();
            
            // Relación polimórfica
            $table->morphs('entidad');
            
            // Información del archivo
            $table->string('nombre_archivo')->comment('Nombre original del archivo');
            $table->string('ruta_archivo')->comment('Ruta relativa en storage');
            
            // Clasificación
            $table->enum('tipo_documento', [
                'CONTRATO',
                'SOPORTE',
                'DIAGNOSTICO',
                'FACTURA',
                'OTRO'
            ])->comment('Tipo de documento');
            
            // Metadatos del archivo
            $table->string('mime_type', 100)->comment('Tipo MIME (ej: application/pdf)');
            $table->integer('tamaño_bytes')->comment('Tamaño en bytes');
            
            // Descripción
            $table->text('descripcion')->nullable()->comment('Descripción del documento');
            
            // Auditoría
            $table->foreignId('subido_por')
                ->constrained('users')
                ->onDelete('restrict')
                ->comment('Usuario que subió el archivo');
            
            $table->timestamps();
            
            // Índices
            $table->index('tipo_documento');
            $table->index('subido_por');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_adjuntos');
    }
};
