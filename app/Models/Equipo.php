<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Equipo
 * 
 * Representa un equipo TI registrado en el inventario.
 * Almacena información técnica, estado operativo y ubicación.
 * 
 * @property int $id
 * @property int $area_id Área donde se encuentra
 * @property int $tipo_equipo_id Tipo de equipo
 * @property int|null $marca_id Marca/Fabricante
 * @property int|null $cliente_id Cliente propietario
 * @property int|null $sede_id Sede donde se ubica
 * @property int|null $contrato_id Contrato de servicios asociado
 * @property string $codigo_activo_cliente Código de activo del cliente
 * @property string|null $modelo Modelo
 * @property string|null $serial Número de serie (único)
 * @property \Illuminate\Support\Carbon|null $fecha_compra Fecha de compra
 * @property \Illuminate\Support\Carbon|null $fecha_instalacion Instalación
 * @property \Illuminate\Support\Carbon|null $fecha_garantia Vencimiento de garantía
 * @property float|null $valor_compra Valor de compra
 * @property string $estado_operativo Estado (OPERATIVO, MANTENIMIENTO, REPARACION, BAJA, OBSOLETO)
 * @property array|null $especificaciones_tecnicas Specs JSON
 * @property string|null $ip_asignada IP asignada
 * @property string|null $mac_address MAC address
 * @property string|null $usuario_asignado Usuario final
 * @property string|null $descripcion Descripción detallada
 * @property string|null $observaciones Notas
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Equipo extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'cliente_id',
        'contrato_id',
        'sede_id',
        'area_id',
        'tipo_equipo_id',
        'marca_id',
        'codigo_activo_cliente',
        'modelo',
        'serial',
        'fecha_compra',
        'fecha_instalacion',
        'fecha_garantia',
        'valor_compra',
        'estado_operativo',
        'especificaciones_tecnicas',
        'ip_asignada',
        'mac_address',
        'usuario_asignado',
        'descripcion',
        'observaciones',
        'mantenimientos_anuales',
        'calibraciones_anuales',
        'fecha_ultimo_mantenimiento',
        'fecha_ultima_calibracion',
        'proxima_fecha_mantenimiento',
        'proxima_fecha_calibracion',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'fecha_compra' => 'date',
        'fecha_instalacion' => 'date',
        'fecha_garantia' => 'date',
        'valor_compra' => 'float',
        'especificaciones_tecnicas' => 'array',
        'fecha_ultimo_mantenimiento' => 'date',
        'fecha_ultima_calibracion' => 'date',
        'proxima_fecha_mantenimiento' => 'date',
        'proxima_fecha_calibracion' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relaciones
     * ============================================
     */

    /**
     * Cliente propietario del equipo
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Contrato de servicios asociado
     */
    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    /**
     * Sede donde se ubica el equipo
     */
    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    /**
     * Área a la que pertenece
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Tipo de equipo
     */
    public function tipoEquipo()
    {
        return $this->belongsTo(TipoEquipo::class);
    }

    /**
     * Marca del equipo
     */
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    /**
     * Servicios asociados
     */
    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    /**
     * Mantenimientos programados
     */
    public function mantenimientosProgramados()
    {
        return $this->hasMany(MantenimientoProgramado::class, 'equipo_id');
    }

    /**
     * Documentos del equipo
     */
    public function documentos()
    {
        return $this->hasMany(EquipoDocumento::class, 'equipo_id');
    }

    /**
     * Mantenimientos y calibraciones programadas
     */
    public function mantenimientosCalibraciónes()
    {
        return $this->hasMany(MantenimientoCalibración::class, 'equipo_id');
    }

    /**
     * Scopes
     * ============================================
     */

    /**
     * Solo equipos operativos
     */
    public function scopeOperativos($query)
    {
        return $query->where('estado_operativo', 'OPERATIVO');
    }

    /**
     * Equipos fuera de servicio
     */
    public function scopeFueraDeServicio($query)
    {
        return $query->whereIn('estado_operativo', ['BAJA', 'OBSOLETO']);
    }

    /**
     * Equipos en mantenimiento
     */
    public function scopeEnMantenimiento($query)
    {
        return $query->whereIn('estado_operativo', ['MANTENIMIENTO', 'REPARACION']);
    }

    /**
     * Equipos con garantía vencida
     */
    public function scopeGarantiaVencida($query)
    {
        return $query->where('fecha_garantia', '<', now());
    }

    /**
     * Equipos con garantía próxima a vencer
     */
    public function scopeGarantiaProxima($query)
    {
        return $query->whereBetween('fecha_garantia', [now(), now()->addDays(30)]);
    }

    /**
     * Filtrar por tipo
     */
    public function scopePorTipo($query, $tipoId)
    {
        return $query->where('tipo_equipo_id', $tipoId);
    }

    /**
     * Filtrar por área
     */
    public function scopePorArea($query, $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    /**
     * Filtrar por usuario
     */
    public function scopePorUsuario($query, $usuario)
    {
        return $query->where('usuario_asignado', $usuario);
    }

    /**
     * Métodos helper
     * ============================================
     */

    /**
     * Obtener estados operativos disponibles
     */
    public static function estadosOperativos()
    {
        return [
            'OPERATIVO' => 'Operativo',
            'MANTENIMIENTO' => 'Mantenimiento',
            'REPARACION' => 'Reparación',
            'BAJA' => 'Baja',
            'OBSOLETO' => 'Obsoleto',
        ];
    }

    /**
     * ¿Tiene garantía válida?
     */
    public function tieneGarantia()
    {
        return $this->fecha_garantia && $this->fecha_garantia >= now();
    }

    /**
     * ¿Requiere mantenimiento urgente?
     */
    public function requiereMantenimiento()
    {
        return in_array($this->estado_operativo, ['MANTENIMIENTO', 'REPARACION']);
    }

    /**
     * Obtener información completa en formato string
     */
    public function getInfoCompletoAttribute()
    {
        return "{$this->marca} {$this->modelo} - SN: {$this->serial}";
    }

    /**
     * Obtener edad del equipo en años
     */
    public function getEdadEquipoAttribute()
    {
        if (!$this->fecha_compra) {
            return null;
        }

        $años = $this->fecha_compra->diffInYears(now());
        $meses = $this->fecha_compra->diffInMonths(now()) % 12;

        return "{$años}a {$meses}m";
    }

    /**
     * Obtener detalles técnicos formateados
     */
    public function getDetallesTecnicosFormateadosAttribute()
    {
        if (!$this->especificaciones_tecnicas) {
            return 'Sin especificaciones registradas';
        }

        $detalles = [];
        foreach ($this->especificaciones_tecnicas as $clave => $valor) {
            $detalles[] = ucfirst($clave) . ": " . $valor;
        }

        return implode(' | ', $detalles);
    }
}
