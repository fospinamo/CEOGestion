<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agregar contrato_id a la tabla equipos
     * 
     * Relaciona cada equipo con un contrato de servicios.
     * Un equipo puede estar asociado a un contrato específico.
     * El contrato define los servicios cubiertos para ese equipo.
     */
    public function up(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            if (!Schema::hasColumn('equipos', 'contrato_id')) {
                $table->foreignId('contrato_id')
                    ->nullable()
                    ->constrained('contratos')
                    ->onDelete('set null')
                    ->after('cliente_id')
                    ->comment('Contrato asociado');
                
                $table->index('contrato_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            if (Schema::hasColumn('equipos', 'contrato_id')) {
                $table->dropForeign(['contrato_id']);
                $table->dropColumn('contrato_id');
            }
        });
    }
};
