<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dependencia extends Model
{
    use HasFactory;

    protected $table = 'dependencias';

    protected $fillable = [
        'nombre',
        'codigo',
        'empresa_id',
        'dependencia_padre_id',
        'responsable',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function dependenciaPadre(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_padre_id');
    }

    public function subdependencias(): HasMany
    {
        return $this->hasMany(Dependencia::class, 'dependencia_padre_id');
    }

    public function series(): HasMany
    {
        return $this->hasMany(SerieDocumental::class, 'dependencia_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }
}
