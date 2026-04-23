<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Sede
 * 
 * Representa una sucursal o ubicación de una empresa.
 * Una sede pertenece a una empresa y opcionalmente a un cliente.
 * Contiene áreas y equipos TI.
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
     * Una sede pertenece a una empresa (proveedora de servicios)
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Una sede puede pertenecer a un cliente (quien contrata servicios)
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
     * Scopes
     * ============================================
     */

    /**
     * Solo sedes activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }

    /**
     * Filtrar por empresa
     */
    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    /**
     * Filtrar por cliente
     */
    public function scopePorCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    /**
     * Métodos helper
     * ============================================
     */

    /**
     * Obtener cantidad total de equipos en la sede
     */
    public function cantidadEquipos()
    {
        return $this->areas()
            ->with('equipos')
            ->get()
            ->sum(fn($area) => $area->equipos->count());
    }

    /**
     * Obtener equipos operativos
     */
    public function equiposOperativos()
    {
        return $this->areas()
            ->with('equipos')
            ->get()
            ->sum(fn($area) => $area->equipos->where('estado_operativo', 'OPERATIVO')->count());
    }
}
