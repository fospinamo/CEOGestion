<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: AuthToken
 * 
 * Valida que el usuario tenga acceso válido al portal del cliente.
 * 
 * Uso en rutas:
 * Route::middleware('auth.token:cliente')->group(function () { ... });
 * 
 * Verifica:
 * - Sesión de portal activa
 * - Token válido
 * - Usuario cliente existe
 * 
 * @author CEOGESTION
 */
class AuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $type = null): Response
    {
        // Verificar que existe sesión de portal
        if (!session()->has('portal_cliente_id') || !session()->has('portal_token')) {
            return redirect('/')->with('error', 'Sesión expirada. Por favor, accede nuevamente con tu token');
        }

        // Verificar token
        $token = session('portal_token');
        $user = \App\Models\User::where('token_acceso', $token)
            ->where('tipo_rol', 'cliente')
            ->where('estado', true)
            ->first();

        if (!$user) {
            session()->forget(['portal_cliente_id', 'portal_user_id', 'portal_token']);
            return redirect('/')->with('error', 'Token inválido o usuario desactivado');
        }

        // Disponibilizar usuario en request
        $request->attributes->set('portal_user', $user);

        return $next($request);
    }
}
