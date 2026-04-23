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
        Schema::table('sedes', function (Blueprint $table) {
            // Eliminar columna ciudad si existe
            if (Schema::hasColumn('sedes', 'ciudad')) {
                $table->dropColumn('ciudad');
            }
            
            // Agregar nuevos campos de ubicación
            $table->foreignId('municipio_id')->constrained('municipios')->onDelete('restrict')->after('codigo');
            $table->foreignId('barrio_id')->nullable()->constrained('barrios')->onDelete('restrict')->after('municipio_id');
            $table->string('codigo_postal')->nullable()->after('barrio_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('municipio_id');
            $table->dropConstrainedForeignId('barrio_id');
            $table->dropColumn(['codigo_postal']);
            
            $table->string('ciudad')->nullable();
            $table->text('direccion')->nullable();
        });
    }
};
