<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Modificar tabla users: agregar role_id y remover tipo_rol
 * 
 * DOCUMENTACIÓN:
 * - MIGRACIÓN: Cambio de sistema de roles
 * - ANTES: Campo tipo_rol (VARCHAR) con valores hardcodeados
 * - DESPUÉS: Relación con tabla roles (foreign key)
 * 
 * VENTAJAS DEL CAMBIO:
 * - Roles dinámicos desde BD (se puede agregar nuevos sin cambiar código)
 * - Mejor integridad de datos con foreign keys
 * - Facilita auditoría y búsquedas
 * 
 * ORDEN DE PASOS:
 * 1. Agregar columna role_id
 * 2. Remover columna tipo_rol (después de migración de datos en seeder)
 * 
 * IMPORTANTE:
 * - El seeder asignará roles a usuarios existentes
 * - Usar función down() para reversibilidad
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregar columna role_id después de email
            $table->after('email', function (Blueprint $table) {
                $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            });
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignKeyConstraints();
            $table->dropColumn('role_id');
        });
    }
};
