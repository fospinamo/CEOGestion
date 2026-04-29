<?php

namespace Database\Seeders;

use App\Models\EstadoServicio;
use Illuminate\Database\Seeder;

class EstadoServicioSeeder extends Seeder
{
    /**
     * Crea los estados de servicio predeterminados
     */
    public function run(): void
    {
        $estados = [
            [
                'nombre' => 'Pendiente',
                'descripcion' => 'Servicio pendiente de atención',
                'color' => '#FFA500',
                'es_cierre' => false,
                'es_pendiente_repuesto' => false,
                'es_en_proceso' => false,
                'orden' => 1,
                'activo' => true,
            ],
            [
                'nombre' => 'Asignado',
                'descripcion' => 'Servicio asignado a un técnico',
                'color' => '#FF6B6B',
                'es_cierre' => false,
                'es_pendiente_repuesto' => false,
                'es_en_proceso' => false,
                'orden' => 2,
                'activo' => true,
            ],
            [
                'nombre' => 'En Proceso',
                'descripcion' => 'Técnico está atendiendo el servicio',
                'color' => '#4169E1',
                'es_cierre' => false,
                'es_pendiente_repuesto' => false,
                'es_en_proceso' => true,
                'orden' => 3,
                'activo' => true,
            ],
            [
                'nombre' => 'Pendiente de Repuesto',
                'descripcion' => 'El servicio está pendiente de recibir repuestos',
                'color' => '#FFD700',
                'es_cierre' => false,
                'es_pendiente_repuesto' => true,
                'es_en_proceso' => false,
                'orden' => 4,
                'activo' => true,
            ],
            [
                'nombre' => 'Cerrado',
                'descripcion' => 'Servicio completado y cerrado',
                'color' => '#28A745',
                'es_cierre' => true,
                'es_pendiente_repuesto' => false,
                'es_en_proceso' => false,
                'orden' => 5,
                'activo' => true,
            ],
            [
                'nombre' => 'Cancelado',
                'descripcion' => 'Servicio cancelado',
                'color' => '#DC3545',
                'es_cierre' => true,
                'es_pendiente_repuesto' => false,
                'es_en_proceso' => false,
                'orden' => 6,
                'activo' => true,
            ],
        ];

        foreach ($estados as $estado) {
            EstadoServicio::updateOrCreate(
                ['nombre' => $estado['nombre']],
                $estado
            );
        }
    }
}
