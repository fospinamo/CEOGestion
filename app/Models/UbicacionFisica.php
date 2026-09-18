<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UbicacionFisica extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones_fisicas';

    protected $fillable = [
        'empresa_id',
        'dependencia_id',
        'nombre',
        'codigo',
        'tipo_archivo',
        'estanteria',
        'fila',
        'nivel',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function dependencia(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }
}
