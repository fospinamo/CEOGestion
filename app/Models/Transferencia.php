<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transferencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_transferencia',
        'documento_id',
        'ubicacion_origen_id',
        'ubicacion_destino_id',
        'tipo_transferencia',
        'usuario_responsable_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha_transferencia' => 'date',
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(SgdDocumento::class, 'documento_id');
    }

    public function ubicacionOrigen(): BelongsTo
    {
        return $this->belongsTo(UbicacionFisica::class, 'ubicacion_origen_id');
    }

    public function ubicacionDestino(): BelongsTo
    {
        return $this->belongsTo(UbicacionFisica::class, 'ubicacion_destino_id');
    }

    public function usuarioResponsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_responsable_id');
    }
}
