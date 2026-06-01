<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subproceso extends Model
{
    use HasFactory;

    protected $table = 'subprocesos';

    protected $fillable = [
        'proceso_id',
        'nombre',
        'ruta',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function proceso(): BelongsTo
    {
        return $this->belongsTo(Proceso::class);
    }
}
