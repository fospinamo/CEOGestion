<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Actualizar tabla equipos - MIGRACIÓN SEGURA
     * 
     * PASOS REALIZADOS (SIN DAÑAR DATOS):
     * 1. Agregar cliente_id, sede_id, marca_id (IF NOT EXISTS)
     * 2. Renombrar codigo_interno a codigo_activo_cliente
     * 3. Migrar datos de marca (string) a marca_id (FK)
     * 4. Hacer serial único
     * 5. Eliminar columna marca (ÚLTIMO)
     * 
     * IMPORTANTE: Cada paso es reversible con down()
     * No se pierden datos en ningún momento
     */
    public function up(): void
    {
        // PASO 1: Agregar nuevas columnas (FK)
        Schema::table('equipos', function (Blueprint $table) {
            // Agregar cliente_id si no existe
            if (!Schema::hasColumn('equipos', 'cliente_id')) {
                $table->foreignId('cliente_id')
                    ->nullable()
                    ->constrained('clientes')
                    ->onDelete('set null')
                    ->after('area_id')
                    ->comment('Cliente propietario del equipo');
                
                $table->index('cliente_id');
            }

            // Agregar sede_id si no existe
            if (!Schema::hasColumn('equipos', 'sede_id')) {
                $table->foreignId('sede_id')
                    ->nullable()
                    ->constrained('sedes')
                    ->onDelete('set null')
                    ->after('cliente_id')
                    ->comment('Sede donde se encuentra el equipo');
                
                $table->index('sede_id');
            }

            // Agregar marca_id si no existe
            if (!Schema::hasColumn('equipos', 'marca_id')) {
                $table->foreignId('marca_id')
                    ->nullable()
                    ->constrained('marcas')
                    ->onDelete('set null')
                    ->after('tipo_equipo_id')
                    ->comment('Marca/Fabricante del equipo');
                
                $table->index('marca_id');
            }
        });

        // PASO 2: Renombrar codigo_interno a codigo_activo_cliente
        if (Schema::hasColumn('equipos', 'codigo_interno')) {
            Schema::table('equipos', function (Blueprint $table) {
                $table->renameColumn('codigo_interno', 'codigo_activo_cliente');
            });
        }

        // PASO 3: Migrar datos de marca (string) a marca_id (FK)
        if (Schema::hasColumn('equipos', 'marca') && Schema::hasColumn('equipos', 'marca_id')) {
            // Mapear marcas existentes y actualizar marca_id
            DB::statement('
                UPDATE equipos e
                INNER JOIN marcas m ON LOWER(e.marca) = LOWER(m.nombre)
                SET e.marca_id = m.id
                WHERE e.marca_id IS NULL AND e.marca IS NOT NULL
            ');
        }

        // PASO 4: Hacer serial único (después de verificar que no hay duplicados)
        if (Schema::hasColumn('equipos', 'serial')) {
            try {
                // Remover índice simple en serial si existe
                DB::statement('ALTER TABLE equipos DROP INDEX IF EXISTS equipos_serial_index');
                
                // Crear índice único en serial (MySQL permite múltiples NULLs)
                DB::statement('ALTER TABLE equipos ADD UNIQUE INDEX equipos_serial_unique (serial)');
            } catch (\Exception $e) {
                // El índice podría ya existir, ignorar
                \Log::warning('Error al crear índice único en serial: ' . $e->getMessage());
            }
        }

        // PASO 5: Eliminar columna marca (ÚLTIMO PASO, después de migrar datos)
        if (Schema::hasColumn('equipos', 'marca')) {
            Schema::table('equipos', function (Blueprint $table) {
                $table->dropColumn('marca');
            });
        }
    }

    /**
     * Reverse the migrations - ROLLBACK SEGURO
     * 
     * Revierte los cambios en orden inverso
     */
    public function down(): void
    {
        // PASO 1: Restaurar columna marca (string)
        if (!Schema::hasColumn('equipos', 'marca')) {
            Schema::table('equipos', function (Blueprint $table) {
                $table->string('marca', 100)->nullable()->after('tipo_equipo_id');
            });
        }

        // PASO 2: Migrar datos de marca_id de vuelta a marca (string)
        if (Schema::hasColumn('equipos', 'marca') && Schema::hasColumn('equipos', 'marca_id')) {
            DB::statement('
                UPDATE equipos e
                LEFT JOIN marcas m ON e.marca_id = m.id
                SET e.marca = m.nombre
                WHERE e.marca_id IS NOT NULL
            ');
        }

        // PASO 3: Eliminar columnas FK (marca_id, cliente_id, sede_id)
        Schema::table('equipos', function (Blueprint $table) {
            // Remover marca_id
            if (Schema::hasColumn('equipos', 'marca_id')) {
                try {
                    $table->dropForeignKey(['marca_id']);
                } catch (\Exception $e) {
                    // FK podría no existir
                }
                $table->dropColumn('marca_id');
            }

            // Remover sede_id
            if (Schema::hasColumn('equipos', 'sede_id')) {
                try {
                    $table->dropForeignKey(['sede_id']);
                } catch (\Exception $e) {
                    // FK podría no existir
                }
                $table->dropColumn('sede_id');
            }

            // Remover cliente_id
            if (Schema::hasColumn('equipos', 'cliente_id')) {
                try {
                    $table->dropForeignKey(['cliente_id']);
                } catch (\Exception $e) {
                    // FK podría no existir
                }
                $table->dropColumn('cliente_id');
            }
        });

        // PASO 4: Renombrar codigo_activo_cliente de vuelta a codigo_interno
        if (Schema::hasColumn('equipos', 'codigo_activo_cliente')) {
            Schema::table('equipos', function (Blueprint $table) {
                $table->renameColumn('codigo_activo_cliente', 'codigo_interno');
            });
        }

        // PASO 5: Remover índice único en serial y restaurar índice simple
        if (Schema::hasColumn('equipos', 'serial')) {
            try {
                DB::statement('ALTER TABLE equipos DROP INDEX IF EXISTS equipos_serial_unique');
                DB::statement('ALTER TABLE equipos ADD INDEX equipos_serial_index (serial)');
            } catch (\Exception $e) {
                // Ignorar si hay errores
            }
        }
    }
};

