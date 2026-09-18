<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SgdDocumento extends Model
{
    use HasFactory;

    protected $table = 'sgd_documentos';

    protected $fillable = [
        'trd_detalle_id',
        'empresa_id',
        'numero_documento',
        'fecha_documento',
        'ubicacion_actual_id',
        'dependencia_creadora_id',
        'dependencia_destino_id',
        'descripcion',
        'volumen',
        'soporte',
        'fecha_ingreso_archivo',
        'estado_documento',
    ];

    protected $casts = [
        'fecha_documento' => 'date',
        'fecha_ingreso_archivo' => 'date',
    ];

    public function trdDetalle(): BelongsTo
    {
        return $this->belongsTo(TrdDetalle::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function ubicacionActual(): BelongsTo
    {
        return $this->belongsTo(UbicacionFisica::class, 'ubicacion_actual_id');
    }

    public function dependenciaCreadora(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_creadora_id');
    }

    public function dependenciaDestino(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_destino_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('estado_documento', 'Activo');
    }
}
