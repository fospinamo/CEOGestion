<?php

namespace Database\Factories;

use App\Models\Sede;
use App\Models\Cliente;
use App\Models\Municipio;
use App\Models\Barrio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para Sede
 * 
 * Genera datos de prueba para sedes de clientes.
 */
class SedeFactory extends Factory
{
    protected $model = Sede::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $cliente = Cliente::inRandomOrder()->first();
        if (!$cliente) {
            $cliente = Cliente::factory()->create();
        }
        
        $municipio_id = Municipio::inRandomOrder()->first()->id ?? 1;
        $barrio_id = Barrio::inRandomOrder()->first()?->id;

        return [
            'cliente_id' => $cliente->id,
            'municipio_id' => $municipio_id,
            'barrio_id' => $barrio_id,
            'nombre' => $this->faker->randomElement([
                'Sede Principal',
                'Sede Matriz',
                'Sede Bogotá',
                'Sede Medellín',
                'Sede Cali',
                'Sede Barranquilla',
                'Sucursal Centro',
                'Sucursal Comercial',
                'Oficina Regional',
                'Campus Administrativo',
            ]),
            'codigo' => $this->faker->unique()->bothify('SDE-????-###'),
            'codigo_postal' => $this->faker->optional(0.7)->postcode(),
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->optional(0.8)->numerify('(1) ########'),
            'email' => $this->faker->optional(0.8)->email(),
            'estado' => $this->faker->boolean(90),
        ];
    }
}
