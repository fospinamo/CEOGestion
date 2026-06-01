<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipoDocumento extends Model
{
    use SoftDeletes;

    protected $table = 'equipo_documentos';

    protected $fillable = [
        'equipo_id',
        'tipo',
        'nombre_original',
        'archivo_path',
        'mime_type',
        'tamaño_bytes',
        'usuario_id',
        'descripcion',
    ];

    protected $casts = [
        'tamaño_bytes' => 'integer',
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
     * Relación: Pertenece a un Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filtrar por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Obtener etiqueta legible del tipo
     */
    public function getTipoLabelAttribute()
    {
        return match($this->tipo) {
            'visual' => 'Visual del Equipo',
            'hojas_vida' => 'Hojas de Vida',
            'reportes_anexos' => 'Reportes Anexos',
            'facturas' => 'Facturas',
            'certificados' => 'Certificados',
            'actas' => 'Actas',
            default => 'Otro'
        };
    }

    /**
     * Obtener ícono según tipo
     */
    public function getIconoAttribute()
    {
        return match($this->tipo) {
            'visual' => '📷',
            'hojas_vida' => '📄',
            'reportes_anexos' => '📋',
            'facturas' => '💰',
            'certificados' => '✅',
            'actas' => '📑',
            default => '📎'
        };
    }
}
