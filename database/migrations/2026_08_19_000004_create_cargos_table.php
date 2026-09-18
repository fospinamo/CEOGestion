<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cargos')) {
            return;
        }

        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->unsignedInteger('consecutivo')->unique()->comment('Consecutivo auto-generado del cargo');
            $table->string('codigo', 50)->unique()->comment('Codigo unico del cargo');
            $table->string('descripcion', 255)->comment('Descripcion del cargo');
            $table->boolean('estado')->default(true)->index()->comment('Activo o inactivo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
