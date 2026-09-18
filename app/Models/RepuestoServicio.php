<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepuestoServicio extends Model
{
    use HasFactory;

    protected $table = 'repuestos_servicios';

    protected $fillable = [
        'servicio_id',
        'codigo',
        'descripcion',
        'marca_id',
        'modelo',
        'serial',
        'cantidad',
        'facturable',
        'numero_factura',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'facturable' => 'boolean',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}
