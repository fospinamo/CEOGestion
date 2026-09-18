<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ubicaciones_fisicas')) {
            return;
        }

        Schema::create('ubicaciones_fisicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('dependencia_id')->nullable()->constrained('dependencias')->nullOnDelete();
            $table->string('nombre', 150)->comment('Nombre del espacio de archivo');
            $table->string('codigo', 30)->unique()->comment('Codigo del espacio');
            $table->enum('tipo_archivo', ['Gestion', 'Central', 'Historico'])->comment('Tipo de archivo');
            $table->string('estanteria', 50)->nullable();
            $table->string('fila', 10)->nullable();
            $table->string('nivel', 10)->nullable();
            $table->boolean('estado')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicaciones_fisicas');
    }
};
