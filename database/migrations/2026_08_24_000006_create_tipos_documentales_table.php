<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tipos_documentales')) {
            return;
        }

        Schema::create('tipos_documentales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->comment('Nombre del tipo documental');
            $table->string('codigo', 40)->unique()->comment('Codigo del tipo documental');
            $table->foreignId('subserie_id')->nullable()->constrained('subseries_documentales')->nullOnDelete();
            $table->foreignId('serie_id')->constrained('series_documentales');
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_documentales');
    }
};
