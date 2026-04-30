<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Role
 * 
 * DOCUMENTACIÓN:
 * - Representa un rol en el sistema (Admin, Técnico, Agente)
 * - Relación Many-to-Many con Permission
 * - Relación One-to-Many con User
 * 
 * RELACIONES:
 * - permissions() → Permisos asignados al rol
 * - users() → Usuarios con este rol
 * 
 * MÉTODOS ÚTILES:
 * - hasPermission($name) → Verificar si rol tiene permiso
 * - grantPermission($permission) → Asignar permiso
 * - revokePermission($permission) → Remover permiso
 * 
 * USO:
 * $admin = Role::where('slug', 'admin')->first();
 * $admin->permissions()->attach($permission);
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // ============ RELACIONES ============

    /**
     * Obtener todos los permisos del rol
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * Obtener todos los usuarios con este rol
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // ============ MÉTODOS CUSTOM ============

    /**
     * Verificar si el rol tiene un permiso específico
     * 
     * @param string $permissionName Nombre del permiso (ej: "usuarios.crear")
     * @return bool
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()
            ->where('name', $permissionName)
            ->exists();
    }

    /**
     * Asignar un permiso al rol
     * 
     * @param Permission|string|int $permission
     * @return void
     */
    public function grantPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission && !$this->hasPermission($permission->name)) {
            $this->permissions()->attach($permission);
        }
    }

    /**
     * Remover un permiso del rol
     * 
     * @param Permission|string|int $permission
     * @return void
     */
    public function revokePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission);
        }
    }

    /**
     * Sincronizar permisos (reemplazar todos con los nuevos)
     * 
     * @param array $permissionIds IDs de permisos
     * @return void
     */
    public function syncPermissions(array $permissionIds)
    {
        $this->permissions()->sync($permissionIds);
    }
}
