<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckPermission Middleware
 * 
 * Verificar si el usuario autenticado tiene un permiso específico
 * 
 * USO EN RUTAS:
 * Route::middleware(['auth', 'permission:usuarios.crear'])->get(...);
 * 
 * O en controlador:
 * $this->middleware('permission:usuarios.crear');
 * 
 * DOCUMENTACIÓN:
 * - Redirige a 403 si no tiene permiso
 * - Admin siempre tiene acceso
 * - El usuario debe estar autenticado (middleware auth debe ser anterior)
 */
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Verificar que usuario esté autenticado
        if (!$request->user()) {
            return redirect('/login');
        }

        // Verificar permiso
        if (!$request->user()->hasPermission($permission)) {
            abort(403, "No tienes permiso para acceder a este recurso");
        }

        return $next($request);
    }
}
