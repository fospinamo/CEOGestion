<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * PermissionController
 * 
 * Controlador para gestión de Permisos
 * 
 * RESPONSABILIDADES:
 * - Visualizar permisos disponibles
 * - Crear nuevos permisos (si es necesario dinámicamente)
 * - Filtrar permisos por módulo, recurso o acción
 * 
 * RESTRICCIÓN: Solo lectura para usuarios autorizados
 * 
 * NOTA: La mayoría de permisos se crean en el seeder
 * Este controlador es principalmente para auditoría y visualización
 * 
 * PERMISOS DOCUMENTADOS:
 * - permissions.ver: Ver lista de permisos
 * - permissions.crear: Crear nuevos permisos (admin only)
 */
class PermissionController extends Controller
{
    /**
     * Constructor
     * Aplicar middleware de autorización
     */
    public function __construct()
    {
        $this->middleware(['auth', 'can:permissions.ver']);
    }

    /**
     * Mostrar lista de permisos
     * 
     * GET /seguridad/permissions
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Obtener filtros opcionales
        $module = $request->query('module');
        $resource = $request->query('resource');
        $action = $request->query('action');

        // Construir query base
        $query = Permission::query();

        // Aplicar filtros
        if ($module) {
            $query->where('module', $module);
        }

        if ($resource) {
            $query->where('resource', $resource);
        }

        if ($action) {
            $query->where('action', $action);
        }

        // Obtener permisos paginados
        $permissions = $query->paginate(50);

        // Obtener opciones para filtros
        $modules = Permission::distinct('module')->pluck('module');
        $resources = Permission::distinct('resource')->pluck('resource');
        $actions = Permission::distinct('action')->pluck('action');

        return view('seguridad.permissions.index', compact(
            'permissions',
            'modules',
            'resources',
            'actions',
            'module',
            'resource',
            'action'
        ));
    }

    /**
     * Mostrar detalle del permiso
     * 
     * GET /seguridad/permissions/{permission}
     * 
     * @param Permission $permission
     * @return View
     */
    public function show(Permission $permission): View
    {
        // Cargar roles que tienen este permiso
        $permission->load('roles');

        return view('seguridad.permissions.show', compact('permission'));
    }

    /**
     * Crear nuevo permiso (muy raro de usar, normalmente en seeder)
     * 
     * POST /seguridad/permissions
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validar autorización
        $this->authorize('can', 'permissions.crear');

        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:permissions',
            'description' => 'nullable|string',
            'module' => 'required|string|max:50',
            'resource' => 'required|string|max:50',
            'action' => 'required|string|max:30',
        ]);

        // Crear permiso
        $permission = Permission::create($validated);

        return redirect()
            ->route('seguridad.permissions.show', $permission)
            ->with('success', "Permiso '{$permission->name}' creado exitosamente");
    }
}
