<?php

namespace Database\Seeders;

use App\Models\Departamento;
use App\Models\Pais;
use Illuminate\Database\Seeder;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colombia = Pais::where('codigo_dane', '170')->first();

        if ($colombia) {
            $departamentos = [
                ['codigo_dane' => '05', 'nombre' => 'Antioquia'],
                ['codigo_dane' => '08', 'nombre' => 'Atlántico'],
                ['codigo_dane' => '11', 'nombre' => 'Bogotá D.C.'],
                ['codigo_dane' => '13', 'nombre' => 'Bolívar'],
                ['codigo_dane' => '15', 'nombre' => 'Boyacá'],
                ['codigo_dane' => '17', 'nombre' => 'Caldas'],
                ['codigo_dane' => '18', 'nombre' => 'Caquetá'],
                ['codigo_dane' => '19', 'nombre' => 'Cauca'],
                ['codigo_dane' => '20', 'nombre' => 'Cesar'],
                ['codigo_dane' => '23', 'nombre' => 'Córdoba'],
                ['codigo_dane' => '25', 'nombre' => 'Cundinamarca'],
                ['codigo_dane' => '27', 'nombre' => 'Guaviare'],
                ['codigo_dane' => '41', 'nombre' => 'Huila'],
                ['codigo_dane' => '44', 'nombre' => 'La Guajira'],
                ['codigo_dane' => '47', 'nombre' => 'Magdalena'],
                ['codigo_dane' => '50', 'nombre' => 'Meta'],
                ['codigo_dane' => '52', 'nombre' => 'Nariño'],
                ['codigo_dane' => '54', 'nombre' => 'Norte de Santander'],
                ['codigo_dane' => '63', 'nombre' => 'Quindío'],
                ['codigo_dane' => '66', 'nombre' => 'Risaralda'],
                ['codigo_dane' => '68', 'nombre' => 'Santander'],
                ['codigo_dane' => '70', 'nombre' => 'Sucre'],
                ['codigo_dane' => '73', 'nombre' => 'Tolima'],
                ['codigo_dane' => '76', 'nombre' => 'Valle del Cauca'],
                ['codigo_dane' => '81', 'nombre' => 'Arauca'],
                ['codigo_dane' => '85', 'nombre' => 'Casanare'],
                ['codigo_dane' => '86', 'nombre' => 'Putumayo'],
                ['codigo_dane' => '88', 'nombre' => 'San Andrés y Providencia'],
                ['codigo_dane' => '91', 'nombre' => 'Amazonas'],
                ['codigo_dane' => '94', 'nombre' => 'Vichada'],
                ['codigo_dane' => '95', 'nombre' => 'Guainía'],
                ['codigo_dane' => '97', 'nombre' => 'Vaupés'],
            ];

            foreach ($departamentos as $depto) {
                Departamento::create([
                    'codigo_dane' => $depto['codigo_dane'],
                    'nombre' => $depto['nombre'],
                    'pais_id' => $colombia->id,
                ]);
            }
        }
    }
}
