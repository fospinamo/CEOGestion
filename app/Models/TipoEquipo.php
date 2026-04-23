<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo TipoEquipo
 * 
 * Representa un tipo de equipo TI disponible en el catálogo.
 * Utilizado para clasificar y estandarizar equipos en el sistema.
 * 
 * @property int $id
 * @property string $nombre Nombre único del tipo
 * @property string|null $descripcion Descripción detallada
 * @property string $categoria Categoría (HARDWARE, SOFTWARE, RED, PERIFERICO, OTRO)
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
     * Equipos que usan este tipo
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
     * Filtrar por categoría
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
     * Obtener lista de categorías disponibles
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
