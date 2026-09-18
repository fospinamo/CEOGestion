<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('clases_documentales')) {
            return;
        }

        Schema::create('clases_documentales', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('consecutivo')->unique()->comment('Consecutivo auto-generado del tipo de documento');
            $table->text('descripcion')->comment('Descripcion de la clase documental');
            $table->boolean('estado')->default(true)->index()->comment('Activo o inactivo');
            $table->string('version', 50)->comment('Version del documento');
            $table->text('observacion')->nullable()->comment('Observaciones adicionales');
            $table->foreignId('usuario_aprueba_id')->constrained('users')->comment('Usuario que aprueba');
            $table->foreignId('usuario_crea_id')->constrained('users')->comment('Usuario que crea');
            $table->foreignId('usuario_registra_id')->constrained('users')->comment('Usuario que registra');
            $table->string('imagen', 255)->nullable()->comment('Ruta de la imagen/archivo adjunto');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clases_documentales');
    }
};
