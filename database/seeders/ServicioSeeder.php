<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\Equipo;
use Illuminate\Database\Seeder;

/**
 * Seeder para servicios (tickets)
 * 
 * Crea servicios de ejemplo para equipos.
 */
class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipos = Equipo::all();

        foreach ($equipos as $equipo) {
            // 0-3 servicios por equipo
            $cantidad = rand(0, 3);
            if ($cantidad > 0) {
                Servicio::factory($cantidad)
                    ->state(['equipo_id' => $equipo->id])
                    ->create();
            }
        }

        // Crear servicios específicos
        $equipo = Equipo::first();
        if ($equipo) {
            // Servicio pendiente urgente
            Servicio::create([
                'equipo_id' => $equipo->id,
                'tipo_servicio' => 'CORRECTIVO',
                'prioridad' => 'URGENTE',
                'fecha_solicitud' => now()->subHours(2),
                'solicitado_por' => 'Ana Martínez',
                'contacto_solicitante' => '3115552000',
                'descripcion_problema' => 'La pantalla no enciende, equipo sin respuesta',
                'estado' => 'PENDIENTE',
                'tecnico_asignado' => 'Pedro Guzmán',
                'tecnico_cedula' => '1095000001',
            ]);

            // Servicio en proceso
            Servicio::create([
                'equipo_id' => $equipo->id,
                'tipo_servicio' => 'PREVENTIVO',
                'prioridad' => 'MEDIA',
                'fecha_solicitud' => now()->subDay(),
                'fecha_atencion' => now()->subHours(12),
                'solicitado_por' => 'Gerencia TI',
                'contacto_solicitante' => '3115553000',
                'descripcion_problema' => 'Limpieza y mantenimiento preventivo',
                'diagnostico' => 'Equipo con acumulación de polvo',
                'estado' => 'EN_PROCESO',
                'tecnico_asignado' => 'Luis Gómez',
                'tecnico_cedula' => '1095000002',
            ]);

            // Servicio cerrado
            Servicio::create([
                'equipo_id' => $equipo->id,
                'tipo_servicio' => 'INSTALACION',
                'prioridad' => 'MEDIA',
                'fecha_solicitud' => now()->subWeeks(2),
                'fecha_atencion' => now()->subDays(13),
                'fecha_cierre' => now()->subDays(13),
                'solicitado_por' => 'Departamento Contable',
                'contacto_solicitante' => '3115554000',
                'descripcion_problema' => 'Instalación de software contable',
                'diagnostico' => 'Sistema operativo actualizado correctamente',
                'solucion_aplicada' => 'Software instalado y configurado, usuario capacitado',
                'horas_trabajadas' => 2.5,
                'estado' => 'CERRADO',
                'tecnico_asignado' => 'Sofía Rodríguez',
                'tecnico_cedula' => '1095000003',
                'calificacion_cliente' => 5,
                'comentarios_cliente' => 'Excelente servicio, muy profesional',
            ]);
        }

        $this->command->info('✓ Servicios creados exitosamente');
    }
}
