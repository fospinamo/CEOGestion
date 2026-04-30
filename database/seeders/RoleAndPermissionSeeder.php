<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * RoleAndPermissionSeeder
 * 
 * Seeder para crear roles y permisos del sistema
 * 
 * RESPONSABILIDADES:
 * - Crear roles: Admin, Técnico, Agente
 * - Crear todos los permisos por módulo
 * - Asignar permisos a cada rol
 * - Crear usuario admin inicial
 * 
 * EJECUCIÓN:
 * php artisan db:seed --class=RoleAndPermissionSeeder
 * 
 * O con migrate:fresh:
 * php artisan migrate:fresh --seed
 * 
 * DOCUMENTACIÓN:
 * - Roles creados: Admin, Técnico, Agente
 * - Permisos creados: 30+ permisos granulares
 * - Usuario admin creado automáticamente para pruebas
 */
class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // ============================================
        // 1. CREAR ROLES
        // ============================================
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrador del sistema con acceso total',
            ],
            [
                'name' => 'Técnico',
                'slug' => 'tecnico',
                'description' => 'Técnico que atiende servicios e incidencias',
            ],
            [
                'name' => 'Agente',
                'slug' => 'agente',
                'description' => 'Agente que registra, asigna y reporta incidencias',
            ],
        ];

        $roleMap = [];
        foreach ($roles as $roleData) {
            $role = Role::create($roleData);
            $roleMap[$roleData['slug']] = $role;
        }

        // ============================================
        // 2. CREAR PERMISOS
        // ============================================

        // MÓDULO SEGURIDAD
        $securityPermissions = [
            ['name' => 'usuarios.ver', 'module' => 'Seguridad', 'resource' => 'usuarios', 'action' => 'ver', 'description' => 'Ver lista de usuarios'],
            ['name' => 'usuarios.crear', 'module' => 'Seguridad', 'resource' => 'usuarios', 'action' => 'crear', 'description' => 'Crear nuevo usuario'],
            ['name' => 'usuarios.editar', 'module' => 'Seguridad', 'resource' => 'usuarios', 'action' => 'editar', 'description' => 'Editar usuario'],
            ['name' => 'usuarios.eliminar', 'module' => 'Seguridad', 'resource' => 'usuarios', 'action' => 'eliminar', 'description' => 'Eliminar usuario'],
            ['name' => 'roles.ver', 'module' => 'Seguridad', 'resource' => 'roles', 'action' => 'ver', 'description' => 'Ver lista de roles'],
            ['name' => 'roles.crear', 'module' => 'Seguridad', 'resource' => 'roles', 'action' => 'crear', 'description' => 'Crear nuevo rol'],
            ['name' => 'roles.editar', 'module' => 'Seguridad', 'resource' => 'roles', 'action' => 'editar', 'description' => 'Editar rol y asignar permisos'],
            ['name' => 'roles.eliminar', 'module' => 'Seguridad', 'resource' => 'roles', 'action' => 'eliminar', 'description' => 'Eliminar rol'],
            ['name' => 'permissions.ver', 'module' => 'Seguridad', 'resource' => 'permissions', 'action' => 'ver', 'description' => 'Ver lista de permisos'],
        ];

        // MÓDULO ADMINISTRATIVO
        $adminPermissions = [
            ['name' => 'empresas.ver', 'module' => 'Administrativo', 'resource' => 'empresas', 'action' => 'ver', 'description' => 'Ver empresas'],
            ['name' => 'empresas.crear', 'module' => 'Administrativo', 'resource' => 'empresas', 'action' => 'crear', 'description' => 'Crear empresa'],
            ['name' => 'empresas.editar', 'module' => 'Administrativo', 'resource' => 'empresas', 'action' => 'editar', 'description' => 'Editar empresa'],
            ['name' => 'empresas.eliminar', 'module' => 'Administrativo', 'resource' => 'empresas', 'action' => 'eliminar', 'description' => 'Eliminar empresa'],
            ['name' => 'sedes.ver', 'module' => 'Administrativo', 'resource' => 'sedes', 'action' => 'ver', 'description' => 'Ver sedes'],
            ['name' => 'sedes.crear', 'module' => 'Administrativo', 'resource' => 'sedes', 'action' => 'crear', 'description' => 'Crear sede'],
            ['name' => 'sedes.editar', 'module' => 'Administrativo', 'resource' => 'sedes', 'action' => 'editar', 'description' => 'Editar sede'],
            ['name' => 'sedes.eliminar', 'module' => 'Administrativo', 'resource' => 'sedes', 'action' => 'eliminar', 'description' => 'Eliminar sede'],
        ];

        // MÓDULO PARÁMETROS
        $paramPermissions = [
            ['name' => 'equipos.ver', 'module' => 'Parámetros', 'resource' => 'equipos', 'action' => 'ver', 'description' => 'Ver equipos'],
            ['name' => 'equipos.crear', 'module' => 'Parámetros', 'resource' => 'equipos', 'action' => 'crear', 'description' => 'Crear equipo'],
            ['name' => 'equipos.editar', 'module' => 'Parámetros', 'resource' => 'equipos', 'action' => 'editar', 'description' => 'Editar equipo'],
            ['name' => 'equipos.eliminar', 'module' => 'Parámetros', 'resource' => 'equipos', 'action' => 'eliminar', 'description' => 'Eliminar equipo'],
            ['name' => 'equipos.exportar', 'module' => 'Parámetros', 'resource' => 'equipos', 'action' => 'exportar', 'description' => 'Exportar equipos a Excel'],
        ];

        // MÓDULO INCIDENCIAS
        $incidenciaPermissions = [
            ['name' => 'servicios.ver', 'module' => 'Incidencias', 'resource' => 'servicios', 'action' => 'ver', 'description' => 'Ver servicios'],
            ['name' => 'servicios.crear', 'module' => 'Incidencias', 'resource' => 'servicios', 'action' => 'crear', 'description' => 'Crear servicio'],
            ['name' => 'servicios.editar', 'module' => 'Incidencias', 'resource' => 'servicios', 'action' => 'editar', 'description' => 'Editar servicio'],
            ['name' => 'servicios.eliminar', 'module' => 'Incidencias', 'resource' => 'servicios', 'action' => 'eliminar', 'description' => 'Eliminar servicio'],
            ['name' => 'servicios.asignar', 'module' => 'Incidencias', 'resource' => 'servicios', 'action' => 'asignar', 'description' => 'Asignar técnico a servicio'],
            ['name' => 'servicios.panel-admin', 'module' => 'Incidencias', 'resource' => 'servicios', 'action' => 'panel-admin', 'description' => 'Ver panel administrativo de servicios'],
            ['name' => 'servicios.panel-tech', 'module' => 'Incidencias', 'resource' => 'servicios', 'action' => 'panel-tech', 'description' => 'Ver panel técnico de servicios'],
            ['name' => 'servicios.reportar', 'module' => 'Incidencias', 'resource' => 'servicios', 'action' => 'reportar', 'description' => 'Generar reportes de servicios'],
            ['name' => 'servicios.imprimir-pdf', 'module' => 'Incidencias', 'resource' => 'servicios', 'action' => 'imprimir-pdf', 'description' => 'Imprimir reportes en PDF'],
            ['name' => 'servicios.estadisticas', 'module' => 'Incidencias', 'resource' => 'servicios', 'action' => 'estadisticas', 'description' => 'Ver estadísticas de servicios'],
        ];

        $allPermissions = array_merge($securityPermissions, $adminPermissions, $paramPermissions, $incidenciaPermissions);

        $permissionMap = [];
        foreach ($allPermissions as $permData) {
            $permission = Permission::create($permData);
            $permissionMap[$permission->name] = $permission;
        }

        // ============================================
        // 3. ASIGNAR PERMISOS A ROLES
        // ============================================

        // ROLE ADMIN: Todos los permisos
        $adminPermNames = array_column($allPermissions, 'name');
        foreach ($adminPermNames as $permName) {
            $roleMap['admin']->grantPermission($permName);
        }

        // ROLE TÉCNICO: Solo panel técnico y ver servicios
        $tecnicoPermissions = [
            'servicios.ver',
            'servicios.panel-tech',
            'servicios.editar', // Para registrar seguimiento
        ];
        foreach ($tecnicoPermissions as $permName) {
            $roleMap['tecnico']->grantPermission($permName);
        }

        // ROLE AGENTE: Crear, ver, asignar servicios y generar reportes
        $agentePermissions = [
            'servicios.ver',
            'servicios.crear',
            'servicios.editar',
            'servicios.asignar',
            'servicios.panel-admin',
            'servicios.reportar',
            'servicios.imprimir-pdf',
            'servicios.estadisticas',
        ];
        foreach ($agentePermissions as $permName) {
            $roleMap['agente']->grantPermission($permName);
        }

        // ============================================
        // 4. CREAR USUARIO ADMIN INICIAL
        // ============================================
        $adminUser = User::where('email', 'admin@ceogestion.com')->first();
        
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Administrador',
                'email' => 'admin@ceogestion.com',
                'password' => Hash::make('password123'),
                'role_id' => $roleMap['admin']->id,
                'estado' => true,
            ]);

            $this->command->info('✓ Usuario admin creado: admin@ceogestion.com / password123');
        } else {
            // Asignar rol admin si no lo tiene
            $adminUser->update(['role_id' => $roleMap['admin']->id]);
            $this->command->info('✓ Usuario admin actualizado con rol');
        }

        $this->command->info('✓ Seeder completado: 3 roles y 30+ permisos creados');
    }
}
