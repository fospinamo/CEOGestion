<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('radicaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('sede_id')->constrained('sedes');
            $table->foreignId('documento_id')->constrained('documentos');
            $table->string('numero', 50)->unique();
            $table->date('fecha_radicacion');
            $table->string('tipo', 50)->default('ENTRADA');
            $table->string('remitente', 200)->nullable();
            $table->string('asunto', 255)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('estado', 30)->default('ABIERTA');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('radicaciones');
    }
};
