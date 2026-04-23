<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrato_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade');
            $table->enum('tipo_servicio', [
                'CORRECTIVO', 'PREVENTIVO', 'INSTALACION', 'CONFIGURACION', 'CAPACITACION', 'CONSULTA'
            ]);
            $table->boolean('incluido')->default(true);
            $table->decimal('costo_adicional', 12, 2)->nullable();
            $table->integer('sla_horas_respuesta')->nullable();
            $table->integer('sla_horas_solucion')->nullable();
            $table->timestamps();
            
            $table->unique(['contrato_id', 'tipo_servicio']);
            $table->index('contrato_id');
            $table->index('tipo_servicio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrato_servicios');
    }
};
