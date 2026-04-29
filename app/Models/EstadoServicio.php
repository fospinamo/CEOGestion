<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo EstadoServicio
 * 
 * Define los posibles estados en que puede estar un servicio.
 * Los técnicos seleccionan estos estados al completar un servicio.
 * 
 * @property int $id
 * @property string $nombre Nombre del estado (Ej: "Pendiente", "En Proceso", "Cerrado")
 * @property string|null $descripcion Descripción del estado
 * @property string $color Color hexadecimal para UI (#RRGGBB)
 * @property bool $es_cierre Si marca como cerrado
 * @property bool $es_pendiente_repuesto Si está pendiente de repuesto
 * @property bool $es_en_proceso Si está en proceso
 * @property int $orden Orden de aparición en selects
 * @property bool $activo Estado activo del registro
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class EstadoServicio extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'color',
        'es_cierre',
        'es_pendiente_repuesto',
        'es_en_proceso',
        'orden',
        'activo',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'es_cierre' => 'boolean',
        'es_pendiente_repuesto' => 'boolean',
        'es_en_proceso' => 'boolean',
        'activo' => 'boolean',
    ];

    /**
     * Relaciones
     */

    /**
     * Servicios con este estado
     */
    public function servicios(): HasMany
    {
        return $this->hasMany(Servicio::class, 'estado_servicio_id');
    }

    /**
     * Scope para estados activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }
}
