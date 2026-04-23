<?php

namespace Database\Seeders;

use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Database\Seeder;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Departamentos principales con sus municipios
        $municipios = [
            // Antioquia
            '05' => [
                ['codigo_dane' => '05001', 'nombre' => 'Medellín'],
                ['codigo_dane' => '05002', 'nombre' => 'Abejorral'],
                ['codigo_dane' => '05004', 'nombre' => 'Abrigo'],
                ['codigo_dane' => '05021', 'nombre' => 'Belmira'],
                ['codigo_dane' => '05030', 'nombre' => 'Bello'],
            ],
            // Bogotá D.C.
            '11' => [
                ['codigo_dane' => '11001', 'nombre' => 'Bogotá'],
            ],
            // Cundinamarca
            '25' => [
                ['codigo_dane' => '25001', 'nombre' => 'Agua de Dios'],
                ['codigo_dane' => '25019', 'nombre' => 'Bojacá'],
                ['codigo_dane' => '25040', 'nombre' => 'Chía'],
                ['codigo_dane' => '25175', 'nombre' => 'Mosquera'],
                ['codigo_dane' => '25286', 'nombre' => 'Soacha'],
            ],
            // Valle del Cauca
            '76' => [
                ['codigo_dane' => '76001', 'nombre' => 'Alcalá'],
                ['codigo_dane' => '76020', 'nombre' => 'Cali'],
                ['codigo_dane' => '76176', 'nombre' => 'Palmira'],
                ['codigo_dane' => '76520', 'nombre' => 'Tuluá'],
                ['codigo_dane' => '76834', 'nombre' => 'Yumbo'],
            ],
            // Atlántico
            '08' => [
                ['codigo_dane' => '08001', 'nombre' => 'Baranoa'],
                ['codigo_dane' => '08078', 'nombre' => 'Barranquilla'],
                ['codigo_dane' => '08141', 'nombre' => 'Malambo'],
                ['codigo_dane' => '08296', 'nombre' => 'Soledad'],
            ],
            // Córdoba
            '23' => [
                ['codigo_dane' => '23001', 'nombre' => 'Ayapel'],
                ['codigo_dane' => '23068', 'nombre' => 'Lorica'],
                ['codigo_dane' => '23205', 'nombre' => 'Montería'],
            ],
            // Santander
            '68' => [
                ['codigo_dane' => '68001', 'nombre' => 'Aguada'],
                ['codigo_dane' => '68264', 'nombre' => 'Piedecuesta'],
                ['codigo_dane' => '68307', 'nombre' => 'Puente Nacional'],
                ['codigo_dane' => '68547', 'nombre' => 'Bucaramanga'],
            ],
            // Cauca
            '19' => [
                ['codigo_dane' => '19001', 'nombre' => 'Almaguer'],
                ['codigo_dane' => '19075', 'nombre' => 'Popayán'],
                ['codigo_dane' => '19136', 'nombre' => 'Silvia'],
            ],
            // Nariño
            '52' => [
                ['codigo_dane' => '52001', 'nombre' => 'Albán'],
                ['codigo_dane' => '52189', 'nombre' => 'Pasto'],
                ['codigo_dane' => '52517', 'nombre' => 'Tumaco'],
            ],
            // Meta
            '50' => [
                ['codigo_dane' => '50001', 'nombre' => 'Acacías'],
                ['codigo_dane' => '50290', 'nombre' => 'Villavicencio'],
                ['codigo_dane' => '50659', 'nombre' => 'Puerto Gaitán'],
            ],
        ];

        foreach ($municipios as $depto_code => $muns) {
            $departamento = Departamento::where('codigo_dane', $depto_code)->first();

            if ($departamento) {
                foreach ($muns as $mun) {
                    Municipio::create([
                        'codigo_dane' => $mun['codigo_dane'],
                        'nombre' => $mun['nombre'],
                        'departamento_id' => $departamento->id,
                    ]);
                }
            }
        }
    }
}
