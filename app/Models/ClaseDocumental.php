<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClaseDocumental extends Model
{
    use HasFactory;

    protected $table = 'clases_documentales';

    protected $fillable = [
        'consecutivo',
        'descripcion',
        'estado',
        'version',
        'observacion',
        'realizado_por_id',
        'registrado_por_id',
        'revisado_por_id',
        'imagen',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function realizadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'realizado_por_id');
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por_id');
    }

    public function revisadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por_id');
    }

    public function historiales(): HasMany
    {
        return $this->hasMany(ClaseDocumentalHistorial::class);
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }
}
