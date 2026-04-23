<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';
    
    protected $fillable = [
        'nombre',
        'nit',
        'digito_verificacion',
        'telefono',
        'email',
        'pagina_web',
        'tipo_contribuyente',
        'responsabilidades_fiscales',
        'direccion',
        'estado'
    ];

    protected $casts = [
        'responsabilidades_fiscales' => 'array',
        'estado' => 'boolean',
    ];

    /**
     * Relación: Una empresa tiene muchas sedes
     */
    public function sedes(): HasMany
    {
        return $this->hasMany(Sede::class);
    }

    /**
     * Relación: Una empresa tiene muchos usuarios
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
