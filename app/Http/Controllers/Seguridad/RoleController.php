<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * RoleController
 * 
 * Controlador para gestión de Roles
 * 
 * RESPONSABILIDADES:
 * - CRUD completo de roles
 * - Asignación de permisos a roles
 * - Validación de datos
 * 
 * RESTRICCIÓN: Solo accesible para usuarios con rol 'admin'
 * 
 * DOCUMENTACIÓN DE MÉTODOS:
 * - index: Lista todos los roles
 * - create: Mostrar formulario crear role
 * - store: Guardar nuevo role
 * - show: Mostrar detalle del role
 * - edit: Mostrar formulario editar role
 * - update: Actualizar role
 * - destroy: Eliminar role
 * - assignPermissions: Asignar permisos a un role
 * 
 * BUENA PRÁCTICA:
 * - Validar que el role tenga al menos 1 permiso
 * - No permitir eliminar roles si tienen usuarios asignados
 * - Usar transactions para operaciones críticas
 */
class RoleController extends Controller
{
    /**
     * Constructor
     * Aplicar middleware de autorización
     */
    public function __construct()
    {
        $this->middleware(['auth', 'can:roles.ver']);
    }

    /**
     * Mostrar lista de roles
     * 
     * GET /seguridad/roles
     * 
     * @return View
     */
    public function index(): View
    {
        $roles = Role::withCount('users')->withCount('permissions')->get();

        return view('seguridad.roles.index', compact('roles'));
    }

    /**
     * Mostrar formulario para crear role
     * 
     * GET /seguridad/roles/create
     * 
     * @return View
     */
    public function create(): View
    {
        return view('seguridad.roles.create');
    }

    /**
     * Guardar nuevo role
     * 
     * POST /seguridad/roles
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles',
            'slug' => 'required|string|max:50|unique:roles',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'El nombre del rol es requerido',
            'name.unique' => 'Ya existe un rol con este nombre',
            'slug.required' => 'El slug es requerido',
            'slug.unique' => 'Ya existe un rol con este slug',
        ]);

        // Crear role
        $role = Role::create($validated);

        return redirect()
            ->route('seguridad.roles.show', $role)
            ->with('success', "Rol '{$role->name}' creado exitosamente");
    }

    /**
     * Mostrar detalle del role
     * 
     * GET /seguridad/roles/{role}
     * 
     * @param Role $role
     * @return View
     */
    public function show(Role $role): View
    {
        // Cargar relaciones
        $role->load(['users', 'permissions']);

        // Obtener todos los permisos agrupados por módulo
        $permissionsByModule = Permission::all()
            ->groupBy('module')
            ->map(fn($perms) => $perms->groupBy('resource'));

        return view('seguridad.roles.show', compact('role', 'permissionsByModule'));
    }

    /**
     * Mostrar formulario para editar role
     * 
     * GET /seguridad/roles/{role}/edit
     * 
     * @param Role $role
     * @return View
     */
    public function edit(Role $role): View
    {
        return view('seguridad.roles.edit', compact('role'));
    }

    /**
     * Actualizar role
     * 
     * PUT /seguridad/roles/{role}
     * 
     * @param Request $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'slug' => 'required|string|max:50|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string|max:500',
        ]);

        // Actualizar role
        $role->update($validated);

        return redirect()
            ->route('seguridad.roles.show', $role)
            ->with('success', "Rol '{$role->name}' actualizado exitosamente");
    }

    /**
     * Eliminar role
     * 
     * DELETE /seguridad/roles/{role}
     * 
     * @param Role $role
     * @return RedirectResponse
     */
    public function destroy(Role $role): RedirectResponse
    {
        // Validar que no tenga usuarios asignados
        if ($role->users()->count() > 0) {
            return redirect()
                ->route('seguridad.roles.index')
                ->with('error', "No se puede eliminar el rol '{$role->name}' porque tiene usuarios asignados");
        }

        $roleName = $role->name;
        $role->delete();

        return redirect()
            ->route('seguridad.roles.index')
            ->with('success', "Rol '{$roleName}' eliminado exitosamente");
    }

    /**
     * Asignar permisos a un role
     * 
     * POST /seguridad/roles/{role}/assign-permissions
     * 
     * @param Request $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function assignPermissions(Request $request, Role $role): RedirectResponse
    {
        // Validar que se envíen permisos
        $validated = $request->validate([
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        // Sincronizar permisos (reemplazar todos con los nuevos)
        $role->syncPermissions($validated['permission_ids'] ?? []);

        return redirect()
            ->route('seguridad.roles.show', $role)
            ->with('success', "Permisos del rol '{$role->name}' actualizados exitosamente");
    }
}
