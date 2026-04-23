<?php

namespace Database\Seeders;

use App\Models\Equipo;
use App\Models\Area;
use Illuminate\Database\Seeder;

/**
 * Seeder para equipos
 * 
 * Crea equipos en las áreas existentes.
 */
class EquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = Area::all();

        foreach ($areas as $area) {
            // 2-6 equipos por área
            Equipo::factory(rand(2, 6))
                ->state(['area_id' => $area->id])
                ->create();
        }

        $this->command->info('✓ Equipos creados exitosamente');
    }
}
