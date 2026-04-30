<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * Modelo User
 * 
 * Representa usuarios del sistema con diferentes roles:
 * - admin: Acceso completo, estadísticas, reportes
 * - tecnico: Atiende servicios asignados
 * - coordinador: Asigna servicios a técnicos, monitorea tickets abiertos
 * - operario: Registra servicios cuando clientes llaman
 * - cliente: Acceso restringido a sus propios contratos y equipos (portal del cliente)
 * 
 * @property int $id
 * @property string $name Nombre del usuario
 * @property string $email Email único
 * @property string $password Contraseña hasheada
 * @property string|null $cedula Cédula o documento
 * @property string|null $telefono Teléfono de contacto
 * @property int|null $empresa_id Empresa a la que pertenece
 * @property int|null $sede_id Sede principal del usuario
 * @property int|null $cliente_id Cliente corporativo (solo para usuarios tipo 'cliente')
 * @property string $rol Rol heredado (deprecado, usar tipo_rol)
 * @property string $tipo_rol admin|tecnico|coordinador|operario|cliente
 * @property bool $estado Usuario activo
 * @property string|null $token_acceso Token para acceso al portal del cliente
 * @property \Illuminate\Support\Carbon|null $ultimo_acceso_portal Última vez que accedió
 * @property string|null $ip_ultimo_acceso IP de último acceso
 * @property array|null $permisos Permisos específicos en JSON
 * @property \Illuminate\Support\Carbon $email_verified_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',              // NUEVO: Relación con tabla roles
        'empresa_id',
        'sede_id',
        'cliente_id',
        'rol',
        'tipo_rol',
        'cedula',
        'telefono',
        'estado',
        'token_acceso',
        'permisos',
    ];

    /**
     * Atributos ocultos en serialización
     */
    protected $hidden = [
        'password',
        'remember_token',
        'token_acceso',
    ];

    /**
     * Casting de atributos
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'estado' => 'boolean',
            'permisos' => 'array',
            'ultimo_acceso_portal' => 'datetime',
        ];
    }

    /**
     * Relaciones
     * ============================================
     */

    /**
     * El usuario pertenece a un rol (Admin, Técnico, Agente)
     * 
     * NUEVO: Sistema de roles dinámicos desde BD
     * Reemplaza el campo tipo_rol por relación a tabla roles
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**

    /**
     * El usuario pertenece a una sede (ubicación)
     */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    /**
     * El usuario pertenece a un cliente (solo si es usuario tipo 'cliente')
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Un usuario (técnico) puede tener servicios asignados
     */
    public function servicios(): HasMany
    {
        return $this->hasMany(Servicio::class, 'tecnico_id');
    }

    /**
     * Un usuario (coordinador/admin) crea seguimientos de servicios
     */
    public function seguimientosCreados(): HasMany
    {
        return $this->hasMany(SeguimientoServicio::class);
    }

    /**
     * Métodos de Autenticación y Permisos
     * ============================================
     */

    /**
     * Verifica si el usuario tiene un rol específico
     * 
     * @param string $roleName Slug del rol (ej: 'admin', 'tecnico', 'agente')
     * @return bool
     * 
     * EJEMPLO:
     * if ($user->hasRole('admin')) { ... }
     */
    public function hasRole(string $roleName): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->slug === $roleName;
    }

    /**
     * Verifica si el usuario tiene un permiso específico
     * 
     * @param string $permissionName Nombre del permiso (ej: 'usuarios.crear')
     * @return bool
     * 
     * EJEMPLO:
     * if ($user->hasPermission('usuarios.crear')) { ... }
     * 
     * NOTA: Admin tiene todos los permisos automáticamente
     */
    public function hasPermission(string $permissionName): bool
    {
        // Admin tiene todos los permisos
        if ($this->hasRole('admin')) {
            return true;
        }

        if (!$this->role) {
            return false;
        }

        return $this->role->hasPermission($permissionName);
    }

    /**
     * Verifica si el usuario tiene alguno de los roles especificados
     * 
     * @param array $roleNames Array de slugs de roles
     * @return bool
     * 
     * EJEMPLO:
     * if ($user->hasAnyRole(['admin', 'agente'])) { ... }
     */
    public function hasAnyRole(array $roleNames): bool
    {
        return collect($roleNames)
            ->contains(fn($role) => $this->hasRole($role));
    }

    /**
     * Verifica si el usuario tiene todos los roles especificados
     * 
     * @param array $roleNames Array de slugs de roles
     * @return bool
     * 
     * EJEMPLO:
     * if ($user->hasAllRoles(['admin', 'tecnico'])) { ... }
     */
    public function hasAllRoles(array $roleNames): bool
    {
        return collect($roleNames)
            ->every(fn($role) => $this->hasRole($role));
    }

    /**
     * Verifica si el usuario tiene alguno de los permisos especificados
     * 
     * @param array $permissionNames Array de nombres de permisos
     * @return bool
     * 
     * EJEMPLO:
     * if ($user->hasAnyPermission(['usuarios.crear', 'usuarios.editar'])) { ... }
     */
    public function hasAnyPermission(array $permissionNames): bool
    {
        return collect($permissionNames)
            ->contains(fn($perm) => $this->hasPermission($perm));
    }

    /**
     * Obtener nombre del rol del usuario
     * 
     * @return string|null
     */
    public function getRoleNameAttribute(): ?string
    {
        return $this->role?->name;
    }

    /**
     * Métodos Helpers Deprecated (para compatibilidad)
     * ============================================
     * 
     * ADVERTENCIA: Estos métodos usan tipo_rol (campo deprecado)
     * Se mantienen para no romper código existente, pero usar nuevos métodos
     */

    /**
     * Verifica si el usuario es administrador
     */
    public function esAdmin(): bool
    {
        return $this->tipo_rol === 'admin';
    }

    /**
     * Verifica si el usuario es técnico
     */
    public function esTecnico(): bool
    {
        return $this->tipo_rol === 'tecnico';
    }

    /**
     * Verifica si el usuario es coordinador
     */
    public function esCoordinador(): bool
    {
        return $this->tipo_rol === 'coordinador';
    }

    /**
     * Verifica si el usuario es operario (registra servicios)
     */
    public function esOperario(): bool
    {
        return $this->tipo_rol === 'operario';
    }

    /**
     * Verifica si el usuario es del tipo cliente corporativo
     */
    public function esCliente(): bool
    {
        return $this->tipo_rol === 'cliente';
    }

    /**
     * Genera un token único para acceso al portal del cliente
     * 
     * @return string Token generado
     */
    public function generarTokenAcceso(): string
    {
        $this->token_acceso = Str::random(64);
        $this->save();
        return $this->token_acceso;
    }

    /**
     * Valida si un permiso específico está disponible
     * 
     * @param string $permiso Nombre del permiso a validar
     * @return bool True si tiene el permiso
     */
    public function tiene(string $permiso): bool
    {
        if ($this->esAdmin()) {
            return true; // Admin tiene todos los permisos
        }

        return in_array($permiso, $this->permisos ?? []);
    }

    /**
     * Registra el acceso al portal del cliente
     * 
     * @param string|null $ip Dirección IP del acceso
     * @return void
     */
    public function registrarAccesoPortal(?string $ip = null): void
    {
        $this->update([
            'ultimo_acceso_portal' => now(),
            'ip_ultimo_acceso' => $ip,
        ]);
    }
}
