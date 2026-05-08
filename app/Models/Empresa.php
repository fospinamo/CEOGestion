<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Modelo Empresa
 * 
 * Representa la empresa proveedora de servicios TI (CEOGestion).
 * 
 * Una empresa:
 * - Tiene múltiples clientes que contratan sus servicios
 * - Tiene múltiples usuarios del sistema
 * - Tiene múltiples sedes a través de sus clientes
 * 
 * Relaciones:
 * - clientes: Clientes de la empresa (HasMany)
 * - usuarios: Usuarios del sistema (HasMany)
 * - sedes: Sedes de los clientes (HasManyThrough Cliente)
 */
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
        'logo',
        'descripcion',
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
     * Relación: Una empresa tiene muchos clientes que contratan sus servicios
     */
    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }

    /**
     * Relación: Una empresa tiene muchos usuarios del sistema
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relación: Una empresa tiene muchas sedes a través de sus clientes
     * 
     * Permite acceder a todas las sedes de los clientes de esta empresa
     * sin necesidad de iterar los clientes manualmente.
     * 
     * Uso: $empresa->sedes retorna todas las sedes de todos los clientes
     */
    public function sedes(): HasManyThrough
    {
        return $this->hasManyThrough(Sede::class, Cliente::class);
    }

    /**
     * Relación: Una empresa tiene una configuración de tema
     */
    public function themeSetting()
    {
        return $this->hasOne(EmpresaThemeSetting::class);
    }
}
