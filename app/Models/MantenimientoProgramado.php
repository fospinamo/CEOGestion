<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * MantenimientoProgramado
 * 
 * Modelo para gestionar mantenimientos y calibraciones programados de equipos.
 * Un mantenimiento puede ser asignado a un técnico y generar un servicio.
 * 
 * @property int $id
 * @property int $equipo_id
 * @property string $tipo (MANTENIMIENTO|CALIBRACION)
 * @property date $fecha_programada
 * @property timestamp $fecha_realizacion
 * @property string $estado (PENDIENTE|REALIZADO|CANCELADO)
 * @property int|null $tecnico_id
 * @property int|null $servicio_id
 * @property string|null $notas
 * @property string|null $resultado
 * @property timestamp $created_at
 * @property timestamp $updated_at
 * @property timestamp $deleted_at
 */
class MantenimientoProgramado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mantenimiento_programado';

    protected $fillable = [
        'equipo_id',
        'tipo',
        'fecha_programada',
        'fecha_realizacion',
        'estado',
        'tecnico_id',
        'servicio_id',
        'notas',
        'resultado',
    ];

    protected $casts = [
        'fecha_programada' => 'date',
        'fecha_realizacion' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ========== RELACIONES ==========

    /**
     * Equipo asociado
     */
    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    /**
     * Técnico asignado
     */
    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    /**
     * Servicio generado (si se asignó a técnico)
     */
    public function servicio(): HasOne
    {
        return $this->hasOne(Servicio::class);
    }

    // ========== SCOPES ==========

    /**
     * Mantenimientos pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'PENDIENTE');
    }

    /**
     * Mantenimientos realizados
     */
    public function scopeRealizados($query)
    {
        return $query->where('estado', 'REALIZADO');
    }

    /**
     * Mantenimientos cancelados
     */
    public function scopeCancelados($query)
    {
        return $query->where('estado', 'CANCELADO');
    }

    /**
     * Solo mantenimientos (no calibraciones)
     */
    public function scopeMantenimientos($query)
    {
        return $query->where('tipo', 'MANTENIMIENTO');
    }

    /**
     * Solo calibraciones
     */
    public function scopeCalibraciónes($query)
    {
        return $query->where('tipo', 'CALIBRACION');
    }

    /**
     * Mantenimientos vencidos (fecha programada < hoy y aún pendientes)
     */
    public function scopeVencidos($query)
    {
        return $query->where('estado', 'PENDIENTE')
            ->where('fecha_programada', '<', now()->toDateString());
    }

    /**
     * Mantenimientos próximos (próximos 30 días)
     */
    public function scopeProximos($query)
    {
        return $query->where('estado', 'PENDIENTE')
            ->whereBetween('fecha_programada', [
                now()->toDateString(),
                now()->addDays(30)->toDateString(),
            ]);
    }

    /**
     * Mantenimientos por equipo
     */
    public function scopePorEquipo($query, $equipoId)
    {
        return $query->where('equipo_id', $equipoId);
    }

    /**
     * Mantenimientos por técnico
     */
    public function scopePorTecnico($query, $tecnicoId)
    {
        return $query->where('tecnico_id', $tecnicoId);
    }

    /**
     * Filtra por rango de fechas
     */
    public function scopePorRangoFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('fecha_programada', [$desde, $hasta]);
    }

    // ========== MÉTODOS UTILITARIOS ==========

    /**
     * ¿Está vencido?
     */
    public function estaVencido(): bool
    {
        return $this->estado === 'PENDIENTE' && $this->fecha_programada < now()->toDateString();
    }

    /**
     * ¿Está próximo? (próximos 30 días)
     */
    public function estaProximo(): bool
    {
        return $this->estado === 'PENDIENTE' 
            && $this->fecha_programada >= now()->toDateString()
            && $this->fecha_programada <= now()->addDays(30)->toDateString();
    }

    /**
     * Obtener etiqueta de estado con color
     */
    public function getEstadoConColorAttribute(): array
    {
        $colores = [
            'PENDIENTE' => 'yellow',
            'REALIZADO' => 'green',
            'CANCELADO' => 'red',
        ];

        return [
            'estado' => $this->estado,
            'color' => $colores[$this->estado] ?? 'gray',
        ];
    }

    /**
     * Obtener etiqueta de tipo
     */
    public function getTipoEtiquetaAttribute(): string
    {
        return match ($this->tipo) {
            'MANTENIMIENTO' => '🔧 Mantenimiento',
            'CALIBRACION' => '📏 Calibración',
            default => $this->tipo,
        };
    }

    /**
     * Marcar como realizado
     */
    public function marcarRealizado(string $resultado = null): bool
    {
        return $this->update([
            'estado' => 'REALIZADO',
            'fecha_realizacion' => now(),
            'resultado' => $resultado,
        ]);
    }

    /**
     * Cancelar mantenimiento
     */
    public function cancelar(string $motivo = null): bool
    {
        return $this->update([
            'estado' => 'CANCELADO',
            'notas' => $motivo,
        ]);
    }

    /**
     * Asignar a técnico y crear servicio
     */
    public function asignarATecnico(int $tecnicoId, string $descripcion = null): ?Servicio
    {
        $this->update(['tecnico_id' => $tecnicoId]);

        // Crear servicio automáticamente
        $servicio = Servicio::create([
            'equipo_id' => $this->equipo_id,
            'tecnico_id' => $tecnicoId,
            'descripcion' => $descripcion ?? "Mantenimiento programado: {$this->tipo_etiqueta}",
            'prioridad' => 'MEDIA',
            'estado' => 'PENDIENTE',
            'fecha_inicio' => $this->fecha_programada,
        ]);

        $this->update(['servicio_id' => $servicio->id]);

        return $servicio;
    }
}
