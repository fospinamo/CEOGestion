<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            // Agregar sede_id si no existe
            if (!Schema::hasColumn('equipos', 'sede_id')) {
                $table->foreignId('sede_id')->nullable()->constrained('sedes')->onDelete('set null')->after('cliente_id');
                $table->index('sede_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            if (Schema::hasColumn('equipos', 'sede_id')) {
                $table->dropForeign(['sede_id']);
                $table->dropColumn('sede_id');
            }
        });
    }
};
