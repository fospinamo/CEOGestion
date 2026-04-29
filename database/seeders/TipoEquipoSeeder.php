<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\TipoEquipo;
use Illuminate\Database\Seeder;

/**
 * Seeder para tipos de equipos
 * 
 * Popula la tabla tipos_equipos con los tipos estándar.
 * Ahora vinculado a categorías parametrizables.
 */
class TipoEquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [
                'nombre' => 'Computador de Escritorio',
                'descripcion' => 'Desktop PC para oficina',
                'categoria' => 'HARDWARE',
                'icono' => 'fa-desktop',
            ],
            [
                'nombre' => 'Laptop',
                'descripcion' => 'Computadora portátil',
                'categoria' => 'HARDWARE',
                'icono' => 'fa-laptop',
            ],
            [
                'nombre' => 'Impresora',
                'descripcion' => 'Impresora de oficina',
                'categoria' => 'PERIFERICO',
                'icono' => 'fa-print',
            ],
            [
                'nombre' => 'Multifuncional',
                'descripcion' => 'Impresora multifuncional (copia, escaneo, fax)',
                'categoria' => 'PERIFERICO',
                'icono' => 'fa-print',
            ],
            [
                'nombre' => 'Router',
                'descripcion' => 'Router de red inalámbrica',
                'categoria' => 'RED',
                'icono' => 'fa-wifi',
            ],
            [
                'nombre' => 'Switch',
                'descripcion' => 'Switch de red para conexión de dispositivos',
                'categoria' => 'RED',
                'icono' => 'fa-network-wired',
            ],
            [
                'nombre' => 'Servidor',
                'descripcion' => 'Servidor de red/datos',
                'categoria' => 'HARDWARE',
                'icono' => 'fa-server',
            ],
            [
                'nombre' => 'UPS',
                'descripcion' => 'Sistema de alimentación ininterrumpida',
                'categoria' => 'HARDWARE',
                'icono' => 'fa-battery-full',
            ],
            [
                'nombre' => 'Monitor',
                'descripcion' => 'Monitor LCD/LED',
                'categoria' => 'PERIFERICO',
                'icono' => 'fa-tv',
            ],
            [
                'nombre' => 'Tablet',
                'descripcion' => 'Dispositivo tablet',
                'categoria' => 'HARDWARE',
                'icono' => 'fa-tablet-alt',
            ],
            [
                'nombre' => 'Celular',
                'descripcion' => 'Teléfono inteligente',
                'categoria' => 'HARDWARE',
                'icono' => 'fa-mobile-alt',
            ],
            [
                'nombre' => 'Cámara IP',
                'descripcion' => 'Cámara de seguridad en red',
                'categoria' => 'RED',
                'icono' => 'fa-video',
            ],
            [
                'nombre' => 'Firewall',
                'descripcion' => 'Dispositivo de seguridad de red',
                'categoria' => 'RED',
                'icono' => 'fa-shield-alt',
            ],
            [
                'nombre' => 'Software Licencia',
                'descripcion' => 'Software con licencia comercial',
                'categoria' => 'SOFTWARE',
                'icono' => 'fa-cube',
            ],
            [
                'nombre' => 'Sistema Operativo',
                'descripcion' => 'Sistema operativo para equipo',
                'categoria' => 'SOFTWARE',
                'icono' => 'fa-square',
            ],
        ];

        foreach ($tipos as $tipo) {
            // Obtener el categoria_id desde la tabla de categorías
            $categoriaNombre = $tipo['categoria'];
            $categoria = Categoria::where('nombre', $categoriaNombre)->first();

            // Preparar datos del tipo
            $dataTipo = [
                'nombre' => $tipo['nombre'],
                'descripcion' => $tipo['descripcion'],
                'icono' => $tipo['icono'],
                'categoria' => $tipo['categoria'], // Legacy field
            ];

            // Agregar categoria_id si existe
            if ($categoria) {
                $dataTipo['categoria_id'] = $categoria->id;
            }

            TipoEquipo::firstOrCreate(
                ['nombre' => $tipo['nombre']],
                $dataTipo
            );
        }

        $this->command->info('✓ Tipos de equipos creados exitosamente');
    }
}

