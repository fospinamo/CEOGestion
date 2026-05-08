<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            // Agregar campo logo después de email
            $table->string('logo')->nullable()->after('email')->comment('Ruta del logo de la empresa');
            
            // Agregar campo descripción breve
            $table->text('descripcion')->nullable()->after('logo')->comment('Descripción breve de la empresa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['logo', 'descripcion']);
        });
    }
};
