<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaseDocumentalHistorial extends Model
{
    use HasFactory;

    protected $table = 'clase_documental_historiales';

    protected $fillable = [
        'clase_documental_id',
        'codigo',
        'version',
        'fecha_cambio',
        'imagen',
    ];

    protected $casts = [
        'fecha_cambio' => 'datetime',
    ];

    public function claseDocumental(): BelongsTo
    {
        return $this->belongsTo(ClaseDocumental::class);
    }
}
