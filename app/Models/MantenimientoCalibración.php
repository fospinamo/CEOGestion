<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class MantenimientoCalibración extends Model
{
    use SoftDeletes;

    protected $table = 'mantenimiento_calibraciones';

    protected $fillable = [
        'equipo_id',
        'tipo',
        'fecha_programada',
        'fecha_realizada',
        'numero_reporte',
        'descripcion_trabajo',
        'tecnico_responsable',
        'empresa_tercero',
        'archivo_pdf_path',
        'costo',
        'estado',
        'usuario_creador',
        'usuario_realizador',
    ];

    protected $casts = [
        'fecha_programada' => 'date',
        'fecha_realizada' => 'date',
        'costo' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación: Pertenece a un Equipo
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    /**
     * Relación: Usuario que creó
     */
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'usuario_creador');
    }

    /**
     * Relación: Usuario que realizó
     */
    public function usuarioRealizador()
    {
        return $this->belongsTo(User::class, 'usuario_realizador');
    }

    /**
     * Scope: Programados (no realizados)
     */
    public function scopeProgramados($query)
    {
        return $query->where('estado', 'programado')->where('fecha_realizada', null);
    }

    /**
     * Scope: Realizados
     */
    public function scopeRealizados($query)
    {
        return $query->where('estado', 'realizado')->whereNotNull('fecha_realizada');
    }

    /**
     * Scope: Vencidos (fecha programada pasada sin realizar)
     */
    public function scopeVencidos($query)
    {
        return $query->where('estado', 'programado')
                    ->where('fecha_programada', '<', Carbon::today())
                    ->whereNull('fecha_realizada');
    }

    /**
     * Scope: Por vencer (próximos 7 días)
     */
    public function scopePorVencer($query)
    {
        return $query->where('estado', 'programado')
                    ->whereBetween('fecha_programada', [
                        Carbon::today(),
                        Carbon::today()->addDays(7)
                    ])
                    ->whereNull('fecha_realizada');
    }

    /**
     * Scope: Por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Marcar como realizado
     */
    public function marcarRealizado($usuario_id, $numero_reporte = null, $costo = null)
    {
        $this->update([
            'estado' => 'realizado',
            'fecha_realizada' => Carbon::now(),
            'usuario_realizador' => $usuario_id,
            'numero_reporte' => $numero_reporte ?? $this->numero_reporte,
            'costo' => $costo ?? $this->costo,
        ]);

        // Actualizar fecha de próximo mantenimiento en el equipo
        if ($this->tipo === 'mantenimiento') {
            $this->equipo->update([
                'fecha_ultimo_mantenimiento' => Carbon::now(),
                'proxima_fecha_mantenimiento' => Carbon::now()->addMonths(
                    (int)ceil(12 / ($this->equipo->mantenimientos_anuales ?? 1))
                ),
            ]);
        } else {
            $this->equipo->update([
                'fecha_ultima_calibracion' => Carbon::now(),
                'proxima_fecha_calibracion' => Carbon::now()->addMonths(
                    (int)ceil(12 / ($this->equipo->calibraciones_anuales ?? 1))
                ),
            ]);
        }

        return $this;
    }

    /**
     * Obtener etiqueta legible del tipo
     */
    public function getTipoLabelAttribute()
    {
        return match($this->tipo) {
            'mantenimiento' => 'Mantenimiento',
            'calibracion' => 'Calibración',
            default => 'Otro'
        };
    }

    /**
     * Obtener etiqueta del estado con color
     */
    public function getEstadoLabelAttribute()
    {
        return match($this->estado) {
            'programado' => '<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">Programado</span>',
            'realizado' => '<span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Realizado</span>',
            'cancelado' => '<span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">Cancelado</span>',
            default => 'Desconocido'
        };
    }
}
