<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Modificación de la tabla sedes para agregar relación con clientes.
     * Ahora una sede pertenece a un cliente que contrató servicios.
     * Se mantiene empresa_id para auditoría de quién provee los servicios.
     */
    public function up(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            // Agregar cliente_id como foreign key
            $table->foreignId('cliente_id')
                ->nullable()
                ->constrained('clientes')
                ->onDelete('restrict')
                ->after('empresa_id')
                ->comment('Cliente propietario de la sede');
            
            // Índice
            $table->index('cliente_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->dropColumn('cliente_id');
        });
    }
};
