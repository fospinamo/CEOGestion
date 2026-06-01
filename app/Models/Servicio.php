<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Servicio
 * 
 * Representa un ticket de servicio TI (soporte, mantenimiento, etc.)
 * Registra el ciclo completo del servicio desde solicitud hasta cierre.
 * 
 * @property int $id
 * @property int $equipo_id Equipo asociado
 * @property int|null $contrato_id Contrato bajo el cual se ofrece
 * @property string $tipo_servicio Tipo (PREVENTIVO, CORRECTIVO, INSTALACION, etc.)
 * @property string $prioridad Prioridad (BAJA, MEDIA, ALTA, URGENTE)
 * @property \Illuminate\Support\Carbon $fecha_solicitud Cuándo se solicitó
 * @property \Illuminate\Support\Carbon|null $fecha_atencion Cuándo se inició
 * @property \Illuminate\Support\Carbon|null $fecha_cierre Cuándo se cerró
 * @property string $solicitado_por Quién solicitó
 * @property string $contacto_solicitante Teléfono/email
 * @property string $descripcion_problema Descripción del problema
 * @property string|null $diagnostico Diagnóstico realizado
 * @property string|null $solucion_aplicada Solución implementada
 * @property array|null $repuestos_utilizados JSON de repuestos
 * @property float|null $horas_trabajadas Horas invertidas
 * @property string $tecnico_asignado Técnico
 * @property string|null $tecnico_cedula Cédula del técnico
 * @property string $estado Estado (PENDIENTE, EN_PROCESO, RESUELTO, CERRADO, CANCELADO)
 * @property int|null $calificacion_cliente Calificación 1-5
 * @property string|null $comentarios_cliente Feedback del cliente
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Servicio extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'equipo_id',
        'contrato_id',
        'tipo_servicio',
        'tipo_servicio_informe',
        'prioridad',
        'fecha_solicitud',
        'fecha_atencion',
        'hora_inicio_atencion',
        'hora_fin_atencion',
        'fecha_cierre',
        'solicitado_por',
        'contacto_solicitante',
        'descripcion_problema',
        'descripcion_solicitud',
        'observaciones',
        'observaciones_informe',
        'diagnostico',
        'diagnostico_validacion',
        'pendientes',
        'solucion_aplicada',
        'repuestos_utilizados',
        'horas_trabajadas',
        'tecnico_asignado',
        'tecnico_cedula',
        'estado',
        'calificacion_cliente',
        'comentarios_cliente',
        'sla_horas_respuesta',
        'sla_horas_solucion',
        'sla_fecha_limite_respuesta',
        'sla_fecha_limite_solucion',
        'alerta_enviada_respuesta',
        'alerta_enviada_solucion',
        'tecnico_asignado_id',
        'fecha_asignacion',
        'fecha_inicio_atencion',
        'fecha_resolucion',
        'fecha_cierre_real',
        // Nuevos campos para atención
        'persona_receptora_nombre',
        'persona_receptora_apellido',
        'persona_receptora_documento',
        'firma_persona_receptora',
        'descripcion_atencion',
        'equipos_adicionales_atendidos',
        'fecha_firma',
        // Nuevos campos para técnico y facturación
        'tecnico_id',
        'estado_servicio_id',
        'puede_facturarse',
        'es_soporte_contrato',
        'imagenes_servicio',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_atencion' => 'date',
        'fecha_cierre' => 'datetime',
        'sla_fecha_limite_respuesta' => 'datetime',
        'sla_fecha_limite_solucion' => 'datetime',
        'fecha_asignacion' => 'datetime',
        'fecha_inicio_atencion' => 'datetime',
        'fecha_resolucion' => 'datetime',
        'fecha_cierre_real' => 'datetime',
        'fecha_firma' => 'datetime',
        'repuestos_utilizados' => 'array',
        'equipos_adicionales_atendidos' => 'array',
        'imagenes_servicio' => 'array',
        'horas_trabajadas' => 'float',
        'calificacion_cliente' => 'integer',
        'alerta_enviada_respuesta' => 'boolean',
        'alerta_enviada_solucion' => 'boolean',
        'puede_facturarse' => 'boolean',
        'es_soporte_contrato' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relaciones
     * ============================================
     */

    /**
     * Equipo asociado
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    /**
     * Contrato asociado
     */
    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    /**
     * Técnico asignado (relación antigua)
     */
    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_asignado_id');
    }

    /**
     * Técnico responsable (nueva relación)
     */
    public function tecnicoResponsable()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    /**
     * Estado del servicio
     */
    public function estadoServicio()
    {
        return $this->belongsTo(EstadoServicio::class, 'estado_servicio_id');
    }

    /**
     * Seguimientos del servicio
     */
    public function seguimientos()
    {
        return $this->hasMany(SeguimientoServicio::class)->orderByDesc('created_at');
    }

    /**
     * Mantenimiento programado asociado (si proviene de cronograma)
     */
    public function mantenimientoProgramado()
    {
        return $this->belongsTo(MantenimientoProgramado::class, 'id', 'servicio_id');
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
     * Solo servicios pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'PENDIENTE')
            ->orWhere('estado', 'EN_PROCESO');
    }

    /**
     * Servicios cerrados
     */
    public function scopeCerrados($query)
    {
        return $query->whereIn('estado', ['CERRADO', 'CANCELADO', 'RESUELTO']);
    }

    /**
     * Servicios urgentes
     */
    public function scopeUrgentes($query)
    {
        return $query->whereIn('prioridad', ['ALTA', 'URGENTE']);
    }

    /**
     * Servicios sin atender
     */
    public function scopeSinAtender($query)
    {
        return $query->where('estado', 'PENDIENTE')
            ->where('fecha_atencion', null);
    }

    /**
     * Servicios por tecnico
     */
    public function scopePorTecnico($query, $tecnico)
    {
        return $query->where('tecnico_asignado', $tecnico);
    }

    /**
     * Servicios por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_servicio', $tipo);
    }

    /**
     * Servicios en rango de fechas
     */
    public function scopeEntreFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('fecha_solicitud', [$desde, $hasta]);
    }

    /**
     * Servicios sin calificar
     */
    public function scopeSinCalificar($query)
    {
        return $query->where('estado', 'CERRADO')
            ->where('calificacion_cliente', null);
    }

    /**
     * Métodos helper
     * ============================================
     */

    /**
     * Obtener tipos de servicio disponibles
     */
    public static function tiposServicio()
    {
        return [
            'PREVENTIVO' => 'Preventivo',
            'CORRECTIVO' => 'Correctivo',
            'INSTALACION' => 'Instalación',
            'CONFIGURACION' => 'Configuración',
            'CAPACITACION' => 'Capacitación',
            'CONSULTA' => 'Consulta',
        ];
    }

    /**
     * Obtener prioridades disponibles
     */
    public static function prioridades()
    {
        return [
            'BAJA' => 'Baja',
            'MEDIA' => 'Media',
            'ALTA' => 'Alta',
            'URGENTE' => 'Urgente',
        ];
    }

    /**
     * Obtener estados disponibles
     */
    public static function estados()
    {
        return [
            'PENDIENTE' => 'Pendiente',
            'EN_PROCESO' => 'En Proceso',
            'RESUELTO' => 'Resuelto',
            'CERRADO' => 'Cerrado',
            'CANCELADO' => 'Cancelado',
        ];
    }

    /**
     * Calcular tiempo de respuesta en minutos
     */
    public function getTiempoRespuestaAttribute()
    {
        if (!$this->fecha_atencion) {
            return null;
        }

        return $this->fecha_solicitud->diffInMinutes($this->fecha_atencion);
    }

    /**
     * Calcular tiempo de resolución en horas
     */
    public function getTiempoResolucionAttribute()
    {
        if (!$this->fecha_cierre) {
            return null;
        }

        return round($this->fecha_solicitud->diffInHours($this->fecha_cierre), 2);
    }

    /**
     * ¿Está vencido el SLA? (4 horas de máximo)
     */
    public function estaVencidoSLA()
    {
        if ($this->estado === 'CERRADO') {
            return false;
        }

        $ahora = now();
        $tiempoTranscurrido = $this->fecha_solicitud->diffInHours($ahora);

        return $tiempoTranscurrido > 4;
    }

    /**
     * Obtener próximo ID de ticket
     */
    public static function obtenerProximoId()
    {
        $ultimo = self::orderBy('id', 'desc')->first();
        $numero = ($ultimo ? $ultimo->id : 0) + 1;
        return 'TKT-' . date('Y') . '-' . str_pad($numero, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Obtener promedio de calificación
     */
    public static function promedioCalificacion()
    {
        return self::cerrados()
            ->whereNotNull('calificacion_cliente')
            ->avg('calificacion_cliente');
    }

    /**
     * Obtener estadísticas rápidas
     */
    public static function estadisticas()
    {
        return [
            'pendientes' => self::pendientes()->count(),
            'urgentes' => self::urgentes()->count(),
            'sin_atender' => self::sinAtender()->count(),
            'promedio_calificacion' => self::promedioCalificacion(),
        ];
    }

    /**
     * Obtener equipos adicionales disponibles en la misma área
     * Exclusión: El equipo principal del servicio
     */
    public function equiposAdicionalesDisponibles()
    {
        if (!$this->equipo || !$this->equipo->area_id) {
            return collect([]);
        }

        return Equipo::where('area_id', $this->equipo->area_id)
            ->where('id', '!=', $this->equipo_id)
            ->where('estado_operativo', 'OPERATIVO')
            ->orderBy('codigo_interno')
            ->get();
    }

    /**
     * Obtener equipos adicionales que fueron atendidos
     */
    public function getEquiposAtendidosRelacion()
    {
        if (!$this->equipos_adicionales_atendidos || empty($this->equipos_adicionales_atendidos)) {
            return collect([]);
        }

        return Equipo::whereIn('id', $this->equipos_adicionales_atendidos)->get();
    }
}
