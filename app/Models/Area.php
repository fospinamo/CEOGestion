<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Area
 * 
 * Representa un área o departamento dentro de una sede.
 * Permite organizar equipos y asignaciones de responsables.
 * 
 * @property int $id
 * @property int $sede_id Sede a la que pertenece
 * @property string $nombre Nombre del área
 * @property string|null $descripcion Descripción
 * @property string|null $responsable_nombre Responsable
 * @property string|null $responsable_contacto Contacto responsable
 * @property string $nivel_riesgo Nivel (BAJO, MEDIO, ALTO, CRITICO)
 * @property bool $estado Área activa
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Area extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'sede_id',
        'nombre',
        'descripcion',
        'responsable_nombre',
        'responsable_contacto',
        'nivel_riesgo',
        'estado',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'estado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relaciones
     * ============================================
     */

    /**
     * Sede a la que pertenece
     */
    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    /**
     * Equipos en esta área
     */
    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    /**
     * Scopes
     * ============================================
     */

    /**
     * Solo áreas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }

    /**
     * Filtrar por riesgo
     */
    public function scopePorRiesgo($query, $nivel)
    {
        return $query->where('nivel_riesgo', $nivel);
    }

    /**
     * Filtrar por sede
     */
    public function scopePorSede($query, $sedeId)
    {
        return $query->where('sede_id', $sedeId);
    }

    /**
     * Áreas de alto riesgo
     */
    public function scopeAltoRiesgo($query)
    {
        return $query->whereIn('nivel_riesgo', ['ALTO', 'CRITICO']);
    }

    /**
     * Métodos helper
     * ============================================
     */

    /**
     * Obtener niveles de riesgo disponibles
     */
    public static function nivelesRiesgo()
    {
        return [
            'BAJO' => 'Bajo',
            'MEDIO' => 'Medio',
            'ALTO' => 'Alto',
            'CRITICO' => 'Crítico',
        ];
    }

    /**
     * Obtener cantidad de equipos
     */
    public function cantidadEquipos()
    {
        return $this->equipos()->count();
    }

    /**
     * Obtener equipos en reparación
     */
    public function equiposEnReparacion()
    {
        return $this->equipos()->whereIn('estado_operativo', ['MANTENIMIENTO', 'REPARACION'])->count();
    }

    /**
     * Badge de riesgo
     */
    public function getBadgeRiesgoAttribute()
    {
        return match ($this->nivel_riesgo) {
            'BAJO' => '<span class="badge badge-success">Bajo</span>',
            'MEDIO' => '<span class="badge badge-warning">Medio</span>',
            'ALTO' => '<span class="badge badge-danger">Alto</span>',
            'CRITICO' => '<span class="badge badge-dark">Crítico</span>',
            default => 'N/A'
        };
    }
}
