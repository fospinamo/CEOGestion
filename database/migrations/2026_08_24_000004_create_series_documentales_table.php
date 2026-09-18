<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('series_documentales')) {
            return;
        }

        Schema::create('series_documentales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->comment('Nombre de la serie documental');
            $table->string('codigo', 20)->unique()->comment('Codigo de la serie');
            $table->text('descripcion')->nullable()->comment('Descripcion de la funcion que genera documentos');
            $table->foreignId('dependencia_id')->constrained('dependencias');
            $table->boolean('estado')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('series_documentales');
    }
};
