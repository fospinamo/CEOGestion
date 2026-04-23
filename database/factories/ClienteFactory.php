<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Municipio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para Cliente
 * 
 * Genera datos de prueba para clientes.
 */
class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $tipo = $this->faker->randomElement(['NIT', 'CC', 'CE', 'PASAPORTE']);
        $empresa_id = Empresa::inRandomOrder()->first()->id ?? Empresa::factory()->create()->id;

        return [
            'empresa_id' => $empresa_id,
            'tipo_documento' => $tipo,
            'documento' => $tipo === 'NIT' ? $this->faker->unique()->numberBetween(800000000, 999999999) : $this->faker->unique()->numerify('#########'),
            'digito_verificacion' => $tipo === 'NIT' ? $this->faker->randomDigit() : null,
            'razon_social' => $this->faker->company(),
            'nombre_comercial' => $this->faker->optional()->company(),
            'primer_nombre' => $this->faker->optional()->firstName(),
            'segundo_nombre' => $this->faker->optional()->firstName(),
            'primer_apellido' => $this->faker->optional()->lastName(),
            'segundo_apellido' => $this->faker->optional()->lastName(),
            'email_principal' => $this->faker->unique()->companyEmail(),
            'email_secundario' => $this->faker->optional()->email(),
            'telefono_fijo' => $this->faker->optional()->phoneNumber(),
            'telefono_movil' => $this->faker->numerify('300########'),
            'telefono_whatsapp' => $this->faker->numerify('300########'),
            'direccion_notificacion' => $this->faker->address(),
            'ciudad_notificacion_id' => Municipio::inRandomOrder()->first()->id ?? null,
            'contacto_nombre' => $this->faker->name(),
            'contacto_cargo' => $this->faker->jobTitle(),
            'contacto_telefono' => $this->faker->numerify('300########'),
            'contacto_email' => $this->faker->email(),
            'estado' => $this->faker->boolean(90),
        ];
    }

    /**
     * Cliente activo
     */
    public function activo()
    {
        return $this->state(fn (array $attributes) => [
            'estado' => true,
        ]);
    }

    /**
     * Cliente inactivo
     */
    public function inactivo()
    {
        return $this->state(fn (array $attributes) => [
            'estado' => false,
        ]);
    }

    /**
     * Cliente persona natural
     */
    public function personaNatural()
    {
        return $this->state(fn (array $attributes) => [
            'tipo_documento' => 'CC',
            'razon_social' => $this->faker->name(),
            'digito_verificacion' => null,
        ]);
    }

    /**
     * Cliente empresa
     */
    public function empresa()
    {
        return $this->state(fn (array $attributes) => [
            'tipo_documento' => 'NIT',
            'razon_social' => $this->faker->company(),
            'digito_verificacion' => $this->faker->randomDigit(),
        ]);
    }
}
