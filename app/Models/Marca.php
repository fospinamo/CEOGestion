<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Marca
 * 
 * Representa las marcas/fabricantes de equipos TI disponibles en el sistema.
 * Permite parametrizar las marcas sin necesidad de código.
 * 
 * @property int $id
 * @property string $nombre Nombre de la marca
 * @property string|null $descripcion Descripción
 * @property string|null $logo_url URL del logo
 * @property bool $estado Marca activa
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'logo_url', 'estado'];

    protected $casts = [
        'estado' => 'boolean',
    ];

    /**
     * Relación: Una marca tiene muchos equipos
     */
    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'marca_id');
    }

    /**
     * Scopes
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }
}
