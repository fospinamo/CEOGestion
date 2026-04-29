<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo TipoEquipo
 * 
 * Representa un tipo de equipo TI disponible en el catálogo.
 * Utilizado para clasificar y estandarizar equipos en el sistema.
 * 
 * Un tipo de equipo puede estar asociado a una categoría parametrizable.
 * 
 * @property int $id
 * @property int|null $categoria_id FK a categorías (parametrizable)
 * @property string $nombre Nombre único del tipo
 * @property string|null $descripcion Descripción detallada
 * @property string|null $categoria Campo legacy (será deprecado)
 * @property string|null $icono Ícono Font Awesome
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class TipoEquipo extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla
     */
    protected $table = 'tipos_equipos';

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'categoria',
        'icono',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relaciones
     * ============================================
     */

    /**
     * Un tipo de equipo pertenece a una categoría
     */
    public function categoriaObj(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * Equipos que usan este tipo
     */
    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class);
    }

    /**
     * Scopes
     * ============================================
     */

    /**
     * Filtrar por categoría ID
     */
    public function scopePorCategoriaId($query, $categoriaId)
    {
        return $query->where('categoria_id', $categoriaId);
    }

    /**
     * Filtrar por categoría (campo legacy)
     */
    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Métodos helper
     * ============================================
     */

    /**
     * Obtener lista de categorías disponibles (legacy)
     * 
     * @deprecated Usar Categoria::activas()->get() en su lugar
     */
    public static function categorias()
    {
        return [
            'HARDWARE' => 'Hardware',
            'SOFTWARE' => 'Software',
            'RED' => 'Red',
            'PERIFERICO' => 'Periférico',
            'OTRO' => 'Otro',
        ];
    }
}

