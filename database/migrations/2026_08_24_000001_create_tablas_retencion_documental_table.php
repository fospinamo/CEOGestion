<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tablas_retencion_documental')) {
            return;
        }

        Schema::create('tablas_retencion_documental', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->unsignedInteger('consecutivo')->unique()->comment('Consecutivo auto-generado de la TRD');
            $table->string('nombre', 255)->comment('Nombre o titulo de la TRD');
            $table->text('descripcion')->nullable()->comment('Descripcion o alcance de la TRD');
            $table->date('fecha_creacion')->comment('Fecha de creacion del instrumento');
            $table->date('fecha_aprobacion')->nullable()->comment('Fecha de aprobacion por el Comite Institucional');
            $table->boolean('aprobado_comite')->default(false)->comment('Aprobado por el Comite de Gestion Documental');
            $table->boolean('convalidado_agn')->default(false)->comment('Convalidado por el AGN o Consejo Territorial');
            $table->boolean('estado')->default(true)->index()->comment('Activo o inactivo');
            $table->text('observacion')->nullable()->comment('Observaciones adicionales');
            $table->foreignId('usuario_crea_id')->constrained('users')->comment('Usuario que crea la TRD');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tablas_retencion_documental');
    }
};
