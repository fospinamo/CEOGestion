<?php

namespace App\Traits;

/**
 * Trait para verificar permisos en controladores
 * 
 * Uso:
 *   use PermissionCheckTrait;
 *   $this->checkPermission('servicios.asignar');
 *   $this->checkPermission('equipos.ver', 'equipos.crear'); // uno de los dos
 */
trait PermissionCheckTrait
{
    /**
     * Verifica que el usuario tenga el permiso indicado. Aborta 403 si no lo tiene.
     */
    protected function checkPermission(string ...$permissions): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(401, 'No autenticado');
        }

        // Admin tiene todos los permisos
        if ($user->hasRole('admin')) {
            return;
        }

        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return;
            }
        }

        abort(403, 'No tienes permiso para realizar esta acción');
    }
}
