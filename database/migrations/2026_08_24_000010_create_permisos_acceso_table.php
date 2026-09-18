<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('permisos_acceso')) {
            return;
        }

        Schema::create('permisos_acceso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('serie_id')->nullable()->constrained('series_documentales')->nullOnDelete();
            $table->foreignId('dependencia_id')->nullable()->constrained('dependencias')->nullOnDelete();
            $table->enum('tipo_acceso', ['Lectura', 'Escritura', 'Eliminacion', 'Administracion']);
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable()->comment('NULL = indefinido');
            $table->boolean('estado')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permisos_acceso');
    }
};
