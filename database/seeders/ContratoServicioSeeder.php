<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contrato;
use App\Models\ContratoServicio;

class ContratoServicioSeeder extends Seeder
{
    public function run()
    {
        // Obtener contratos existentes
        $contratos = Contrato::all();
        
        foreach ($contratos as $contrato) {
            // Servicios básicos para todos los contratos
            $serviciosBase = [
                [
                    'tipo_servicio' => 'PREVENTIVO',
                    'incluido' => true,
                    'sla_horas_respuesta' => 24,
                    'sla_horas_solucion' => 72
                ],
                [
                    'tipo_servicio' => 'CORRECTIVO',
                    'incluido' => true,
                    'sla_horas_respuesta' => 4,
                    'sla_horas_solucion' => 24
                ],
            ];
            
            // Servicios adicionales según el tipo de contrato
            $serviciosAdicionales = [
                [
                    'tipo_servicio' => 'INSTALACION',
                    'incluido' => true,
                    'sla_horas_respuesta' => 48,
                    'sla_horas_solucion' => 96,
                    'costo_adicional' => null
                ],
                [
                    'tipo_servicio' => 'CONFIGURACION',
                    'incluido' => true,
                    'sla_horas_respuesta' => 24,
                    'sla_horas_solucion' => 48,
                    'costo_adicional' => 250000
                ],
                [
                    'tipo_servicio' => 'CAPACITACION',
                    'incluido' => false,
                    'sla_horas_respuesta' => 72,
                    'sla_horas_solucion' => 168,
                    'costo_adicional' => 500000
                ],
                [
                    'tipo_servicio' => 'CONSULTA',
                    'incluido' => true,
                    'sla_horas_respuesta' => 8,
                    'sla_horas_solucion' => null,
                    'costo_adicional' => null
                ],
            ];
            
            // Agregar todos los servicios
            $todos = array_merge($serviciosBase, $serviciosAdicionales);
            
            foreach ($todos as $servicio) {
                ContratoServicio::firstOrCreate(
                    [
                        'contrato_id' => $contrato->id,
                        'tipo_servicio' => $servicio['tipo_servicio']
                    ],
                    $servicio
                );
            }
        }
        
        $this->command->info('✓ Servicios por contrato creados exitosamente');
    }
}
