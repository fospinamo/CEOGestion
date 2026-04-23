<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo DocumentoAdjunto
 * 
 * Modelo polimórfico para adjuntos.
 * Permite asociar documentos a diferentes entidades (Contrato, Servicio, etc.)
 * 
 * @property int $id
 * @property string $entidad_type Tipo de entidad (App\Models\Contrato, etc.)
 * @property int $entidad_id ID de la entidad
 * @property string $nombre_archivo Nombre original
 * @property string $ruta_archivo Ruta en storage
 * @property string $tipo_documento Tipo (CONTRATO, SOPORTE, DIAGNOSTICO, etc.)
 * @property string $mime_type Tipo MIME
 * @property int $tamaño_bytes Tamaño en bytes
 * @property string|null $descripcion Descripción
 * @property int $subido_por Usuario que subió
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class DocumentoAdjunto extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'entidad_type',
        'entidad_id',
        'nombre_archivo',
        'ruta_archivo',
        'tipo_documento',
        'mime_type',
        'tamaño_bytes',
        'descripcion',
        'subido_por',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'tamaño_bytes' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Tabla personalizada
     */
    protected $table = 'documentos_adjuntos';

    /**
     * Relaciones
     * ============================================
     */

    /**
     * Entidad polimórfica (Contrato, Servicio, etc.)
     */
    public function entidad()
    {
        return $this->morphTo();
    }

    /**
     * Usuario que subió el documento
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'subido_por');
    }

    /**
     * Scopes
     * ============================================
     */

    /**
     * Filtrar por tipo de documento
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_documento', $tipo);
    }

    /**
     * Filtrar por MIME type
     */
    public function scopePorMimeType($query, $mimeType)
    {
        return $query->where('mime_type', $mimeType);
    }

    /**
     * Solo PDFs
     */
    public function scopeSOloPDFs($query)
    {
        return $query->where('mime_type', 'application/pdf');
    }

    /**
     * Solo imágenes
     */
    public function scopeSoloImagenes($query)
    {
        return $query->whereIn('mime_type', [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp'
        ]);
    }

    /**
     * Documentos recientes
     */
    public function scopeRecientes($query, $dias = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }

    /**
     * Métodos helper
     * ============================================
     */

    /**
     * Obtener tipos de documento disponibles
     */
    public static function tiposDocumento()
    {
        return [
            'CONTRATO' => 'Contrato',
            'SOPORTE' => 'Soporte',
            'DIAGNOSTICO' => 'Diagnóstico',
            'FACTURA' => 'Factura',
            'OTRO' => 'Otro',
        ];
    }

    /**
     * Obtener tamaño en formato legible
     */
    public function getTamañoFormateadoAttribute()
    {
        $bytes = $this->tamaño_bytes;
        $unidades = ['B', 'KB', 'MB', 'GB'];
        $indice = 0;

        while ($bytes >= 1024 && $indice < count($unidades) - 1) {
            $bytes /= 1024;
            $indice++;
        }

        return round($bytes, 2) . ' ' . $unidades[$indice];
    }

    /**
     * ¿Es una imagen?
     */
    public function esImagen()
    {
        return in_array($this->mime_type, [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp'
        ]);
    }

    /**
     * ¿Es un PDF?
     */
    public function esPDF()
    {
        return $this->mime_type === 'application/pdf';
    }

    /**
     * Obtener URL pública de descarga
     */
    public function getUrlDescargaAttribute()
    {
        return route('documentos.descargar', $this->id);
    }

    /**
     * Obtener nombre corto (máximo 30 caracteres)
     */
    public function getNombreCortoAttribute()
    {
        if (strlen($this->nombre_archivo) > 30) {
            return substr($this->nombre_archivo, 0, 27) . '...';
        }

        return $this->nombre_archivo;
    }

    /**
     * Eventos
     * ============================================
     */

    /**
     * Al eliminar el documento, también eliminar archivo
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($documento) {
            if (\Storage::exists($documento->ruta_archivo)) {
                \Storage::delete($documento->ruta_archivo);
            }
        });
    }
}
