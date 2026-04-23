<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla de clientes que contratan servicios TI.
     * Puede ser persona natural o jurídica.
     * Cada cliente pertenece a una empresa (proveedor de servicios).
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            
            // Relación con empresa proveedora
            $table->foreignId('empresa_id')
                ->constrained('empresas')
                ->onDelete('restrict')
                ->comment('Empresa proveedora de servicios');
            
            // Información de identificación
            $table->enum('tipo_documento', ['NIT', 'CC', 'CE', 'PASAPORTE'])
                ->comment('Tipo de documento de identidad');
            $table->string('documento', 20)->unique()->comment('Número de documento');
            $table->string('digito_verificacion', 1)->nullable()->comment('Dígito de verificación (solo NIT)');
            
            // Información del cliente
            $table->string('razon_social')->comment('Razón social o nombre completo');
            $table->string('nombre_comercial')->nullable()->comment('Nombre comercial o nombre común');
            
            // Para personas naturales
            $table->string('primer_nombre')->nullable()->comment('Primer nombre (personas naturales)');
            $table->string('segundo_nombre')->nullable()->comment('Segundo nombre (personas naturales)');
            $table->string('primer_apellido')->nullable()->comment('Primer apellido (personas naturales)');
            $table->string('segundo_apellido')->nullable()->comment('Segundo apellido (personas naturales)');
            
            // Contacto principal
            $table->string('email_principal')->comment('Email principal para contacto');
            $table->string('email_secundario')->nullable()->comment('Email secundario');
            $table->string('telefono_fijo', 20)->nullable()->comment('Teléfono fijo');
            $table->string('telefono_movil', 20)->nullable()->comment('Teléfono móvil');
            $table->string('telefono_whatsapp', 20)->nullable()->comment('Teléfono con WhatsApp');
            
            // Dirección de notificación
            $table->text('direccion_notificacion')->comment('Dirección para notificaciones');
            $table->foreignId('ciudad_notificacion_id')
                ->nullable()
                ->constrained('municipios')
                ->onDelete('set null')
                ->comment('Ciudad/Municipio de notificación');
            
            // Contacto específico
            $table->string('contacto_nombre')->nullable()->comment('Nombre del contacto principal');
            $table->string('contacto_cargo')->nullable()->comment('Cargo del contacto');
            $table->string('contacto_telefono', 20)->nullable()->comment('Teléfono del contacto');
            $table->string('contacto_email')->nullable()->comment('Email del contacto');
            
            // Estado
            $table->boolean('estado')->default(true)->comment('Cliente activo');
            
            $table->softDeletes();
            $table->timestamps();
            
            // Índices
            $table->index('empresa_id');
            $table->index('documento');
            $table->index('tipo_documento');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
