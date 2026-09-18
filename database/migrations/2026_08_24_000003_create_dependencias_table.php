<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('dependencias')) {
            return;
        }

        Schema::create('dependencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->comment('Nombre del area u oficina');
            $table->string('codigo', 20)->unique()->comment('Codigo interno de la dependencia');
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->unsignedBigInteger('dependencia_padre_id')->nullable()->comment('Dependencia superior (NULL si es principal)');
            $table->string('responsable', 150)->nullable()->comment('Nombre del jefe de area');
            $table->boolean('estado')->default(true)->index();
            $table->timestamps();

            $table->foreign('dependencia_padre_id')->references('id')->on('dependencias')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dependencias');
    }
};
