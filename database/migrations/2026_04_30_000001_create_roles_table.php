<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crear tabla de roles
 * 
 * DOCUMENTACIÓN:
 * - Almacena los roles del sistema (Admin, Técnico, Agente)
 * - name: Nombre descriptivo del rol (ej: "Admin", "Técnico")
 * - slug: Identificador único para queries (ej: "admin", "tecnico")
 * - description: Descripción del rol y sus permisos
 * 
 * BUENA PRÁCTICA: Usar slug para identificar roles en código, no 'name'
 * Ejemplo: $user->hasRole('admin') comparar con slug
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();        // "Admin", "Técnico", "Agente"
            $table->string('slug', 50)->unique();        // "admin", "tecnico", "agente"
            $table->text('description')->nullable();     // Descripción del rol
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
