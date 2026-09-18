<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sgd_documentos')) {
            return;
        }

        Schema::create('sgd_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trd_detalle_id')->constrained('trd_detalle');
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->string('numero_documento', 50)->comment('Numero de radicado o folio');
            $table->date('fecha_documento')->comment('Fecha de creacion del documento');
            $table->foreignId('ubicacion_actual_id')->nullable()->constrained('ubicaciones_fisicas')->nullOnDelete();
            $table->foreignId('dependencia_creadora_id')->constrained('dependencias');
            $table->foreignId('dependencia_destino_id')->nullable()->constrained('dependencias')->nullOnDelete();
            $table->text('descripcion')->nullable()->comment('Asunto o contenido');
            $table->string('volumen', 30)->nullable()->comment('Cantidad de folios');
            $table->enum('soporte', ['Fisico', 'Digital', 'Ambos'])->default('Fisico');
            $table->date('fecha_ingreso_archivo')->nullable();
            $table->enum('estado_documento', ['Activo', 'Transferido', 'Eliminado'])->default('Activo')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sgd_documentos');
    }
};
