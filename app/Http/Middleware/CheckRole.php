<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckRole Middleware
 * 
 * Verificar si el usuario autenticado tiene uno o más roles específicos
 * 
 * USO EN RUTAS:
 * Route::middleware(['auth', 'role:admin'])->get(...);
 * Route::middleware(['auth', 'role:admin|tecnico'])->get(...);
 * 
 * O en controlador:
 * $this->middleware('role:admin|tecnico');
 * 
 * DOCUMENTACIÓN:
 * - Redirige a 403 si no tiene el rol
 * - Múltiples roles separados por | funcionan como OR (tiene uno o más)
 * - El usuario debe estar autenticado (middleware auth debe ser anterior)
 * 
 * EJEMPLO:
 * // Solo admin
 * Route::middleware(['auth', 'role:admin'])->group(...);
 * 
 * // Admin o Técnico
 * Route::middleware(['auth', 'role:admin|tecnico'])->group(...);
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $roles Roles separados por |
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        // Verificar que usuario esté autenticado
        if (!$request->user()) {
            return redirect('/login');
        }

        // Obtener roles permitidos (separados por |)
        $allowedRoles = explode('|', $roles);

        // Verificar si usuario tiene alguno de los roles
        $hasRole = collect($allowedRoles)
            ->some(fn($role) => $request->user()->hasRole(trim($role)));

        if (!$hasRole) {
            abort(403, "No tienes acceso a este recurso. Se requiere uno de los siguientes roles: " . implode(', ', $allowedRoles));
        }

        return $next($request);
    }
}
