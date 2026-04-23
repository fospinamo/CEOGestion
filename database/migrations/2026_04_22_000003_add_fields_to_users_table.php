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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->onDelete('set null');
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->onDelete('set null');
            $table->enum('rol', ['admin', 'administrativo', 'conductor', 'pasajero'])->default('pasajero');
            $table->string('cedula')->unique()->nullable();
            $table->string('telefono')->nullable();
            $table->boolean('estado')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropForeign(['sede_id']);
            $table->dropColumn(['empresa_id', 'sede_id', 'rol', 'cedula', 'telefono', 'estado']);
        });
    }
};
