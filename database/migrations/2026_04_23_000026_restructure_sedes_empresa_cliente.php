<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reestructura de Sedes
     * 
     * Las sedes pueden pertenecer a:
     * 1. Empresa (sedes propias de CEOGestion): empresa_id NOT NULL, cliente_id NULL
     * 2. Cliente (ubicaciones del cliente): cliente_id NOT NULL, empresa_id NULL
     * 
     * Validación: Exactamente UNA de las dos debe ser NOT NULL (validada en modelo)
     */
    public function up(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            // Si empresa_id fue eliminado, restaurarlo como nullable
            if (!Schema::hasColumn('sedes', 'empresa_id')) {
                $table->foreignId('empresa_id')
                    ->nullable()
                    ->constrained('empresas')
                    ->onDelete('cascade')
                    ->after('id');
            }
            
            // Hacer cliente_id nullable nuevamente
            if (Schema::hasColumn('sedes', 'cliente_id')) {
                $table->foreignId('cliente_id')
                    ->nullable()
                    ->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            if (Schema::hasColumn('sedes', 'empresa_id')) {
                $table->dropForeign(['empresa_id']);
                $table->dropColumn('empresa_id');
            }
            
            if (Schema::hasColumn('sedes', 'cliente_id')) {
                $table->foreignId('cliente_id')
                    ->nullable(false)
                    ->change();
            }
        });
    }
};
