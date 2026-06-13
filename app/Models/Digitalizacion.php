<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Digitalizacion extends Model
{
    use HasFactory;

    protected $table = 'digitalizaciones';

    protected $fillable = [
        'empresa_id',
        'sede_id',
        'proceso_id',
        'subproceso_id',
        'documento_id',
        'user_id',
        'radicacion_id',
        'titulo',
        'fecha_documento',
        'estado',
        'ruta',
        'nombre_archivo',
        'extension',
        'tamano_bytes',
    ];

    protected $casts = [
        'fecha_documento' => 'date',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function proceso(): BelongsTo
    {
        return $this->belongsTo(Proceso::class);
    }

    public function subproceso(): BelongsTo
    {
        return $this->belongsTo(Subproceso::class);
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function radicacion(): BelongsTo
    {
        return $this->belongsTo(Radicacion::class);
    }

}
