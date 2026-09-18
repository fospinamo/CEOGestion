<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SerieDocumental extends Model
{
    use HasFactory;

    protected $table = 'series_documentales';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'dependencia_id',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function dependencia(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function subseries(): HasMany
    {
        return $this->hasMany(SubserieDocumental::class, 'serie_id');
    }

    public function tiposDocumentales(): HasMany
    {
        return $this->hasMany(TipoDocumental::class, 'serie_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }
}
