<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Sede;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para Area
 * 
 * Genera datos de prueba para áreas.
 */
class AreaFactory extends Factory
{
    protected $model = Area::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $sede_id = Sede::inRandomOrder()->first()->id ?? Sede::factory()->create()->id;

        return [
            'sede_id' => $sede_id,
            'nombre' => $this->faker->randomElement([
                'Departamento de TI',
                'Contabilidad',
                'Gerencia',
                'Ventas',
                'Logística',
                'Recursos Humanos',
                'Financiero',
                'Operaciones',
                'Atención al Cliente',
            ]),
            'descripcion' => $this->faker->optional(0.7)->text(150),
            'responsable_nombre' => $this->faker->optional(0.8)->name(),
            'responsable_contacto' => $this->faker->optional(0.7)->numerify('300########'),
            'nivel_riesgo' => $this->faker->randomElement(['BAJO', 'MEDIO', 'ALTO', 'CRITICO']),
            'estado' => $this->faker->boolean(95),
        ];
    }

    /**
     * Área de alto riesgo
     */
    public function altoRiesgo()
    {
        return $this->state(fn (array $attributes) => [
            'nivel_riesgo' => 'CRITICO',
            'nombre' => 'Departamento de TI',
        ]);
    }

    /**
     * Área de riesgo bajo
     */
    public function bajoRiesgo()
    {
        return $this->state(fn (array $attributes) => [
            'nivel_riesgo' => 'BAJO',
        ]);
    }
}
