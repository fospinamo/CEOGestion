<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Seed las categorías iniciales
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'HARDWARE',
                'slug' => 'hardware',
                'descripcion' => 'Componentes físicos: computadoras, servidores, componentes electrónicos',
                'icono' => 'fa-microchip',
                'color' => '#3b82f6', // Azul
                'estado' => true,
            ],
            [
                'nombre' => 'SOFTWARE',
                'slug' => 'software',
                'descripcion' => 'Licencias, aplicaciones, sistemas operativos y programas',
                'icono' => 'fa-code',
                'color' => '#10b981', // Verde
                'estado' => true,
            ],
            [
                'nombre' => 'RED',
                'slug' => 'red',
                'descripcion' => 'Equipos de conectividad: routers, switches, cableado, firewalls',
                'icono' => 'fa-network-wired',
                'color' => '#f59e0b', // Ámbar
                'estado' => true,
            ],
            [
                'nombre' => 'PERIFERICO',
                'slug' => 'periferico',
                'descripcion' => 'Periféricos: impresoras, escáneres, monitores, teclados',
                'icono' => 'fa-print',
                'color' => '#ef4444', // Rojo
                'estado' => true,
            ],
            [
                'nombre' => 'OTRO',
                'slug' => 'otro',
                'descripcion' => 'Equipos no clasificados en otras categorías',
                'icono' => 'fa-cubes',
                'color' => '#8b5cf6', // Púrpura
                'estado' => true,
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(
                ['nombre' => $categoria['nombre']],
                $categoria
            );
        }

        $this->command->info('Categorías semillas creadas exitosamente');
    }
}
