<?php

namespace Database\Seeders;

use App\Models\Equipo;
use App\Models\Area;
use Illuminate\Database\Seeder;

/**
 * Seeder para equipos
 * 
 * Crea equipos en las áreas existentes con asignación automática
 * de cliente_id y sede_id según el área.
 */
class EquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = Area::with(['sede', 'sede.cliente'])->get();

        foreach ($areas as $area) {
            // Obtener sede y cliente del área
            $sede = $area->sede;
            $cliente = $sede ? $sede->cliente : null;

            // 2-6 equipos por área
            Equipo::factory(rand(2, 6))
                ->state([
                    'area_id' => $area->id,
                    'sede_id' => $sede?->id,
                    'cliente_id' => $cliente?->id,
                ])
                ->create();
        }

        $this->command->info('✓ Equipos creados exitosamente con cliente y sede asignados');
    }
}
