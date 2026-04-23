<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

/**
 * Seeder para clientes
 * 
 * Crea clientes de ejemplo para cada empresa.
 */
class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresas = Empresa::all();

        foreach ($empresas as $empresa) {
            // 3 clientes por empresa
            Cliente::factory(3)
                ->state(['empresa_id' => $empresa->id])
                ->create();
        }

        // Crear algunos clientes específicos
        $empresa = Empresa::first();
        if ($empresa) {
            // Cliente empresa grande
            Cliente::create([
                'empresa_id' => $empresa->id,
                'tipo_documento' => 'NIT',
                'documento' => '890985923',
                'digito_verificacion' => '1',
                'razon_social' => 'Bancolombia S.A.',
                'nombre_comercial' => 'Bancolombia',
                'email_principal' => 'contacto@bancolombia.com.co',
                'email_secundario' => 'soporte@bancolombia.com.co',
                'telefono_movil' => '3115551234',
                'telefono_whatsapp' => '3115551234',
                'direccion_notificacion' => 'Calle 50 #50-50, Medellín',
                'contacto_nombre' => 'Juan García',
                'contacto_cargo' => 'Gerente TI',
                'contacto_telefono' => '3115551234',
                'contacto_email' => 'jgarcia@bancolombia.com.co',
                'estado' => true,
            ]);

            // Cliente mediano
            Cliente::create([
                'empresa_id' => $empresa->id,
                'tipo_documento' => 'NIT',
                'documento' => '830091234',
                'digito_verificacion' => '5',
                'razon_social' => 'Comercial TechSolutions Ltda.',
                'nombre_comercial' => 'TechSolutions',
                'email_principal' => 'info@techsolutions.com.co',
                'telefono_movil' => '3004445555',
                'telefono_whatsapp' => '3004445555',
                'direccion_notificacion' => 'Carrera 7 #24-89, Bogotá',
                'contacto_nombre' => 'María López',
                'contacto_cargo' => 'Directora Administrativa',
                'contacto_telefono' => '3004445555',
                'contacto_email' => 'mlopez@techsolutions.com.co',
                'estado' => true,
            ]);

            // Cliente persona natural
            Cliente::create([
                'empresa_id' => $empresa->id,
                'tipo_documento' => 'CC',
                'documento' => '1020456789',
                'razon_social' => 'Carlos Rodríguez Martínez',
                'primer_nombre' => 'Carlos',
                'primer_apellido' => 'Rodríguez',
                'segundo_apellido' => 'Martínez',
                'email_principal' => 'crodriguez@email.com',
                'telefono_movil' => '3127778888',
                'telefono_whatsapp' => '3127778888',
                'direccion_notificacion' => 'Avenida 9 de Julio 1936, Buenos Aires',
                'contacto_nombre' => 'Carlos Rodríguez',
                'contacto_cargo' => 'Propietario',
                'contacto_telefono' => '3127778888',
                'contacto_email' => 'crodriguez@email.com',
                'estado' => true,
            ]);
        }

        $this->command->info('✓ Clientes creados exitosamente');
    }
}
