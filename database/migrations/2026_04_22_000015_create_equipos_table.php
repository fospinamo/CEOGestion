<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla de equipos TI.
     * Registra todos los activos tecnológicos en cada área.
     * Incluye información técnica, estado operativo y asignación de usuarios.
     * Soporta especificaciones JSON para flexibilidad en diferentes tipos.
     */
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('area_id')
                ->constrained('areas')
                ->onDelete('cascade')
                ->comment('Área donde se encuentra el equipo');
            
            $table->foreignId('tipo_equipo_id')
                ->constrained('tipos_equipos')
                ->onDelete('restrict')
                ->comment('Tipo de equipo');
            
            // Identificación del equipo
            $table->string('codigo_interno')->unique()->comment('Código único interno de la empresa');
            
            // Información física
            $table->string('marca', 100)->nullable()->comment('Marca del equipo');
            $table->string('modelo', 100)->nullable()->comment('Modelo del equipo');
            $table->string('serial')->unique()->nullable()->comment('Número de serie');
            
            // Fechas importantes
            $table->date('fecha_compra')->nullable()->comment('Fecha de compra');
            $table->date('fecha_instalacion')->nullable()->comment('Fecha de instalación');
            $table->date('fecha_garantia')->nullable()->comment('Fecha de vencimiento de garantía');
            
            // Información financiera
            $table->decimal('valor_compra', 12, 2)->nullable()->comment('Valor de compra del equipo');
            
            // Estado operativo
            $table->enum('estado_operativo', [
                'OPERATIVO',
                'MANTENIMIENTO',
                'REPARACION',
                'BAJA',
                'OBSOLETO'
            ])->default('OPERATIVO')->comment('Estado operativo actual');
            
            // Especificaciones técnicas (flexible para diferentes tipos)
            $table->json('especificaciones_tecnicas')->nullable()
                ->comment('JSON con specs: RAM, disco, procesador, SO, etc.');
            
            // Configuración de red
            $table->string('ip_asignada', 45)->nullable()->comment('IP asignada (v4 o v6)');
            $table->string('mac_address', 17)->nullable()->comment('MAC address del equipo');
            
            // Asignación de usuario
            $table->string('usuario_asignado')->nullable()->comment('Nombre del usuario final');
            
            // Notas
            $table->text('observaciones')->nullable()->comment('Observaciones adicionales');
            
            $table->softDeletes();
            $table->timestamps();
            
            // Índices
            $table->index('area_id');
            $table->index('tipo_equipo_id');
            $table->index('codigo_interno');
            $table->index('serial');
            $table->index('estado_operativo');
            $table->index('ip_asignada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
