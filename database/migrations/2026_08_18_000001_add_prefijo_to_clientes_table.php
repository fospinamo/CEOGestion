<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agregar campo prefijo a la tabla clientes.
     *
     * El prefijo es un código corto (ej: "C", "EMP") que se usa para
     * autogenerar los códigos de activo de los equipos de ese cliente.
     * Formato del código: {PREFIJO}-001, {PREFIJO}-002, etc.
     *
     * Unicidad: compuesta por (empresa_id, prefijo). Dos clientes de la
     * MISMA empresa no pueden compartir prefijo, pero clientes de
     * DIFERENTES empresas sí pueden.
     */
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('prefijo', 10)
                ->nullable()
                ->after('empresa_id')
                ->comment('Código corto para autogenerar códigos de activo: PREFIJO-001, PREFIJO-002');

            $table->unique(['empresa_id', 'prefijo'], 'idx_clientes_empresa_prefijo');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropIndex('idx_clientes_empresa_prefijo');
            $table->dropColumn('prefijo');
        });
    }
};
