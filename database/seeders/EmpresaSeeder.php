<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

/**
 * Seeder para empresas
 * 
 * Crea empresas de ejemplo.
 */
class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear empresa principal
        Empresa::create([
            'nombre' => 'CEOGestion',
            'nit' => '901234567',
            'digito_verificacion' => '8',
            'email' => 'info@ceogestion.com',
            'telefono' => '(1) 7654321',
            'direccion' => 'Calle 50 #50-50, Medellín, Antioquia',
            'ciudad' => 'Medellín',
            'logo' => null, // Se establecerá manualmente o por upload en el admin
            'descripcion' => 'Sistema de Gestión Empresarial CEOGestion - Soluciones para tu negocio',
            'pagina_web' => 'https://www.ceogestion.com',
            'tipo_contribuyente' => 'persona_juridica',
            'responsabilidades_fiscales' => ['IVA', 'Retenedor'],
            'estado' => true,
        ]);

        $this->command->info('✓ Empresas creadas exitosamente');
    }
}
