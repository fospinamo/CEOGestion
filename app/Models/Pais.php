<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pais extends Model
{
    protected $fillable = ['codigo_dane', 'nombre'];

    protected $table = 'paises';

    public $timestamps = true;

    /**
     * Relación: Un país tiene muchos departamentos
     */
    public function departamentos(): HasMany
    {
        return $this->hasMany(Departamento::class, 'pais_id');
    }
}
