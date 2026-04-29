<?php

namespace Database\Seeders;

use App\Models\Sede;
use App\Models\Cliente;
use Illuminate\Database\Seeder;

/**
 * Seeder para sedes de clientes
 * 
 * Crea sedes para cada cliente registrado.
 */
class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = Cliente::all();

        foreach ($clientes as $cliente) {
            // 1-3 sedes por cliente
            Sede::factory(rand(1, 3))
                ->create([
                    'cliente_id' => $cliente->id,
                ]);
        }

        $this->command->info('✓ Sedes creadas exitosamente para cada cliente');
    }
}
