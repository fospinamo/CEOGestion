<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Empresa;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

/**
 * UsuarioController (MÓDULO SEGURIDAD)
 * 
 * Controlador para gestión de Usuarios del Sistema
 * 
 * RESPONSABILIDADES:
 * - CRUD completo de usuarios
 * - Asignación de roles a usuarios
 * - Gestión de permisos por usuario
 * - Activación/desactivación de usuarios
 * 
 * RESTRICCIÓN: Solo accesible para usuarios con permiso 'usuarios.ver'
 * 
 * MÉTODOS:
 * - index: Listar todos los usuarios
 * - create: Mostrar formulario de creación
 * - store: Guardar nuevo usuario
 * - show: Ver detalle del usuario
 * - edit: Mostrar formulario de edición
 * - update: Actualizar usuario
 * - destroy: Eliminar usuario
 * 
 * CAMBIOS PRINCIPALES (Módulo Seguridad):
 * - Migrado de app/Http/Controllers a app/Http/Controllers/Seguridad
 * - Ahora usa field role_id en lugar de tipo_rol
 * - Integración con sistema de permisos dinámicos
 * 
 * VALIDACIONES:
 * - Email único en toda la BD
 * - Rol debe existir en tabla roles
 * - Password hasheado automáticamente
 */
class UsuarioController extends Controller
{
    // Middleware se aplica a nivel de rutas en routes/seguridad.php
    // NO en el constructor del controlador

    /**
     * Mostrar lista de usuarios
     * 
     * GET /seguridad/usuarios
     * 
     * @return View
     */
    public function index(): View
    {
        // Cargar usuarios con sus relaciones
        $usuarios = User::with(['role', 'empresa', 'sede'])
            ->paginate(25);

        return view('seguridad.usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario para crear usuario
     * 
     * GET /seguridad/usuarios/create
     * 
     * @return View
     */
    public function create(): View
    {
        // Autorización
        $this->authorize('can', 'usuarios.crear');

        // Cargar datos para combos
        $roles = Role::all();
        $empresas = Empresa::all();
        $sedes = Sede::all();

        return view('seguridad.usuarios.create', compact('roles', 'empresas', 'sedes'));
    }

    /**
     * Guardar nuevo usuario
     * 
     * POST /seguridad/usuarios
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Autorización
        $this->authorize('can', 'usuarios.crear');

        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'empresa_id' => 'nullable|exists:empresas,id',
            'sede_id' => 'nullable|exists:sedes,id',
            'cedula' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'estado' => 'nullable|boolean',
        ], [
            'email.unique' => 'Este email ya está registrado en el sistema',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'role_id.required' => 'Debe seleccionar un rol',
            'role_id.exists' => 'El rol seleccionado no existe',
        ]);

        // Hash de contraseña
        $validated['password'] = Hash::make($validated['password']);

        // Crear usuario
        $usuario = User::create($validated);

        return redirect()
            ->route('seguridad.usuarios.show', $usuario)
            ->with('success', "Usuario '{$usuario->name}' creado exitosamente");
    }

    /**
     * Mostrar detalle del usuario
     * 
     * GET /seguridad/usuarios/{usuario}
     * 
     * @param User $usuario
     * @return View
     */
    public function show(User $usuario): View
    {
        // Cargar relaciones
        $usuario->load(['role', 'empresa', 'sede']);

        return view('seguridad.usuarios.show', compact('usuario'));
    }

    /**
     * Mostrar formulario para editar usuario
     * 
     * GET /seguridad/usuarios/{usuario}/edit
     * 
     * @param User $usuario
     * @return View
     */
    public function edit(User $usuario): View
    {
        // Autorización
        $this->authorize('can', 'usuarios.editar');

        // Cargar datos para combos
        $roles = Role::all();
        $empresas = Empresa::all();
        $sedes = Sede::all();

        return view('seguridad.usuarios.edit', compact('usuario', 'roles', 'empresas', 'sedes'));
    }

    /**
     * Actualizar usuario
     * 
     * PUT /seguridad/usuarios/{usuario}
     * 
     * @param Request $request
     * @param User $usuario
     * @return RedirectResponse
     */
    public function update(Request $request, User $usuario): RedirectResponse
    {
        // Autorización
        $this->authorize('can', 'usuarios.editar');

        // Validar datos (email único excepto el del usuario actual)
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'empresa_id' => 'nullable|exists:empresas,id',
            'sede_id' => 'nullable|exists:sedes,id',
            'cedula' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'estado' => 'nullable|boolean',
        ]);

        // Si hay contraseña nueva, hashear
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            // Si no hay contraseña, no incluir en update
            unset($validated['password']);
        }

        // Actualizar usuario
        $usuario->update($validated);

        return redirect()
            ->route('seguridad.usuarios.show', $usuario)
            ->with('success', "Usuario '{$usuario->name}' actualizado exitosamente");
    }

    /**
     * Eliminar usuario
     * 
     * DELETE /seguridad/usuarios/{usuario}
     * 
     * @param User $usuario
     * @return RedirectResponse
     */
    public function destroy(User $usuario): RedirectResponse
    {
        // Autorización
        $this->authorize('can', 'usuarios.eliminar');

        // No permitir eliminar al usuario autenticado
        if ($usuario->id === auth()->id()) {
            return redirect()
                ->route('seguridad.usuarios.index')
                ->with('error', 'No puedes eliminar tu propia cuenta');
        }

        $userName = $usuario->name;
        $usuario->delete();

        return redirect()
            ->route('seguridad.usuarios.index')
            ->with('success', "Usuario '{$userName}' eliminado exitosamente");
    }
}
