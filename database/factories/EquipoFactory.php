<?php

namespace Database\Factories;

use App\Models\Equipo;
use App\Models\Area;
use App\Models\TipoEquipo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para Equipo
 * 
 * Genera datos de prueba para equipos.
 */
class EquipoFactory extends Factory
{
    protected $model = Equipo::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $area_id = Area::inRandomOrder()->first()->id ?? Area::factory()->create()->id;
        $tipo_equipo_id = TipoEquipo::inRandomOrder()->first()->id ?? TipoEquipo::factory()->create()->id;

        return [
            'area_id' => $area_id,
            'tipo_equipo_id' => $tipo_equipo_id,
            'codigo_interno' => 'EQ-' . strtoupper($this->faker->unique()->bothify('??-######')),
            'marca' => $this->faker->randomElement(['Dell', 'HP', 'Lenovo', 'ASUS', 'Cisco', 'Ubiquiti']),
            'modelo' => $this->faker->bothify('?#-###'),
            'serial' => strtoupper($this->faker->unique()->bothify('????-####-####')),
            'fecha_compra' => $this->faker->optional(0.8)->dateTimeBetween('-5 years', 'now'),
            'fecha_instalacion' => $this->faker->optional(0.7)->dateTimeBetween('-5 years', 'now'),
            'fecha_garantia' => $this->faker->optional(0.6)->dateTimeBetween('-1 year', '+2 years'),
            'valor_compra' => $this->faker->optional(0.7)->numberBetween(100000, 10000000),
            'estado_operativo' => $this->faker->randomElement(['OPERATIVO', 'MANTENIMIENTO', 'REPARACION', 'BAJA']),
            'especificaciones_tecnicas' => [
                'ram' => $this->faker->randomElement(['4GB', '8GB', '16GB', '32GB']),
                'disco' => $this->faker->randomElement(['256GB SSD', '512GB SSD', '1TB HDD', '2TB HDD']),
                'procesador' => $this->faker->randomElement(['Intel i5', 'Intel i7', 'AMD Ryzen 5', 'AMD Ryzen 7']),
                'so' => $this->faker->randomElement(['Windows 10', 'Windows 11', 'Ubuntu 22.04', 'macOS']),
            ],
            'ip_asignada' => $this->faker->optional(0.5)->ipv4(),
            'mac_address' => $this->faker->optional(0.5)->macAddress(),
            'usuario_asignado' => $this->faker->optional(0.6)->name(),
            'observaciones' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    /**
     * Equipo operativo
     */
    public function operativo()
    {
        return $this->state(fn (array $attributes) => [
            'estado_operativo' => 'OPERATIVO',
        ]);
    }

    /**
     * Equipo en reparación
     */
    public function enReparacion()
    {
        return $this->state(fn (array $attributes) => [
            'estado_operativo' => 'REPARACION',
        ]);
    }

    /**
     * Equipo dado de baja
     */
    public function baja()
    {
        return $this->state(fn (array $attributes) => [
            'estado_operativo' => 'BAJA',
        ]);
    }
}
