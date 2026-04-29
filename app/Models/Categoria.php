<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Categoria
 * 
 * Representa una categoría parametrizable para tipos de equipos.
 * Permite que los administradores creen nuevas categorías sin modificar código.
 * 
 * Categorías predeterminadas:
 * - HARDWARE: Computadoras, servidores, periféricos físicos
 * - SOFTWARE: Licencias, aplicaciones, sistemas operativos
 * - RED: Routers, switches, cableado, dispositivos de conectividad
 * - PERIFERICO: Impresoras, escáneres, monitores
 * - OTRO: Categoría genérica para equipos no clasificados
 * 
 * @property int $id
 * @property string $nombre Nombre único de la categoría (ej: HARDWARE)
 * @property string $slug Slug URL-friendly (ej: hardware)
 * @property string|null $descripcion Descripción detallada
 * @property string|null $icono Icono Font Awesome (ej: fa-desktop)
 * @property string $color Código hexadecimal del color (#3b82f6)
 * @property bool $estado Indica si está activa
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'icono',
        'color',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    /**
     * Relaciones
     * ============================================
     */

    /**
     * Una categoría tiene muchos tipos de equipos
     */
    public function tiposEquipos(): HasMany
    {
        return $this->hasMany(TipoEquipo::class, 'categoria_id');
    }

    /**
     * Scopes
     * ============================================
     */

    /**
     * Filtrar categorías activas
     * 
     * Uso: Categoria::activas()->get()
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }

    /**
     * Métodos Helper
     * ============================================
     */

    /**
     * Obtener el atributo de ruta (slug)
     * 
     * Usado en rutas implícitas
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Obtener cantidad de tipos de equipos
     */
    public function cantidadTipos(): int
    {
        return $this->tiposEquipos()->count();
    }

    /**
     * Generar slug automáticamente desde el nombre
     */
    public static function generarSlug(string $nombre): string
    {
        return strtolower(str_replace([' ', '-'], '_', trim($nombre)));
    }
}
