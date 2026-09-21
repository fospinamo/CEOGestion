<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotizacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cliente_id',
        'numero_cotizacion',
        'fecha_cotizacion',
        'fecha_vencimiento',
        'tipo_servicio',
        'valor_subtotal',
        'valor_iva',
        'valor_total',
        'moneda',
        'descripcion_servicio',
        'observaciones',
        'estado',
        'archivo_pdf',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha_cotizacion' => 'date',
        'fecha_vencimiento' => 'date',
        'valor_subtotal' => 'float',
        'valor_iva' => 'float',
        'valor_total' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modificadoPor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public static function tiposServicio()
    {
        return [
            'SOPORTE_TI' => 'Soporte TI',
            'MANTENIMIENTO' => 'Mantenimiento',
            'INFRAESTRUCTURA' => 'Infraestructura',
            'CONSULTORIA' => 'Consultoría',
        ];
    }

    public static function estados()
    {
        return [
            'BORRADOR' => 'Borrador',
            'ENVIADA' => 'Enviada',
            'APROBADA' => 'Aprobada',
            'RECHAZADA' => 'Rechazada',
            'VENCIDA' => 'Vencida',
        ];
    }

    public static function monedas()
    {
        return [
            'COP' => 'Peso Colombiano',
            'USD' => 'Dólar USD',
            'EUR' => 'Euro',
        ];
    }

    public function getValorFormateadoAttribute()
    {
        $simbolo = match ($this->moneda) {
            'USD' => '$',
            'EUR' => '€',
            default => '$',
        };

        return $simbolo . ' ' . number_format($this->valor_total, 0, ',', '.');
    }
}
