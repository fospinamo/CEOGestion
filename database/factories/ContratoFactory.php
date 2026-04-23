<?php

namespace Database\Factories;

use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para Contrato
 * 
 * Genera datos de prueba para contratos.
 */
class ContratoFactory extends Factory
{
    protected $model = Contrato::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $cliente_id = Cliente::inRandomOrder()->first()->id ?? Cliente::factory()->create()->id;
        $user_id = User::inRandomOrder()->first()->id ?? User::factory()->create()->id;
        $fecha_inicio = $this->faker->dateTimeBetween('-1 year', 'now');
        $fecha_fin = $this->faker->dateTimeBetween($fecha_inicio, '+1 year');

        return [
            'cliente_id' => $cliente_id,
            'numero_contrato' => 'CONT-' . strtoupper($this->faker->unique()->bothify('??-######')),
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'fecha_firma' => $this->faker->optional(0.8)->dateTime(),
            'fecha_terminacion' => $this->faker->optional(0.2)->dateTime(),
            'tipo_contrato' => $this->faker->randomElement(['SOPORTE_TI', 'MANTENIMIENTO', 'INFRAESTRUCTURA', 'CONSULTORIA']),
            'modalidad' => $this->faker->randomElement(['MENSUAL', 'TRIMESTRAL', 'SEMESTRAL', 'ANUAL']),
            'valor_contrato' => $this->faker->numberBetween(1000000, 50000000),
            'moneda' => $this->faker->randomElement(['COP', 'USD', 'EUR']),
            'condiciones_pago' => $this->faker->optional(0.7)->text(200),
            'alcance_servicios' => $this->faker->text(300),
            'clausulas_especiales' => $this->faker->optional(0.5)->text(150),
            'documento_pdf' => $this->faker->optional(0.6)->filePath(),
            'documento_firmado' => $this->faker->boolean(80),
            'estado' => $this->faker->randomElement(['BORRADOR', 'ACTIVO', 'VENCIDO', 'TERMINADO']),
            'renovacion_automatica' => $this->faker->boolean(40),
            'created_by' => $user_id,
            'updated_by' => $this->faker->optional(0.5)->randomElement(User::all()->pluck('id')->toArray()),
        ];
    }

    /**
     * Contrato activo
     */
    public function activo()
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'ACTIVO',
            'fecha_inicio' => now()->subMonths(3),
            'fecha_fin' => now()->addMonths(9),
            'documento_firmado' => true,
        ]);
    }

    /**
     * Contrato vencido
     */
    public function vencido()
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'VENCIDO',
            'fecha_fin' => now()->subDays(30),
        ]);
    }

    /**
     * Contrato próximo a vencer
     */
    public function proximoAVencer()
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'ACTIVO',
            'fecha_fin' => now()->addDays(15),
        ]);
    }
}
