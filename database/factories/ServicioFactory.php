<?php

namespace Database\Factories;

use App\Models\Servicio;
use App\Models\Equipo;
use App\Models\Contrato;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para Servicio
 * 
 * Genera datos de prueba para servicios (tickets).
 */
class ServicioFactory extends Factory
{
    protected $model = Servicio::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $equipo_id = Equipo::inRandomOrder()->first()->id ?? Equipo::factory()->create()->id;
        $contrato_id = $this->faker->optional(0.7)->randomElement(Contrato::all()->pluck('id')->toArray());
        $fecha_solicitud = $this->faker->dateTimeThisMonth();

        return [
            'equipo_id' => $equipo_id,
            'contrato_id' => $contrato_id,
            'tipo_servicio' => $this->faker->randomElement(['PREVENTIVO', 'CORRECTIVO', 'INSTALACION', 'CONFIGURACION', 'CAPACITACION', 'CONSULTA']),
            'prioridad' => $this->faker->randomElement(['BAJA', 'MEDIA', 'ALTA', 'URGENTE']),
            'fecha_solicitud' => $fecha_solicitud,
            'fecha_atencion' => $this->faker->optional(0.8)->dateTimeBetween($fecha_solicitud, $fecha_solicitud->modify('+2 hours')),
            'fecha_cierre' => $this->faker->optional(0.6)->dateTimeBetween($fecha_solicitud, $fecha_solicitud->modify('+3 days')),
            'solicitado_por' => $this->faker->name(),
            'contacto_solicitante' => $this->faker->numerify('300########'),
            'descripcion_problema' => $this->faker->paragraph(),
            'diagnostico' => $this->faker->optional(0.7)->paragraph(),
            'solucion_aplicada' => $this->faker->optional(0.6)->paragraph(),
            'repuestos_utilizados' => $this->faker->optional(0.4)->randomElement([
                [
                    ['nombre' => 'RAM 8GB', 'cantidad' => 1, 'valor' => 180000],
                    ['nombre' => 'SSD 256GB', 'cantidad' => 1, 'valor' => 150000],
                ],
                [
                    ['nombre' => 'Cable de red', 'cantidad' => 2, 'valor' => 5000],
                ],
                null,
            ]),
            'horas_trabajadas' => $this->faker->optional(0.7)->randomFloat(1, 0.5, 8),
            'tecnico_asignado' => $this->faker->name(),
            'tecnico_cedula' => $this->faker->numerify('#########'),
            'estado' => $this->faker->randomElement(['PENDIENTE', 'EN_PROCESO', 'RESUELTO', 'CERRADO']),
            'calificacion_cliente' => $this->faker->optional(0.5)->numberBetween(1, 5),
            'comentarios_cliente' => $this->faker->optional(0.4)->sentence(),
        ];
    }

    /**
     * Servicio pendiente
     */
    public function pendiente()
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'PENDIENTE',
            'fecha_atencion' => null,
            'fecha_cierre' => null,
            'diagnostico' => null,
            'solucion_aplicada' => null,
        ]);
    }

    /**
     * Servicio en proceso
     */
    public function enProceso()
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'EN_PROCESO',
            'fecha_cierre' => null,
        ]);
    }

    /**
     * Servicio cerrado
     */
    public function cerrado()
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'CERRADO',
            'diagnostico' => $this->faker->paragraph(),
            'solucion_aplicada' => $this->faker->paragraph(),
            'horas_trabajadas' => $this->faker->randomFloat(1, 1, 8),
        ]);
    }

    /**
     * Servicio urgente
     */
    public function urgente()
    {
        return $this->state(fn (array $attributes) => [
            'prioridad' => 'URGENTE',
        ]);
    }
}
