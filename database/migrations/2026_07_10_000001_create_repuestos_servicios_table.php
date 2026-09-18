<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repuestos_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained()->onDelete('cascade');
            $table->string('codigo', 100)->nullable();
            $table->string('descripcion', 255);
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->nullOnDelete();
            $table->string('modelo', 150)->nullable();
            $table->string('serial', 150)->nullable();
            $table->unsignedInteger('cantidad')->default(1);
            $table->boolean('facturable')->default(false);
            $table->string('numero_factura', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repuestos_servicios');
    }
};
