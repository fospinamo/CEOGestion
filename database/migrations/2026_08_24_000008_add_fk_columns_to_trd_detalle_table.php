<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trd_detalle', function (Blueprint $table) {
            $table->foreignId('dependencia_id')->nullable()->after('tabla_retencion_documental_id')->constrained('dependencias')->nullOnDelete();
            $table->foreignId('serie_id')->nullable()->after('dependencia_id')->constrained('series_documentales')->nullOnDelete();
            $table->foreignId('subserie_id')->nullable()->after('serie_id')->constrained('subseries_documentales')->nullOnDelete();
            $table->foreignId('tipo_documental_id')->nullable()->after('subserie_id')->constrained('tipos_documentales')->nullOnDelete();
            $table->foreignId('ubicacion_gestion_id')->nullable()->after('tipo_documental_id')->constrained('ubicaciones_fisicas')->nullOnDelete();
            $table->foreignId('ubicacion_central_id')->nullable()->after('ubicacion_gestion_id')->constrained('ubicaciones_fisicas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('trd_detalle', function (Blueprint $table) {
            $table->dropForeign(['dependencia_id']);
            $table->dropForeign(['serie_id']);
            $table->dropForeign(['subserie_id']);
            $table->dropForeign(['tipo_documental_id']);
            $table->dropForeign(['ubicacion_gestion_id']);
            $table->dropForeign(['ubicacion_central_id']);
            $table->dropColumn(['dependencia_id', 'serie_id', 'subserie_id', 'tipo_documental_id', 'ubicacion_gestion_id', 'ubicacion_central_id']);
        });
    }
};
