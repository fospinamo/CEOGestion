<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Sede
 * 
 * Representa una ubicación física que puede pertenecer a:
 * - La empresa (CEOGestion): sedes propias, data centers, oficinas
 * - Un cliente: sucursales, ubicaciones del cliente donde se instalan equipos
 * 
 * Validación: Una sede debe pertenecer a UNA Y SOLO UNA entidad
 * - Si es sede de empresa: empresa_id NOT NULL, cliente_id NULL
 * - Si es sede de cliente: cliente_id NOT NULL, empresa_id NULL
 * 
 * Una sede:
 * - Está ubicada en un municipio y opcionalmente en un barrio
 * - Contiene áreas con equipos TI
 * - Puede tener múltiples usuarios asignados
 * - Contiene equipos ubicados en ella
 * 
 * Relaciones:
 * - empresa: La empresa dueña de la sede (BelongsTo) - nullable
 * - cliente: El cliente dueño de la sede (BelongsTo) - nullable
 * - municipio: Ubicación del municipio (BelongsTo)
 * - barrio: Ubicación del barrio - opcional (BelongsTo)
 * - usuarios: Usuarios asignados a la sede (HasMany)
 * - areas: Áreas funcionales dentro de la sede (HasMany)
 */
class Sede extends Model
{
    use HasFactory;

    protected $table = 'sedes';
    
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'nombre',
        'codigo',
        'direccion',
        'municipio_id',
        'barrio_id',
        'codigo_postal',
        'telefono',
        'email',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    /**
     * Relaciones
     * ============================================
     */

    /**
     * Una sede pertenece a una empresa (si es sede de empresa)
     * 
     * @return BelongsTo
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Una sede pertenece a un cliente (si es sede de cliente)
     * 
     * @return BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Una sede pertenece a un municipio
     */
    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    /**
     * Una sede pertenece a un barrio (opcional)
     */
    public function barrio(): BelongsTo
    {
        return $this->belongsTo(Barrio::class);
    }

    /**
     * Una sede tiene muchos usuarios
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Una sede tiene muchas áreas
     */
    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }

    /**
     * Validadores personalizados
     * ============================================
     */

    /**
     * Valida que la sede pertenezca a UNA Y SOLO UNA entidad (empresa o cliente)
     * 
     * @return bool
     * @throws \Exception Si ni empresa_id ni cliente_id están seteados
     */
    public function validarPropietario(): bool
    {
        $tieneEmpresa = !is_null($this->empresa_id);
        $tieneCliente = !is_null($this->cliente_id);

        // Ambas null: error
        if (!$tieneEmpresa && !$tieneCliente) {
            throw new \Exception('La sede debe pertenecer a una empresa O a un cliente');
        }

        // Ambas seteadas: error (no permitido)
        if ($tieneEmpresa && $tieneCliente) {
            throw new \Exception('La sede no puede pertenecer simultáneamente a empresa y cliente');
        }

        return true;
    }

    /**
     * Scopes
     * ============================================
     */

    /**
     * Filtrar sedes activas
     * 
     * Retorna solo sedes con estado = true
     * 
     * Uso: Sede::activas()->get()
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }

    /**
     * Filtrar sedes de empresa
     * 
     * Retorna todas las sedes propias de la empresa (empresa_id NOT NULL)
     * 
     * Uso: Sede::deEmpresa()->get()
     * Uso: Sede::deEmpresa($empresaId)->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $empresaId ID de la empresa (opcional)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeEmpresa($query, $empresaId = null)
    {
        $query->whereNotNull('empresa_id')->whereNull('cliente_id');
        
        if ($empresaId) {
            $query->where('empresa_id', $empresaId);
        }
        
        return $query;
    }

    /**
     * Filtrar sedes de cliente
     * 
     * Retorna todas las sedes que pertenecen a clientes (cliente_id NOT NULL)
     * 
     * Uso: Sede::deCliente()->get()
     * Uso: Sede::deCliente($clienteId)->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $clienteId ID del cliente (opcional)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeCliente($query, $clienteId = null)
    {
        $query->whereNotNull('cliente_id')->whereNull('empresa_id');
        
        if ($clienteId) {
            $query->where('cliente_id', $clienteId);
        }
        
        return $query;
    }

    /**
     * Métodos helper
     * ============================================
     */

    /**
     * Obtener el propietario de la sede (empresa o cliente)
     * 
     * @return Empresa|Cliente|null
     */
    public function propietario()
    {
        return $this->empresa ?? $this->cliente;
    }

    /**
     * Verificar si es sede de empresa
     * 
     * @return bool
     */
    public function esDeEmpresa(): bool
    {
        return !is_null($this->empresa_id) && is_null($this->cliente_id);
    }

    /**
     * Verificar si es sede de cliente
     * 
     * @return bool
     */
    public function esDeCliente(): bool
    {
        return !is_null($this->cliente_id) && is_null($this->empresa_id);
    }

    /**
     * Obtener cantidad total de equipos en todas las áreas de la sede
     * 
     * Calcula el total de equipos (independientemente de su estado)
     * en todas las áreas funcionales de esta sede
     * 
     * @return int Cantidad total de equipos
     */
    public function cantidadEquipos()
    {
        return $this->areas()
            ->with('equipos')
            ->get()
            ->sum(fn($area) => $area->equipos->count());
    }

    /**
     * Obtener cantidad de equipos operativos en la sede
     * 
     * Retorna solo los equipos que están en estado 'OPERATIVO'
     * en todas las áreas de esta sede
     * 
     * @return int Cantidad de equipos en estado operativo
     */
    public function equiposOperativos()
    {
        return $this->areas()
            ->with('equipos')
            ->get()
            ->sum(fn($area) => $area->equipos->where('estado_operativo', 'OPERATIVO')->count());
    }
}
