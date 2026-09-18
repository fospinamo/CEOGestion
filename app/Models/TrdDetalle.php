<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrdDetalle extends Model
{
    use HasFactory;

    protected $table = 'trd_detalle';

    protected $fillable = [
        'tabla_retencion_documental_id',
        'dependencia',
        'serie',
        'subserie',
        'tipo_documental',
        'archivo_gestion_tiempo',
        'archivo_central_tiempo',
        'disposicion_final',
        'observaciones',
        'orden',
        'dependencia_id',
        'serie_id',
        'subserie_id',
        'tipo_documental_id',
        'ubicacion_gestion_id',
        'ubicacion_central_id',
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    public function tablaRetencionDocumental(): BelongsTo
    {
        return $this->belongsTo(TablaRetencionDocumental::class, 'tabla_retencion_documental_id');
    }

    public function dependenciaRef(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_id');
    }

    public function serieRef(): BelongsTo
    {
        return $this->belongsTo(SerieDocumental::class, 'serie_id');
    }

    public function subserieRef(): BelongsTo
    {
        return $this->belongsTo(SubserieDocumental::class, 'subserie_id');
    }

    public function tipoDocumentalRef(): BelongsTo
    {
        return $this->belongsTo(TipoDocumental::class, 'tipo_documental_id');
    }

    public function ubicacionGestion(): BelongsTo
    {
        return $this->belongsTo(UbicacionFisica::class, 'ubicacion_gestion_id');
    }

    public function ubicacionCentral(): BelongsTo
    {
        return $this->belongsTo(UbicacionFisica::class, 'ubicacion_central_id');
    }

    public function getDisposicionFinalLabelAttribute(): string
    {
        return match ($this->disposicion_final) {
            'CP' => 'Conservación Permanente',
            'EL' => 'Eliminación',
            'D' => 'Digitalización',
            default => $this->disposicion_final,
        };
    }
}
