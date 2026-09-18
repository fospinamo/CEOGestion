<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clases_documentales', function (Blueprint $table) {
            $table->renameColumn('usuario_aprueba_id', 'revisado_por_id');
            $table->renameColumn('usuario_crea_id', 'realizado_por_id');
            $table->renameColumn('usuario_registra_id', 'registrado_por_id');
        });
    }

    public function down(): void
    {
        Schema::table('clases_documentales', function (Blueprint $table) {
            $table->renameColumn('revisado_por_id', 'usuario_aprueba_id');
            $table->renameColumn('realizado_por_id', 'usuario_crea_id');
            $table->renameColumn('registrado_por_id', 'usuario_registra_id');
        });
    }
};
