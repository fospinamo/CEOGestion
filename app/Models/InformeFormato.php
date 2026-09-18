<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InformeFormato extends Model
{
    use HasFactory;

    protected $table = 'informe_formatos';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'blade_template',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
