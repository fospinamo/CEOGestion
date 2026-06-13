<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('digitalizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('sede_id')->constrained('sedes');
            $table->foreignId('proceso_id')->constrained('procesos');
            $table->foreignId('subproceso_id')->constrained('subprocesos');
            $table->foreignId('documento_id')->constrained('documentos');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('radicacion_id')->nullable()->constrained('radicaciones');
            $table->string('titulo', 200)->nullable();
            $table->date('fecha_documento')->nullable();
            $table->string('estado', 30)->default('ACTIVO');
            $table->string('ruta', 255)->nullable();
            $table->string('nombre_archivo', 255)->nullable();
            $table->string('extension', 20)->nullable();
            $table->unsignedBigInteger('tamano_bytes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digitalizaciones');
    }
};
