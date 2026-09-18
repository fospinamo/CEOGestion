<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class ClaseDocumentalPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // 1. CREAR PERMISOS PARA CLASES DOCUMENTALES
        // ============================================
        $claseDocPermissions = [
            ['name' => 'clases_documentales.ver', 'module' => 'Documentación', 'resource' => 'clases_documentales', 'action' => 'ver', 'description' => 'Ver clases documentales'],
            ['name' => 'clases_documentales.crear', 'module' => 'Documentación', 'resource' => 'clases_documentales', 'action' => 'crear', 'description' => 'Crear clase documental'],
            ['name' => 'clases_documentales.editar', 'module' => 'Documentación', 'resource' => 'clases_documentales', 'action' => 'editar', 'description' => 'Editar clase documental'],
            ['name' => 'clases_documentales.eliminar', 'module' => 'Documentación', 'resource' => 'clases_documentales', 'action' => 'eliminar', 'description' => 'Eliminar clase documental'],
        ];

        foreach ($claseDocPermissions as $permData) {
            Permission::firstOrCreate(
                ['name' => $permData['name']],
                $permData
            );
        }

        // ============================================
        // 2. CREAR PERMISOS PARA CARGOS
        // ============================================
        $cargoPermissions = [
            ['name' => 'cargos.ver', 'module' => 'Parámetros', 'resource' => 'cargos', 'action' => 'ver', 'description' => 'Ver cargos'],
            ['name' => 'cargos.crear', 'module' => 'Parámetros', 'resource' => 'cargos', 'action' => 'crear', 'description' => 'Crear cargo'],
            ['name' => 'cargos.editar', 'module' => 'Parámetros', 'resource' => 'cargos', 'action' => 'editar', 'description' => 'Editar cargo'],
            ['name' => 'cargos.eliminar', 'module' => 'Parámetros', 'resource' => 'cargos', 'action' => 'eliminar', 'description' => 'Eliminar cargo'],
        ];

        foreach ($cargoPermissions as $permData) {
            Permission::firstOrCreate(
                ['name' => $permData['name']],
                $permData
            );
        }

        // ============================================
        // 3. CREAR ROL DIGITALIZADOR_ADMIN
        // ============================================
        $role = Role::firstOrCreate(
            ['slug' => 'digitalizador_admin'],
            [
                'name' => 'Digitalizador Admin',
                'description' => 'Administrador de digitalización con acceso completo al módulo de Documentación',
            ]
        );

        // ============================================
        // 4. ASIGNAR PERMISOS AL ROL
        // ============================================
        // Todos los permisos del módulo Documentación
        $documentacionPerms = [
            'digitalizaciones.ver', 'digitalizaciones.crear', 'digitalizaciones.editar', 'digitalizaciones.eliminar',
            'documentos.ver', 'documentos.crear', 'documentos.editar', 'documentos.eliminar',
            'radicaciones.ver', 'radicaciones.crear', 'radicaciones.editar', 'radicaciones.eliminar',
            'clases_documentales.ver', 'clases_documentales.crear', 'clases_documentales.editar', 'clases_documentales.eliminar',
        ];

        foreach ($documentacionPerms as $permName) {
            $role->grantPermission($permName);
        }

        // Asignar permisos de cargos al admin
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            foreach ($cargoPermissions as $perm) {
                $adminRole->grantPermission($perm['name']);
            }
        }

        $this->command->info('✓ Permisos de clases documentales creados');
        $this->command->info('✓ Permisos de cargos creados');
        $this->command->info('✓ Rol digitalizador_admin creado con permisos de Documentación');
    }
}
