<?php

namespace Database\Factories;

use App\Models\TipoEquipo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para TipoEquipo
 * 
 * Genera datos de prueba para tipos de equipos.
 */
class TipoEquipoFactory extends Factory
{
    protected $model = TipoEquipo::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->word(),
            'descripcion' => $this->faker->sentence(),
            'categoria' => $this->faker->randomElement(['HARDWARE', 'SOFTWARE', 'RED', 'PERIFERICO', 'OTRO']),
            'icono' => $this->faker->randomElement(['fa-desktop', 'fa-laptop', 'fa-printer', 'fa-wifi', 'fa-server']),
        ];
    }
}
