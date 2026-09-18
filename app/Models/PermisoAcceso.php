<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermisoAcceso extends Model
{
    use HasFactory;

    protected $table = 'permisos_acceso';

    protected $fillable = [
        'user_id',
        'serie_id',
        'dependencia_id',
        'tipo_acceso',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'estado' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serie(): BelongsTo
    {
        return $this->belongsTo(SerieDocumental::class, 'serie_id');
    }

    public function dependencia(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class);
    }
}
