<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'empresa_id',
        'sede_id',
        'proceso_id',
        'subproceso_id',
        'codigo',
        'nombre',
        'descripcion',
        'version',
        'estado',
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

    public function digitalizaciones(): HasMany
    {
        return $this->hasMany(Digitalizacion::class);
    }
}
