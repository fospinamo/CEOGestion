<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->string('numero_cotizacion', 50)->unique();
            $table->date('fecha_cotizacion');
            $table->date('fecha_vencimiento')->nullable();
            $table->string('tipo_servicio', 50)->default('SOPORTE_TI');
            $table->decimal('valor_subtotal', 15, 2)->default(0);
            $table->decimal('valor_iva', 15, 2)->default(0);
            $table->decimal('valor_total', 15, 2)->default(0);
            $table->string('moneda', 10)->default('COP');
            $table->text('descripcion_servicio')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('estado', 20)->default('BORRADOR');
            $table->string('archivo_pdf', 255)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('estado');
            $table->index('fecha_cotizacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
