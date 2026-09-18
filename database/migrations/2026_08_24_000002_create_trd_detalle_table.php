<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('trd_detalle')) {
            return;
        }

        Schema::create('trd_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tabla_retencion_documental_id')->constrained('tablas_retencion_documental')->cascadeOnDelete();
            $table->string('dependencia', 255)->comment('Dependencia u oficina productora');
            $table->string('serie', 255)->comment('Serie documental');
            $table->string('subserie', 255)->nullable()->comment('Subserie documental');
            $table->string('tipo_documental', 255)->comment('Tipo documental');
            $table->string('archivo_gestion_tiempo', 100)->comment('Tiempo de retencion en Archivo de Gestion');
            $table->string('archivo_central_tiempo', 100)->comment('Tiempo de retencion en Archivo Central');
            $table->enum('disposicion_final', ['CP', 'EL', 'D'])->comment('Conservacion Permanente, Eliminacion o Digitalizacion');
            $table->text('observaciones')->nullable()->comment('Observaciones o normativa aplicable');
            $table->unsignedInteger('orden')->default(0)->comment('Orden de presentacion en la TRD');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trd_detalle');
    }
};
