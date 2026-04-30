<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla pivote: roles y permisos (Many-to-Many)
 * 
 * DOCUMENTACIÓN:
 * - Establece relación Many-to-Many entre roles y permissions
 * - Un rol puede tener múltiples permisos
 * - Un permiso puede asignarse a múltiples roles
 * 
 * EJEMPLO:
 * Rol "Admin" puede tener permisos:
 *   - usuarios.ver
 *   - usuarios.crear
 *   - usuarios.editar
 *   - usuarios.eliminar
 * 
 * BUENA PRÁCTICA:
 * - Usar UNIQUE en (role_id, permission_id) para evitar asignaciones duplicadas
 * - Usar CASCADE en delete para limpiar automáticamente
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->timestamps();
            
            // Evitar asignaciones duplicadas de permiso al mismo rol
            $table->unique(['role_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
