<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipio extends Model
{
    protected $fillable = ['codigo_dane', 'nombre', 'departamento_id'];

    protected $table = 'municipios';

    public $timestamps = true;

    /**
     * Relación: Un municipio pertenece a un departamento
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    /**
     * Relación: Un municipio tiene muchos barrios
     */
    public function barrios(): HasMany
    {
        return $this->hasMany(Barrio::class, 'municipio_id');
    }

    /**
     * Relación: Un municipio tiene muchas sedes
     */
    public function sedes(): HasMany
    {
        return $this->hasMany(Sede::class, 'municipio_id');
    }
}
