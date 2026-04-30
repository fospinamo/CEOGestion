<?php

/**
 * RUTAS DEL MÓDULO SEGURIDAD
 * 
 * Descripción:
 * Gestión de usuarios, roles y permisos del sistema
 * 
 * REQUISITOS:
 * - Usuario debe estar autenticado
 * - Solo Admin tiene acceso (middleware role:admin)
 * 
 * ESTRUCTURA:
 * /seguridad/
 * ├── /usuarios (CRUD de usuarios)
 * ├── /roles (CRUD de roles)
 * └── /permissions (visualización de permisos)
 * 
 * DOCUMENTACIÓN DE PERMISOS:
 * - usuarios.ver: Ver lista de usuarios
 * - usuarios.crear: Crear nuevo usuario
 * - usuarios.editar: Editar usuario
 * - usuarios.eliminar: Eliminar usuario
 * - roles.ver: Ver lista de roles
 * - roles.crear: Crear nuevo rol
 * - roles.editar: Editar rol y asignar permisos
 * - roles.eliminar: Eliminar rol
 * - permissions.ver: Ver permisos disponibles
 * 
 * BUENA PRÁCTICA:
 * - Todos los permisos listados arriba serán validados por middleware
 * - En controladores se valida con $this->middleware(['auth', 'can:permiso.name'])
 */

use App\Http\Controllers\Seguridad\UsuarioController;
use App\Http\Controllers\Seguridad\RoleController;
use App\Http\Controllers\Seguridad\PermissionController;
use Illuminate\Support\Facades\Route;

/**
 * GRUPO DE RUTAS: Módulo Seguridad
 * 
 * Middleware aplicado:
 * - auth: Usuario debe estar autenticado
 * - role:admin: Solo usuarios con rol 'admin' pueden acceder
 */
Route::prefix('seguridad')
    ->name('seguridad.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // ============================================
        // RUTAS DE USUARIOS
        // ============================================
        /**
         * Gestión de usuarios del sistema
         * 
         * RUTAS:
         * GET    /seguridad/usuarios              → index (listar)
         * GET    /seguridad/usuarios/create       → create (formulario)
         * POST   /seguridad/usuarios              → store (guardar)
         * GET    /seguridad/usuarios/{usuario}    → show (detalle)
         * GET    /seguridad/usuarios/{usuario}/edit → edit (formulario)
         * PUT    /seguridad/usuarios/{usuario}    → update (actualizar)
         * DELETE /seguridad/usuarios/{usuario}    → destroy (eliminar)
         * 
         * PERMISOS REQUERIDOS:
         * - usuarios.ver (listar)
         * - usuarios.crear (crear)
         * - usuarios.editar (editar)
         * - usuarios.eliminar (eliminar)
         */
        Route::resource('usuarios', UsuarioController::class);

        // ============================================
        // RUTAS DE ROLES
        // ============================================
        /**
         * Gestión de roles del sistema
         * 
         * RUTAS:
         * GET    /seguridad/roles              → index (listar)
         * GET    /seguridad/roles/create       → create (formulario)
         * POST   /seguridad/roles              → store (guardar)
         * GET    /seguridad/roles/{role}       → show (detalle)
         * GET    /seguridad/roles/{role}/edit  → edit (formulario)
         * PUT    /seguridad/roles/{role}       → update (actualizar)
         * DELETE /seguridad/roles/{role}       → destroy (eliminar)
         * POST   /seguridad/roles/{role}/assign-permissions → assignPermissions
         * 
         * PERMISOS REQUERIDOS:
         * - roles.ver (listar, show)
         * - roles.crear (create, store)
         * - roles.editar (edit, update, assignPermissions)
         * - roles.eliminar (destroy)
         */
        Route::resource('roles', RoleController::class);

        /**
         * Asignar permisos a un rol
         * 
         * POST /seguridad/roles/{role}/assign-permissions
         * 
         * Parámetros POST:
         * - permission_ids: array de IDs de permisos
         * 
         * PERMISO REQUERIDO: roles.editar
         */
        Route::post('roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])
            ->name('roles.assign-permissions')
            ->middleware('can:roles.editar');

        // ============================================
        // RUTAS DE PERMISOS
        // ============================================
        /**
         * Visualización de permisos disponibles
         * 
         * RUTAS:
         * GET /seguridad/permissions         → index (listar con filtros)
         * GET /seguridad/permissions/{permission} → show (detalle)
         * 
         * PERMISOS REQUERIDOS:
         * - permissions.ver (listar, show)
         * 
         * FILTROS DISPONIBLES EN INDEX:
         * ?module=Seguridad  → Filtrar por módulo
         * ?resource=usuarios → Filtrar por recurso
         * ?action=crear      → Filtrar por acción
         */
        Route::resource('permissions', PermissionController::class)
            ->only(['index', 'show', 'store']);
    });
