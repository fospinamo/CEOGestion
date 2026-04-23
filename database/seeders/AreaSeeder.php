<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Sede;
use Illuminate\Database\Seeder;

/**
 * Seeder para áreas
 * 
 * Crea áreas en las sedes existentes.
 */
class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sedes = Sede::all();

        foreach ($sedes as $sede) {
            // 2-4 áreas por sede
            Area::factory(rand(2, 4))
                ->state(['sede_id' => $sede->id])
                ->create();
        }

        $this->command->info('✓ Áreas creadas exitosamente');
    }
}
