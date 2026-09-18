<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subseries_documentales')) {
            return;
        }

        Schema::create('subseries_documentales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->comment('Nombre de la subserie');
            $table->string('codigo', 30)->unique()->comment('Codigo de la subserie');
            $table->foreignId('serie_id')->constrained('series_documentales');
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subseries_documentales');
    }
};
