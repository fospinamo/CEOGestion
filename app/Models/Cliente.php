<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Cliente
 * 
 * Representa un cliente que contrata servicios TI.
 * Puede ser persona natural o jurídica.
 * 
 * @property int $id
 * @property int $empresa_id Empresa proveedora
 * @property string $tipo_documento Tipo (NIT, CC, CE, PASAPORTE)
 * @property string $documento Número de documento
 * @property string|null $digito_verificacion Dígito verificación (NIT)
 * @property string $razon_social Razón social o nombre completo
 * @property string|null $nombre_comercial Nombre comercial
 * @property string|null $primer_nombre
 * @property string|null $segundo_nombre
 * @property string|null $primer_apellido
 * @property string|null $segundo_apellido
 * @property string $email_principal Email principal
 * @property string|null $email_secundario Email secundario
 * @property string|null $telefono_fijo Teléfono fijo
 * @property string|null $telefono_movil Teléfono móvil
 * @property string|null $telefono_whatsapp WhatsApp
 * @property string $direccion_notificacion Dirección
 * @property int|null $ciudad_notificacion_id Municipio
 * @property string|null $contacto_nombre Contacto principal
 * @property string|null $contacto_cargo Cargo del contacto
 * @property string|null $contacto_telefono Teléfono contacto
 * @property string|null $contacto_email Email contacto
 * @property bool $estado Cliente activo
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'empresa_id',
        'tipo_documento',
        'documento',
        'digito_verificacion',
        'razon_social',
        'nombre_comercial',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'email_principal',
        'email_secundario',
        'telefono_fijo',
        'telefono_movil',
        'telefono_whatsapp',
        'direccion_notificacion',
        'ciudad_notificacion_id',
        'contacto_nombre',
        'contacto_cargo',
        'contacto_telefono',
        'contacto_email',
        'estado',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'estado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relaciones
     * ============================================
     */

    /**
     * Empresa a la que pertenece este cliente
     * 
     * Un cliente contrata servicios a través de una empresa
     * proveedora (CEOGestion).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Contratos asociados al cliente
     * 
     * Un cliente puede tener múltiples contratos de servicios
     * con la empresa
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    /**
     * Sedes (ubicaciones) del cliente
     * 
     * Un cliente puede tener múltiples sedes o sucursales
     * donde se requieren servicios TI
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }

    /**
     * Ciudad/Municipio de notificación
     * 
     * Ubicación donde se envían notificaciones y documentación
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ciudadNotificacion()
    {
        return $this->belongsTo(Municipio::class, 'ciudad_notificacion_id');
    }

    /**
     * Scopes
     * ============================================
     */

    /**
     * Solo clientes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }

    /**
     * Filtrar por empresa
     */
    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    /**
     * Filtrar por tipo de documento
     */
    public function scopePorTipoDocumento($query, $tipo)
    {
        return $query->where('tipo_documento', $tipo);
    }

    /**
     * Métodos helper
     * ============================================
     */

    /**
     * Obtener nombre completo (para personas naturales)
     */
    public function getNombreCompletoAttribute()
    {
        if ($this->tipo_documento === 'NIT') {
            return $this->razon_social;
        }

        $nombres = array_filter([
            $this->primer_nombre,
            $this->segundo_nombre,
            $this->primer_apellido,
            $this->segundo_apellido,
        ]);

        return implode(' ', $nombres) ?: $this->razon_social;
    }

    /**
     * Obtener documento formateado
     */
    public function getDocumentoFormateadoAttribute()
    {
        if ($this->tipo_documento === 'NIT') {
            return "{$this->documento}-{$this->digito_verificacion}";
        }
        return $this->documento;
    }

    /**
     * Tipos de documento disponibles
     */
    public static function tiposDocumento()
    {
        return [
            'NIT' => 'NIT',
            'CC' => 'Cédula de Ciudadanía',
            'CE' => 'Cédula de Extranjería',
            'PASAPORTE' => 'Pasaporte',
        ];
    }

    /**
     * Eventos del modelo
     * ============================================
     */

    /**
     * Validar que sea persona jurídica (NIT) o natural (otros)
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cliente) {
            // Si es NIT, debe tener dígito de verificación
            if ($cliente->tipo_documento === 'NIT' && !$cliente->digito_verificacion) {
                throw new \Exception('El dígito de verificación es requerido para NIT');
            }
        });
    }
}
