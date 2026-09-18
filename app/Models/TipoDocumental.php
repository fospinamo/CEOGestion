<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipoDocumental extends Model
{
    use HasFactory;

    protected $table = 'tipos_documentales';

    protected $fillable = [
        'nombre',
        'codigo',
        'subserie_id',
        'serie_id',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function subserie(): BelongsTo
    {
        return $this->belongsTo(SubserieDocumental::class, 'subserie_id');
    }

    public function serie(): BelongsTo
    {
        return $this->belongsTo(SerieDocumental::class, 'serie_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }
}
