<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crear tabla de permisos
 * 
 * DOCUMENTACIÓN:
 * - Almacena todos los permisos disponibles en el sistema
 * - Cada permiso es granular (ej: usuarios.ver, usuarios.crear, usuarios.editar)
 * - Los permisos se asignan a roles a través de tabla pivote role_permissions
 * 
 * ESTRUCTURA DEL NOMBRE (name):
 * {módulo}.{recurso}.{acción}
 * Ejemplos:
 *   - seguridad.usuarios.ver
 *   - seguridad.roles.crear
 *   - incidencias.servicios.asignar
 *   - parametros.equipos.exportar
 * 
 * BUENA PRÁCTICA: Usar nombres consistentes y predecibles
 * Acciones estándar: ver, crear, editar, eliminar, exportar, imprimir
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();      // "usuarios.ver", "servicios.crear"
            $table->text('description')->nullable();    // Descripción del permiso
            $table->string('module', 50)->nullable();   // "Administrativo", "Parámetros", "Incidencias"
            $table->string('resource', 50)->nullable(); // "usuarios", "equipos", "servicios"
            $table->string('action', 30)->nullable();   // "ver", "crear", "editar", "eliminar"
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index('module');
            $table->index('resource');
            $table->index(['module', 'resource']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
