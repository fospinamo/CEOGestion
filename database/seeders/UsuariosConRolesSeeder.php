<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Seeder: UsuariosConRolesSeeder
 * 
 * Crea usuarios de prueba con diferentes roles:
 * - 1 Admin: Acceso completo
 * - 1 Coordinador: Asigna y monitorea servicios
 * - 2 Operarios: Registran servicios
 * - 3 Técnicos: Atienden servicios
 * - N Usuarios Cliente: Uno por cada cliente (acceso portal)
 * 
 * Contraseñas de prueba:
 * - Todos usan: 'password123'
 * 
 * Tokens de acceso para clientes generados automáticamente
 */
class UsuariosConRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener o crear empresa principal
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

        // Contraseña por defecto para todos los usuarios de prueba
        $passwordHash = Hash::make('password123');

        // ============================================
        // 1. USUARIO ADMINISTRADOR
        // ============================================
        User::updateOrCreate(
            ['email' => 'admin@ceogestion.com'],
            [
                'name' => 'Administrador Sistema',
                'email' => 'admin@ceogestion.com',
                'password' => $passwordHash,
                'empresa_id' => $empresa->id,
                'tipo_rol' => 'admin',
                'cedula' => '1001234567',
                'telefono' => '3001234567',
                'estado' => true,
                'permisos' => [
                    'ver_estadisticas',
                    'generar_reportes',
                    'gestionar_usuarios',
                    'gestionar_clientes',
                    'ver_sla_compliance',
                ],
            ]
        );

        // ============================================
        // 2. USUARIO COORDINADOR/MONITOR
        // ============================================
        User::updateOrCreate(
            ['email' => 'coordinador@ceogestion.com'],
            [
                'name' => 'Juan Carlos Coordinador',
                'email' => 'coordinador@ceogestion.com',
                'password' => $passwordHash,
                'empresa_id' => $empresa->id,
                'tipo_rol' => 'coordinador',
                'cedula' => '1001234568',
                'telefono' => '3001234568',
                'estado' => true,
                'permisos' => [
                    'ver_servicios_abiertos',
                    'asignar_servicios',
                    'cambiar_prioridad',
                    'ver_carga_tecnicos',
                ],
            ]
        );

        // ============================================
        // 3. USUARIOS OPERARIOS (Registran servicios)
        // ============================================
        $operarios = [
            [
                'email' => 'operario1@ceogestion.com',
                'name' => 'María García - Operaria',
                'cedula' => '1001234569',
                'telefono' => '3001234569',
                'observacion' => 'Turno mañana',
            ],
            [
                'email' => 'operario2@ceogestion.com',
                'name' => 'Carlos López - Operario',
                'cedula' => '1001234570',
                'telefono' => '3001234570',
                'observacion' => 'Turno tarde',
            ],
        ];

        foreach ($operarios as $operario) {
            User::updateOrCreate(
                ['email' => $operario['email']],
                [
                    'name' => $operario['name'],
                    'email' => $operario['email'],
                    'password' => $passwordHash,
                    'empresa_id' => $empresa->id,
                    'tipo_rol' => 'operario',
                    'cedula' => $operario['cedula'],
                    'telefono' => $operario['telefono'],
                    'estado' => true,
                    'permisos' => [
                        'crear_servicios',
                        'ver_clientes',
                        'ver_equipos_cliente',
                    ],
                ]
            );
        }

        // ============================================
        // 4. USUARIOS TÉCNICOS
        // ============================================
        $tecnicos = [
            [
                'email' => 'tecnico1@ceogestion.com',
                'name' => 'Pedro Rodríguez - Técnico Senior',
                'cedula' => '1001234571',
                'telefono' => '3001234571',
                'especialidad' => 'Redes e Infraestructura',
            ],
            [
                'email' => 'tecnico2@ceogestion.com',
                'name' => 'Laura Martínez - Técnico Servidores',
                'cedula' => '1001234572',
                'telefono' => '3001234572',
                'especialidad' => 'Servidores y Backup',
            ],
            [
                'email' => 'tecnico3@ceogestion.com',
                'name' => 'Roberto Gómez - Técnico Campo',
                'cedula' => '1001234573',
                'telefono' => '3001234573',
                'especialidad' => 'Soporte en sitio',
            ],
        ];

        foreach ($tecnicos as $tecnico) {
            User::updateOrCreate(
                ['email' => $tecnico['email']],
                [
                    'name' => $tecnico['name'],
                    'email' => $tecnico['email'],
                    'password' => $passwordHash,
                    'empresa_id' => $empresa->id,
                    'tipo_rol' => 'tecnico',
                    'cedula' => $tecnico['cedula'],
                    'telefono' => $tecnico['telefono'],
                    'estado' => true,
                    'permisos' => [
                        'ver_servicios_asignados',
                        'actualizar_estado_servicio',
                        'registrar_seguimiento',
                        'registrar_tiempo_trabajo',
                    ],
                ]
            );
        }

        // ============================================
        // 5. USUARIOS CLIENTE (Portal del Cliente)
        // ============================================
        // Obtener todos los clientes existentes
        $clientes = Cliente::where('estado', true)->get();

        foreach ($clientes as $cliente) {
            // Generar email único para usuario cliente
            $slugNombre = Str::slug($cliente->razon_social);
            $email = "cliente.{$slugNombre}@portal.ceogestion.com";

            // Crear o actualizar usuario cliente
            $usuarioCliente = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $cliente->razon_social,
                    'email' => $email,
                    'password' => $passwordHash,
                    'empresa_id' => $empresa->id,
                    'cliente_id' => $cliente->id,
                    'tipo_rol' => 'cliente',
                    'cedula' => $cliente->documento,
                    'telefono' => $cliente->telefono_movil ?? $cliente->telefono_fijo,
                    'estado' => true,
                    'permisos' => [
                        'ver_propios_contratos',
                        'ver_propios_equipos',
                        'ver_propios_servicios',
                        'crear_servicio',
                        'descargar_atencion',
                    ],
                ]
            );

            // Generar token de acceso único si no existe
            if (!$usuarioCliente->token_acceso) {
                $usuarioCliente->generarTokenAcceso();
            }

            // Log para debugging
            echo "✓ Usuario cliente creado: {$cliente->razon_social} ({$email})" . PHP_EOL;
            echo "  Token: {$usuarioCliente->token_acceso}" . PHP_EOL;
        }

        echo PHP_EOL . "✅ Usuarios con roles creados exitosamente" . PHP_EOL;
    }
}
