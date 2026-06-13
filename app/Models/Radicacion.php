<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Radicacion extends Model
{
    use HasFactory;

    protected $table = 'radicaciones';

    protected $fillable = [
        'empresa_id',
        'sede_id',
        'documento_id',
        'numero',
        'fecha_radicacion',
        'tipo',
        'remitente',
        'asunto',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'fecha_radicacion' => 'date',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function digitalizaciones(): HasMany
    {
        return $this->hasMany(Digitalizacion::class);
    }
}
