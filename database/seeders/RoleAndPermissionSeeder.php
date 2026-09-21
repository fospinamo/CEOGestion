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
            $role = Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
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
            ['name' => 'marcas.ver', 'module' => 'Parámetros', 'resource' => 'marcas', 'action' => 'ver', 'description' => 'Ver marcas'],
            ['name' => 'marcas.crear', 'module' => 'Parámetros', 'resource' => 'marcas', 'action' => 'crear', 'description' => 'Crear marca'],
            ['name' => 'marcas.editar', 'module' => 'Parámetros', 'resource' => 'marcas', 'action' => 'editar', 'description' => 'Editar marca'],
            ['name' => 'marcas.eliminar', 'module' => 'Parámetros', 'resource' => 'marcas', 'action' => 'eliminar', 'description' => 'Eliminar marca'],
            ['name' => 'procesos.ver', 'module' => 'Parámetros', 'resource' => 'procesos', 'action' => 'ver', 'description' => 'Ver procesos'],
            ['name' => 'procesos.crear', 'module' => 'Parámetros', 'resource' => 'procesos', 'action' => 'crear', 'description' => 'Crear proceso'],
            ['name' => 'procesos.editar', 'module' => 'Parámetros', 'resource' => 'procesos', 'action' => 'editar', 'description' => 'Editar proceso'],
            ['name' => 'procesos.eliminar', 'module' => 'Parámetros', 'resource' => 'procesos', 'action' => 'eliminar', 'description' => 'Eliminar proceso'],
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

        // MÓDULO PARÁMETROS - Permisos faltantes
        $paramExtraPermissions = [
            ['name' => 'clientes.ver', 'module' => 'Parámetros', 'resource' => 'clientes', 'action' => 'ver', 'description' => 'Ver clientes'],
            ['name' => 'clientes.crear', 'module' => 'Parámetros', 'resource' => 'clientes', 'action' => 'crear', 'description' => 'Crear cliente'],
            ['name' => 'clientes.editar', 'module' => 'Parámetros', 'resource' => 'clientes', 'action' => 'editar', 'description' => 'Editar cliente'],
            ['name' => 'clientes.eliminar', 'module' => 'Parámetros', 'resource' => 'clientes', 'action' => 'eliminar', 'description' => 'Eliminar cliente'],
            ['name' => 'areas.ver', 'module' => 'Parámetros', 'resource' => 'areas', 'action' => 'ver', 'description' => 'Ver áreas'],
            ['name' => 'areas.crear', 'module' => 'Parámetros', 'resource' => 'areas', 'action' => 'crear', 'description' => 'Crear área'],
            ['name' => 'areas.editar', 'module' => 'Parámetros', 'resource' => 'areas', 'action' => 'editar', 'description' => 'Editar área'],
            ['name' => 'areas.eliminar', 'module' => 'Parámetros', 'resource' => 'areas', 'action' => 'eliminar', 'description' => 'Eliminar área'],
            ['name' => 'mantenimientos.ver', 'module' => 'Parámetros', 'resource' => 'mantenimientos', 'action' => 'ver', 'description' => 'Ver mantenimientos'],
            ['name' => 'mantenimientos.crear', 'module' => 'Parámetros', 'resource' => 'mantenimientos', 'action' => 'crear', 'description' => 'Crear mantenimiento'],
            ['name' => 'mantenimientos.editar', 'module' => 'Parámetros', 'resource' => 'mantenimientos', 'action' => 'editar', 'description' => 'Editar mantenimiento'],
            ['name' => 'mantenimientos.eliminar', 'module' => 'Parámetros', 'resource' => 'mantenimientos', 'action' => 'eliminar', 'description' => 'Eliminar mantenimiento'],
            ['name' => 'tipos-equipos.ver', 'module' => 'Parámetros', 'resource' => 'tipos-equipos', 'action' => 'ver', 'description' => 'Ver tipos de equipo'],
            ['name' => 'tipos-equipos.crear', 'module' => 'Parámetros', 'resource' => 'tipos-equipos', 'action' => 'crear', 'description' => 'Crear tipo de equipo'],
            ['name' => 'tipos-equipos.editar', 'module' => 'Parámetros', 'resource' => 'tipos-equipos', 'action' => 'editar', 'description' => 'Editar tipo de equipo'],
            ['name' => 'tipos-equipos.eliminar', 'module' => 'Parámetros', 'resource' => 'tipos-equipos', 'action' => 'eliminar', 'description' => 'Eliminar tipo de equipo'],
            ['name' => 'categorias.ver', 'module' => 'Parámetros', 'resource' => 'categorias', 'action' => 'ver', 'description' => 'Ver categorías'],
            ['name' => 'categorias.crear', 'module' => 'Parámetros', 'resource' => 'categorias', 'action' => 'crear', 'description' => 'Crear categoría'],
            ['name' => 'categorias.editar', 'module' => 'Parámetros', 'resource' => 'categorias', 'action' => 'editar', 'description' => 'Editar categoría'],
            ['name' => 'categorias.eliminar', 'module' => 'Parámetros', 'resource' => 'categorias', 'action' => 'eliminar', 'description' => 'Eliminar categoría'],
            ['name' => 'contratos.ver', 'module' => 'Parámetros', 'resource' => 'contratos', 'action' => 'ver', 'description' => 'Ver contratos'],
            ['name' => 'contratos.crear', 'module' => 'Parámetros', 'resource' => 'contratos', 'action' => 'crear', 'description' => 'Crear contrato'],
            ['name' => 'contratos.editar', 'module' => 'Parámetros', 'resource' => 'contratos', 'action' => 'editar', 'description' => 'Editar contrato'],
            ['name' => 'contratos.eliminar', 'module' => 'Parámetros', 'resource' => 'contratos', 'action' => 'eliminar', 'description' => 'Eliminar contrato'],
            ['name' => 'informe-formatos.ver', 'module' => 'Parámetros', 'resource' => 'informe-formatos', 'action' => 'ver', 'description' => 'Ver formatos de informe'],
            ['name' => 'informe-formatos.crear', 'module' => 'Parámetros', 'resource' => 'informe-formatos', 'action' => 'crear', 'description' => 'Crear formato de informe'],
            ['name' => 'informe-formatos.editar', 'module' => 'Parámetros', 'resource' => 'informe-formatos', 'action' => 'editar', 'description' => 'Editar formato de informe'],
            ['name' => 'informe-formatos.eliminar', 'module' => 'Parámetros', 'resource' => 'informe-formatos', 'action' => 'eliminar', 'description' => 'Eliminar formato de informe'],
        ];

        // MÓDULO ADMINISTRATIVO - Permisos faltantes
        $adminExtraPermissions = [
            ['name' => 'paises.ver', 'module' => 'Administrativo', 'resource' => 'paises', 'action' => 'ver', 'description' => 'Ver países'],
            ['name' => 'paises.crear', 'module' => 'Administrativo', 'resource' => 'paises', 'action' => 'crear', 'description' => 'Crear país'],
            ['name' => 'paises.editar', 'module' => 'Administrativo', 'resource' => 'paises', 'action' => 'editar', 'description' => 'Editar país'],
            ['name' => 'paises.eliminar', 'module' => 'Administrativo', 'resource' => 'paises', 'action' => 'eliminar', 'description' => 'Eliminar país'],
            ['name' => 'departamentos.ver', 'module' => 'Administrativo', 'resource' => 'departamentos', 'action' => 'ver', 'description' => 'Ver departamentos'],
            ['name' => 'departamentos.crear', 'module' => 'Administrativo', 'resource' => 'departamentos', 'action' => 'crear', 'description' => 'Crear departamento'],
            ['name' => 'departamentos.editar', 'module' => 'Administrativo', 'resource' => 'departamentos', 'action' => 'editar', 'description' => 'Editar departamento'],
            ['name' => 'departamentos.eliminar', 'module' => 'Administrativo', 'resource' => 'departamentos', 'action' => 'eliminar', 'description' => 'Eliminar departamento'],
            ['name' => 'municipios.ver', 'module' => 'Administrativo', 'resource' => 'municipios', 'action' => 'ver', 'description' => 'Ver municipios'],
            ['name' => 'municipios.crear', 'module' => 'Administrativo', 'resource' => 'municipios', 'action' => 'crear', 'description' => 'Crear municipio'],
            ['name' => 'municipios.editar', 'module' => 'Administrativo', 'resource' => 'municipios', 'action' => 'editar', 'description' => 'Editar municipio'],
            ['name' => 'municipios.eliminar', 'module' => 'Administrativo', 'resource' => 'municipios', 'action' => 'eliminar', 'description' => 'Eliminar municipio'],
        ];

        // MÓDULO DOCUMENTACIÓN - Permisos faltantes
        $docPermissions = [
            ['name' => 'digitalizaciones.ver', 'module' => 'Documentación', 'resource' => 'digitalizaciones', 'action' => 'ver', 'description' => 'Ver digitalizaciones'],
            ['name' => 'digitalizaciones.crear', 'module' => 'Documentación', 'resource' => 'digitalizaciones', 'action' => 'crear', 'description' => 'Crear digitalización'],
            ['name' => 'digitalizaciones.editar', 'module' => 'Documentación', 'resource' => 'digitalizaciones', 'action' => 'editar', 'description' => 'Editar digitalización'],
            ['name' => 'digitalizaciones.eliminar', 'module' => 'Documentación', 'resource' => 'digitalizaciones', 'action' => 'eliminar', 'description' => 'Eliminar digitalización'],
            ['name' => 'documentos.ver', 'module' => 'Documentación', 'resource' => 'documentos', 'action' => 'ver', 'description' => 'Ver documentos'],
            ['name' => 'documentos.crear', 'module' => 'Documentación', 'resource' => 'documentos', 'action' => 'crear', 'description' => 'Crear documento'],
            ['name' => 'documentos.editar', 'module' => 'Documentación', 'resource' => 'documentos', 'action' => 'editar', 'description' => 'Editar documento'],
            ['name' => 'documentos.eliminar', 'module' => 'Documentación', 'resource' => 'documentos', 'action' => 'eliminar', 'description' => 'Eliminar documento'],
            ['name' => 'radicaciones.ver', 'module' => 'Documentación', 'resource' => 'radicaciones', 'action' => 'ver', 'description' => 'Ver radicaciones'],
            ['name' => 'radicaciones.crear', 'module' => 'Documentación', 'resource' => 'radicaciones', 'action' => 'crear', 'description' => 'Crear radicación'],
            ['name' => 'radicaciones.editar', 'module' => 'Documentación', 'resource' => 'radicaciones', 'action' => 'editar', 'description' => 'Editar radicación'],
            ['name' => 'radicaciones.eliminar', 'module' => 'Documentación', 'resource' => 'radicaciones', 'action' => 'eliminar', 'description' => 'Eliminar radicación'],
        ];

        // MÓDULO COTIZACIONES
        $cotizacionPermissions = [
            ['name' => 'cotizaciones.ver', 'module' => 'Cotizaciones', 'resource' => 'cotizaciones', 'action' => 'ver', 'description' => 'Ver cotizaciones'],
            ['name' => 'cotizaciones.crear', 'module' => 'Cotizaciones', 'resource' => 'cotizaciones', 'action' => 'crear', 'description' => 'Crear cotización'],
            ['name' => 'cotizaciones.editar', 'module' => 'Cotizaciones', 'resource' => 'cotizaciones', 'action' => 'editar', 'description' => 'Editar cotización'],
            ['name' => 'cotizaciones.eliminar', 'module' => 'Cotizaciones', 'resource' => 'cotizaciones', 'action' => 'eliminar', 'description' => 'Eliminar cotización'],
        ];

        $allPermissions = array_merge(
            $securityPermissions,
            $adminPermissions,
            $adminExtraPermissions,
            $paramPermissions,
            $paramExtraPermissions,
            $incidenciaPermissions,
            $docPermissions,
            $cotizacionPermissions
        );

        $permissionMap = [];
        foreach ($allPermissions as $permData) {
            $permission = Permission::firstOrCreate(
                ['name' => $permData['name']],
                $permData
            );
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

        // ROLE TÉCNICO: Solo panel técnico, ver y editar servicios
        $tecnicoPermissions = [
            'servicios.ver',
            'servicios.panel-tech',
            'servicios.editar',
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
            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'areas.ver',
            'equipos.ver',
            'contratos.ver',
            'empresas.ver',
            'sedes.ver',
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
