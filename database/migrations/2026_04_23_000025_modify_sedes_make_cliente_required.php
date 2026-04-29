<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Las sedes ahora son específicas de cada cliente.
     * Se hace cliente_id requerido y se elimina empresa_id
     * ya que la empresa se obtiene a través del cliente.
     */
    public function up(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            // Hacer cliente_id requerido
            $table->foreignId('cliente_id')
                ->nullable(false)
                ->change();
            
            // Eliminar empresa_id si ya no es necesario
            if (Schema::hasColumn('sedes', 'empresa_id')) {
                $table->dropForeign(['empresa_id']);
                $table->dropColumn('empresa_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            $table->foreignId('empresa_id')
                ->constrained('empresas')
                ->onDelete('cascade')
                ->after('id');
            
            $table->foreignId('cliente_id')
                ->nullable()
                ->change();
        });
    }
};
