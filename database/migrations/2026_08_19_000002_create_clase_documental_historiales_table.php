<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('clase_documental_historiales')) {
            return;
        }

        Schema::create('clase_documental_historiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clase_documental_id')->constrained('clases_documentales')->onDelete('cascade');
            $table->string('codigo', 50)->comment('Consecutivo al momento del cambio');
            $table->string('version', 50)->comment('Version al momento del cambio');
            $table->dateTime('fecha_cambio')->comment('Fecha y hora del cambio');
            $table->string('imagen', 255)->nullable()->comment('Imagen anterior al cambio');
            $table->timestamps();

            $table->index('clase_documental_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clase_documental_historiales');
    }
};
