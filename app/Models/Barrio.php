<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barrio extends Model
{
    protected $fillable = ['nombre', 'municipio_id'];

    protected $table = 'barrios';

    public $timestamps = true;

    /**
     * Relación: Un barrio pertenece a un municipio
     */
    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    /**
     * Relación: Un barrio tiene muchas sedes
     */
    public function sedes(): HasMany
    {
        return $this->hasMany(Sede::class, 'barrio_id');
    }
}
