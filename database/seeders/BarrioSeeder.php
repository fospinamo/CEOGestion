<?php

namespace Database\Seeders;

use App\Models\Barrio;
use App\Models\Municipio;
use Illuminate\Database\Seeder;

class BarrioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Barrios de Medellín
        $medellin = Municipio::where('codigo_dane', '05001')->first();
        if ($medellin) {
            $barrios_medellin = [
                'Belén', 'Laureles', 'El Hueco', 'San Alejo', 'Junín',
                'Robledo', 'Arví', 'Villa del Prado', 'Santo Domingo', 'Castilla'
            ];
            foreach ($barrios_medellin as $barrio) {
                Barrio::create(['nombre' => $barrio, 'municipio_id' => $medellin->id]);
            }
        }

        // Barrios de Bogotá
        $bogota = Municipio::where('codigo_dane', '11001')->first();
        if ($bogota) {
            $barrios_bogota = [
                'La Candelaria', 'Chapinero', 'Usaquén', 'Teusaquillo', 'Barrios Unidos',
                'Kennedy', 'Puente Aranda', 'Suba', 'Usme', 'Rafael Uribe Uribe'
            ];
            foreach ($barrios_bogota as $barrio) {
                Barrio::create(['nombre' => $barrio, 'municipio_id' => $bogota->id]);
            }
        }

        // Barrios de Cali
        $cali = Municipio::where('codigo_dane', '76020')->first();
        if ($cali) {
            $barrios_cali = [
                'San Antonio', 'San Joaquín', 'Menga', 'La Ferretería', 'Cristo Rey',
                'San Fernando', 'Juanchito', 'Terrón Colorado', 'Ribera', 'Versalles'
            ];
            foreach ($barrios_cali as $barrio) {
                Barrio::create(['nombre' => $barrio, 'municipio_id' => $cali->id]);
            }
        }

        // Barrios de Barranquilla
        $barranquilla = Municipio::where('codigo_dane', '08078')->first();
        if ($barranquilla) {
            $barrios_barranquilla = [
                'El Prado', 'Riomar', 'Castillogrande', 'Altos del Rosario', 'Suroriental',
                'La Magdalena', 'Tabor', 'Atlántico', 'Rebolo', 'San Roque'
            ];
            foreach ($barrios_barranquilla as $barrio) {
                Barrio::create(['nombre' => $barrio, 'municipio_id' => $barranquilla->id]);
            }
        }

        // Barrios de Montería
        $monteria = Municipio::where('codigo_dane', '23205')->first();
        if ($monteria) {
            $barrios_monteria = [
                'El Centro', 'Crespo', 'Pasacaballos', 'Las Flores', 'Campo Hermoso',
                'San Cristóbal', 'Santander', 'Paraíso', 'Nuevo Paraíso', 'Las Gaviotas'
            ];
            foreach ($barrios_monteria as $barrio) {
                Barrio::create(['nombre' => $barrio, 'municipio_id' => $monteria->id]);
            }
        }

        // Barrios de Bucaramanga
        $bucaramanga = Municipio::where('codigo_dane', '68547')->first();
        if ($bucaramanga) {
            $barrios_bucaramanga = [
                'Centro', 'Cabecera', 'Río de Oro', 'Morrosquillo', 'Mejoras Públicas',
                'San Martín', 'Los Andes', 'Provenza', 'Sotomayor', 'Circunvalar'
            ];
            foreach ($barrios_bucaramanga as $barrio) {
                Barrio::create(['nombre' => $barrio, 'municipio_id' => $bucaramanga->id]);
            }
        }

        // Barrios de Pasto
        $pasto = Municipio::where('codigo_dane', '52189')->first();
        if ($pasto) {
            $barrios_pasto = [
                'Centro', 'Obrero', 'Chapal', 'Anganoy', 'Álamo',
                'Mocondino', 'Santa Rosa', 'San Fernando', 'La Laguna', 'Tescual'
            ];
            foreach ($barrios_pasto as $barrio) {
                Barrio::create(['nombre' => $barrio, 'municipio_id' => $pasto->id]);
            }
        }

        // Barrios de Villavicencio
        $villavicencio = Municipio::where('codigo_dane', '50290')->first();
        if ($villavicencio) {
            $barrios_villavicencio = [
                'Centro', 'Barzal', 'Acacías', 'Libertadores', 'Los Girasoles',
                'Aranda', 'Las Colinas', 'Vanguardia', 'Centro Occidente', 'El Edén'
            ];
            foreach ($barrios_villavicencio as $barrio) {
                Barrio::create(['nombre' => $barrio, 'municipio_id' => $villavicencio->id]);
            }
        }

        // Barrios de Popayán
        $popayan = Municipio::where('codigo_dane', '19075')->first();
        if ($popayan) {
            $barrios_popayan = [
                'Centro', 'La Esmeralda', 'Santa Rosa', 'Bolívar', 'Palestina',
                'San Francisco', 'Aranda', 'Las Acacias', 'Kennedy', 'Nueva Esperanza'
            ];
            foreach ($barrios_popayan as $barrio) {
                Barrio::create(['nombre' => $barrio, 'municipio_id' => $popayan->id]);
            }
        }
    }
}
