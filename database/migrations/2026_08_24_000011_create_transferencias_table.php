<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('transferencias')) {
            return;
        }

        Schema::create('transferencias', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_transferencia');
            $table->foreignId('documento_id')->constrained('sgd_documentos');
            $table->foreignId('ubicacion_origen_id')->constrained('ubicaciones_fisicas');
            $table->foreignId('ubicacion_destino_id')->constrained('ubicaciones_fisicas');
            $table->enum('tipo_transferencia', ['Primaria', 'Secundaria'])->comment('Primaria: Gestión→Central, Secundaria: Central→Histórico');
            $table->foreignId('usuario_responsable_id')->constrained('users');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transferencias');
    }
};
