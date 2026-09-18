<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TablaRetencionDocumental extends Model
{
    use HasFactory;

    protected $table = 'tablas_retencion_documental';

    protected $fillable = [
        'empresa_id',
        'consecutivo',
        'nombre',
        'descripcion',
        'fecha_creacion',
        'fecha_aprobacion',
        'aprobado_comite',
        'convalidado_agn',
        'estado',
        'observacion',
        'usuario_crea_id',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'aprobado_comite' => 'boolean',
        'convalidado_agn' => 'boolean',
        'fecha_creacion' => 'date',
        'fecha_aprobacion' => 'date',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function usuarioCrea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_crea_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(TrdDetalle::class, 'tabla_retencion_documental_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }
}
