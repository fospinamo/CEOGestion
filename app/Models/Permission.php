<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Permission
 * 
 * DOCUMENTACIÓN:
 * - Representa un permiso en el sistema
 * - Estructura: {módulo}.{recurso}.{acción}
 * 
 * EJEMPLOS DE PERMISOS:
 * - seguridad.usuarios.ver
 * - seguridad.usuarios.crear
 * - incidencias.servicios.asignar
 * - parametros.equipos.exportar
 * 
 * RELACIONES:
 * - roles() → Roles que tienen este permiso
 * 
 * BUENA PRÁCTICA:
 * - Crear permisos en seeder, no hardcodear
 * - Usar convención de nombres consistente
 * - Documentar qué puede hacer cada permiso
 */
class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'module',
        'resource',
        'action',
    ];

    // ============ RELACIONES ============

    /**
     * Obtener todos los roles que tienen este permiso
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    // ============ SCOPES ============

    /**
     * Filtrar permisos por módulo
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $module
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Filtrar permisos por recurso
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $resource
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByResource($query, string $resource)
    {
        return $query->where('resource', $resource);
    }

    /**
     * Filtrar permisos por acción
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $action
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Obtener permisos por módulo y recurso
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $module
     * @param string $resource
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByModuleAndResource($query, string $module, string $resource)
    {
        return $query->where('module', $module)->where('resource', $resource);
    }
}
