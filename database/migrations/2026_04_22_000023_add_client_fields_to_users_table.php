<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Agregar campos de cliente a tabla users
 * 
 * Agrega campos necesarios para:
 * - Autenticación de clientes corporativos
 * - Tokens de acceso único para portal del cliente
 * - Permisos granulares
 * - Auditoría de acciones
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cliente al que pertenece este usuario corporativo (NULL si es usuario interno)
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            
            // Token de acceso para portal del cliente (acceso sin contraseña)
            $table->string('token_acceso')->nullable()->unique();
            
            // Última vez que usó el token de acceso
            $table->timestamp('ultimo_acceso_portal')->nullable();
            
            // IP desde donde accedió por última vez
            $table->string('ip_ultimo_acceso')->nullable();
            
            // Tipo de rol específico: admin, tecnico, coordinador, operario, cliente
            $table->enum('tipo_rol', [
                'admin',           // Administrador de aplicación
                'tecnico',         // Técnico que atiende servicios
                'coordinador',     // Coordinador/Monitor de servicios
                'operario',        // Operario que registra servicios
                'cliente'          // Usuario corporativo del cliente
            ])->default('operario');
            
            // Permisos específicos (JSON para flexibilidad)
            $table->json('permisos')->nullable();
            
            // Índices
            $table->index('tipo_rol');
            $table->index('cliente_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Cliente::class, 'cliente_id');
            $table->dropColumn([
                'cliente_id',
                'token_acceso',
                'ultimo_acceso_portal',
                'ip_ultimo_acceso',
                'tipo_rol',
                'permisos',
            ]);
        });
    }
};
