<?php

namespace Database\Seeders;

use App\Models\Contrato;
use App\Models\Cliente;
use Illuminate\Database\Seeder;

/**
 * Seeder para contratos
 * 
 * Crea contratos de ejemplo asociados a clientes.
 */
class ContratoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = Cliente::all();

        foreach ($clientes as $cliente) {
            // 1-2 contratos por cliente
            Contrato::factory(rand(1, 2))
                ->state(['cliente_id' => $cliente->id])
                ->create();
        }

        // Crear algunos contratos específicos
        $cliente = Cliente::where('razon_social', 'Bancolombia S.A.')->first();
        if ($cliente) {
            // Contrato activo
            Contrato::create([
                'cliente_id' => $cliente->id,
                'numero_contrato' => 'CONT-2026-00001',
                'fecha_inicio' => now()->subMonths(6),
                'fecha_fin' => now()->addMonths(6),
                'fecha_firma' => now()->subMonths(6),
                'tipo_contrato' => 'SOPORTE_TI',
                'modalidad' => 'MENSUAL',
                'valor_contrato' => 25000000,
                'moneda' => 'COP',
                'condiciones_pago' => 'Pago mensual anticipado contra factura',
                'alcance_servicios' => 'Soporte de nivel 1 y 2, disponibilidad 24/7',
                'documento_firmado' => true,
                'estado' => 'ACTIVO',
                'renovacion_automatica' => true,
                'created_by' => 1,
            ]);

            // Contrato vencido
            Contrato::create([
                'cliente_id' => $cliente->id,
                'numero_contrato' => 'CONT-2025-00089',
                'fecha_inicio' => now()->subYear(),
                'fecha_fin' => now()->subMonths(3),
                'fecha_firma' => now()->subYear(),
                'fecha_terminacion' => now()->subMonths(3),
                'tipo_contrato' => 'MANTENIMIENTO',
                'modalidad' => 'ANUAL',
                'valor_contrato' => 18000000,
                'moneda' => 'COP',
                'documento_firmado' => true,
                'estado' => 'VENCIDO',
                'created_by' => 1,
            ]);
        }

        $this->command->info('✓ Contratos creados exitosamente');
    }
}
