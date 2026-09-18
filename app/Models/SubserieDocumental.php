<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubserieDocumental extends Model
{
    use HasFactory;

    protected $table = 'subseries_documentales';

    protected $fillable = [
        'nombre',
        'codigo',
        'serie_id',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function serie(): BelongsTo
    {
        return $this->belongsTo(SerieDocumental::class, 'serie_id');
    }

    public function tiposDocumentales(): HasMany
    {
        return $this->hasMany(TipoDocumental::class, 'subserie_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }
}
