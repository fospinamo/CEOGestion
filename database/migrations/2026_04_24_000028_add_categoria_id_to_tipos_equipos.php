<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agregar relación a categorías en tabla tipos_equipos
     */
    public function up(): void
    {
        Schema::table('tipos_equipos', function (Blueprint $table) {
            // Agregar categoria_id (nullable inicialmente para migración segura)
            $table->foreignId('categoria_id')
                ->nullable()
                ->constrained('categorias')
                ->onDelete('restrict')
                ->after('nombre');

            // Cambiar campo categoria a desuso (será migrado)
            $table->string('categoria')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipos_equipos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
            $table->string('categoria')->nullable(false)->change();
        });
    }
};
