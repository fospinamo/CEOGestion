<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Contrato
 * 
 * Representa un contrato de servicios TI entre empresa y cliente.
 * Registra términos, valores, alcance y documentación.
 * 
 * @property int $id
 * @property int $cliente_id Cliente contratante
 * @property string $numero_contrato Número único
 * @property \Illuminate\Support\Carbon $fecha_inicio Inicio
 * @property \Illuminate\Support\Carbon $fecha_fin Fin prevista
 * @property \Illuminate\Support\Carbon|null $fecha_firma Fecha de firma
 * @property \Illuminate\Support\Carbon|null $fecha_terminacion Terminación real
 * @property string $tipo_contrato Tipo (SOPORTE_TI, MANTENIMIENTO, INFRAESTRUCTURA, CONSULTORIA)
 * @property string $modalidad Modalidad (MENSUAL, TRIMESTRAL, SEMESTRAL, ANUAL)
 * @property float $valor_contrato Valor total
 * @property string $moneda Moneda (COP, USD, EUR)
 * @property string|null $condiciones_pago Condiciones de pago
 * @property string|null $alcance_servicios Alcance
 * @property string|null $clausulas_especiales Cláusulas
 * @property string|null $documento_pdf Ruta PDF
 * @property bool $documento_firmado ¿Firmado?
 * @property string $estado Estado (BORRADOR, ACTIVO, VENCIDO, TERMINADO, RENOVADO)
 * @property bool $renovacion_automatica ¿Renovación automática?
 * @property int $created_by Usuario creador
 * @property int|null $updated_by Último usuario modificador
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Contrato extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'cliente_id',
        'numero_contrato',
        'fecha_inicio',
        'fecha_fin',
        'fecha_firma',
        'fecha_terminacion',
        'tipo_contrato',
        'modalidad',
        'valor_contrato',
        'moneda',
        'condiciones_pago',
        'alcance_servicios',
        'clausulas_especiales',
        'documento_pdf',
        'documento_firmado',
        'estado',
        'renovacion_automatica',
        'created_by',
        'updated_by',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_firma' => 'date',
        'fecha_terminacion' => 'date',
        'documento_firmado' => 'boolean',
        'renovacion_automatica' => 'boolean',
        'valor_contrato' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relaciones
     * ============================================
     */

    /**
     * Cliente del contrato
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Usuario que creó el contrato
     */
    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Último usuario que modificó
     */
    public function modificadoPor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Servicios asociados
     */
    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    /**
     * Equipos asociados al contrato
     */
    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    /**
     * Documentos adjuntos (polimórficos)
     */
    public function documentosAdjuntos()
    {
        return $this->morphMany(DocumentoAdjunto::class, 'entidad');
    }

    /**
     * Scopes
     * ============================================
     */

    /**
     * Solo contratos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'ACTIVO')
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now());
    }

    /**
     * Contratos vencidos
     */
    public function scopeVencidos($query)
    {
        return $query->where('fecha_fin', '<', now());
    }

    /**
     * Contratos por cliente
     */
    public function scopePorCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    /**
     * Contratos por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_contrato', $tipo);
    }

    /**
     * Contratos por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Métodos helper
     * ============================================
     */

    /**
     * Obtener número de contratos activos
     */
    public static function contratosActivosCount()
    {
        return self::activos()->count();
    }

    /**
     * Obtener tipos de contrato disponibles
     */
    public static function tiposContrato()
    {
        return [
            'SOPORTE_TI' => 'Soporte TI',
            'MANTENIMIENTO' => 'Mantenimiento',
            'INFRAESTRUCTURA' => 'Infraestructura',
            'CONSULTORIA' => 'Consultoría',
        ];
    }

    /**
     * Obtener modalidades disponibles
     */
    public static function modalidades()
    {
        return [
            'MENSUAL' => 'Mensual',
            'TRIMESTRAL' => 'Trimestral',
            'SEMESTRAL' => 'Semestral',
            'ANUAL' => 'Anual',
        ];
    }

    /**
     * Obtener monedas disponibles
     */
    public static function monedas()
    {
        return [
            'COP' => 'Peso Colombiano',
            'USD' => 'Dólar USD',
            'EUR' => 'Euro',
        ];
    }

    /**
     * Obtener estados disponibles
     */
    public static function estados()
    {
        return [
            'BORRADOR' => 'Borrador',
            'ACTIVO' => 'Activo',
            'VENCIDO' => 'Vencido',
            'TERMINADO' => 'Terminado',
            'RENOVADO' => 'Renovado',
        ];
    }

    /**
     * ¿Contrato está vigente?
     */
    public function esVigente()
    {
        return $this->estado === 'ACTIVO' &&
            $this->fecha_inicio <= now() &&
            $this->fecha_fin >= now();
    }

    /**
     * ¿Está próximo a vencer? (30 días)
     */
    public function proximoAVencer()
    {
        return $this->fecha_fin->diffInDays(now()) <= 30 &&
            $this->fecha_fin->diffInDays(now()) >= 0;
    }

    /**
     * Valor en formato moneda
     */
    public function getValorFormateadoAttribute()
    {
        $simbolo = match ($this->moneda) {
            'USD' => '$',
            'EUR' => '€',
            default => '₡'
        };

        return "{$simbolo} " . number_format($this->valor_contrato, 2);
    }
}
