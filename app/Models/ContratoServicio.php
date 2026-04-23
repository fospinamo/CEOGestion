<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContratoServicio extends Model
{
    use HasFactory;

    protected $table = 'contrato_servicios';

    protected $fillable = [
        'contrato_id', 'tipo_servicio', 'incluido', 
        'costo_adicional', 'sla_horas_respuesta', 'sla_horas_solucion'
    ];

    protected $casts = [
        'incluido' => 'boolean',
        'costo_adicional' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }
}
