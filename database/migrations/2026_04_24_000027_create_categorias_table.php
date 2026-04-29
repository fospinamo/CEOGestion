<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla de categorías de equipos
     * 
     * Almacena las categorías parametrizables para tipos de equipos.
     * Permite que los administradores creen nuevas categorías sin código.
     */
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->string('icono', 50)->nullable();
            $table->string('color', 7)->default('#3b82f6'); // Color hexadecimal
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
