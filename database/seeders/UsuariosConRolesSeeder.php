<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Seeder: UsuariosConRolesSeeder
 * 
 * NOTA: Los roles y permisos se crean en RoleAndPermissionSeeder
 * Este seeder solo crea usuarios de prueba y los asigna a roles
 * 
 * Usuarios creados:
 * - 1 Admin: admin@ceogestion.com / password123
 * - 1 Técnico: tecnico1@ceogestion.com / password123
 * - 1 Agente: agente1@ceogestion.com / password123
 * - N Usuarios Cliente: Uno por cada cliente (acceso portal)
 */
class UsuariosConRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener empresa principal
        $empresa = Empresa::first();
        if (!$empresa) {
            $empresa = Empresa::create([
                'nombre' => 'CEOGESTION SAS',
                'nit' => '900.123.456-7',
                'digito_verificacion' => '7',
                'email' => 'info@ceogestion.com',
                'telefono' => '(1) 2345678',
                'estado' => true,
            ]);
        }

        $passwordHash = Hash::make('password123');

        // Obtener roles (creados por RoleAndPermissionSeeder)
        $adminRole = Role::where('slug', 'admin')->first();
        $tecnicoRole = Role::where('slug', 'tecnico')->first();
        $agenteRole = Role::where('slug', 'agente')->first();

        // ============================================
        // 1. USUARIO ADMINISTRADOR
        // ============================================
        if ($adminRole) {
            $admin = User::updateOrCreate(
                ['email' => 'admin@ceogestion.com'],
                [
                    'name' => 'Administrador Sistema',
                    'email' => 'admin@ceogestion.com',
                    'password' => $passwordHash,
                    'empresa_id' => $empresa->id,
                    'role_id' => $adminRole->id,
                    'cedula' => '1001234567',
                    'telefono' => '3001234567',
                    'estado' => true,
                ]
            );
            echo "✓ Admin creado: admin@ceogestion.com / password123" . PHP_EOL;
        }

        // ============================================
        // 2. USUARIO TÉCNICO (Demo)
        // ============================================
        if ($tecnicoRole) {
            $tecnico = User::updateOrCreate(
                ['email' => 'tecnico1@ceogestion.com'],
                [
                    'name' => 'Técnico Demo',
                    'email' => 'tecnico1@ceogestion.com',
                    'password' => $passwordHash,
                    'empresa_id' => $empresa->id,
                    'role_id' => $tecnicoRole->id,
                    'cedula' => '1001234571',
                    'telefono' => '3001234571',
                    'estado' => true,
                ]
            );
            echo "✓ Técnico creado: tecnico1@ceogestion.com / password123" . PHP_EOL;
        }

        // ============================================
        // 3. USUARIO AGENTE (Demo)
        // ============================================
        if ($agenteRole) {
            $agente = User::updateOrCreate(
                ['email' => 'agente1@ceogestion.com'],
                [
                    'name' => 'Agente Demo',
                    'email' => 'agente1@ceogestion.com',
                    'password' => $passwordHash,
                    'empresa_id' => $empresa->id,
                    'role_id' => $agenteRole->id,
                    'cedula' => '1001234580',
                    'telefono' => '3001234580',
                    'estado' => true,
                ]
            );
            echo "✓ Agente creado: agente1@ceogestion.com / password123" . PHP_EOL;
        }

        // ============================================
        // 4. USUARIOS CLIENTE (Portal)
        // ============================================
        $clientes = Cliente::where('estado', true)->get();

        foreach ($clientes as $cliente) {
            $slugNombre = Str::slug($cliente->razon_social);
            $email = "cliente.{$slugNombre}@portal.ceogestion.com";

            $usuarioCliente = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $cliente->razon_social,
                    'email' => $email,
                    'password' => $passwordHash,
                    'empresa_id' => $empresa->id,
                    'cliente_id' => $cliente->id,
                    'cedula' => $cliente->documento,
                    'telefono' => $cliente->telefono_movil ?? $cliente->telefono_fijo,
                    'estado' => true,
                ]
            );

            echo "✓ Usuario cliente: {$email}" . PHP_EOL;
        }

        echo PHP_EOL . "✅ Usuarios creados exitosamente" . PHP_EOL;
    }
}
