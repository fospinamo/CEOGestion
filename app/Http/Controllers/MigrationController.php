<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrationController extends Controller
{
    /**
     * Ejecutar migraciones desde web
     * URL: /api/migrate-db?token=tu_token_secreto
     */
    public function runMigrations()
    {
        // Token de seguridad (CAMBIAR EN PRODUCCIÓN)
        $token = request('token');
        $secret_token = env('MIGRATION_TOKEN', 'cambiar_esto_en_produccion');
        
        if ($token !== $secret_token) {
            return response()->json([
                'error' => 'Token inválido',
                'status' => 'unauthorized'
            ], 401);
        }

        try {
            echo "🚀 EJECUTANDO MIGRACIONES...\n";
            echo "==========================================\n\n";
            
            // Ejecutar migraciones
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();
            
            echo "✅ MIGRACIONES COMPLETADAS:\n";
            echo $output . "\n\n";
            
            return response()->json([
                'status' => 'success',
                'message' => 'Migraciones ejecutadas',
                'output' => $output
            ]);
            
        } catch (\Exception $e) {
            echo "❌ ERROR:\n";
            echo $e->getMessage() . "\n";
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ejecutar seeders desde web
     * URL: /api/seed-db?token=tu_token_secreto
     */
    public function runSeeds()
    {
        // Token de seguridad
        $token = request('token');
        $secret_token = env('MIGRATION_TOKEN', 'cambiar_esto_en_produccion');
        
        if ($token !== $secret_token) {
            return response()->json([
                'error' => 'Token inválido',
                'status' => 'unauthorized'
            ], 401);
        }

        try {
            echo "🌱 EJECUTANDO SEEDERS...\n";
            echo "==========================================\n\n";
            
            // Ejecutar seeders
            Artisan::call('db:seed', ['--force' => true]);
            $output = Artisan::output();
            
            echo "✅ SEEDERS COMPLETADOS:\n";
            echo $output . "\n\n";
            
            return response()->json([
                'status' => 'success',
                'message' => 'Seeders ejecutados',
                'output' => $output
            ]);
            
        } catch (\Exception $e) {
            echo "❌ ERROR:\n";
            echo $e->getMessage() . "\n";
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar estado de la BD
     * URL: /api/verify-db
     */
    public function verifyDatabase()
    {
        try {
            $maestras = [
                'paises',
                'departamentos',
                'municipios',
                'barrios',
                'tipos_equipos',
                'categorias',
                'estado_servicios',
                'roles',
                'permissions',
                'role_permissions',
            ];

            $operacionales = [
                'servicios',
                'equipos',
                'empresas',
                'clientes',
                'sedes',
                'areas',
            ];

            $result = [
                'maestras' => [],
                'operacionales' => [],
                'status' => 'ok'
            ];

            // Verificar maestras
            foreach ($maestras as $table) {
                try {
                    $count = DB::table($table)->count();
                    $result['maestras'][$table] = $count;
                } catch (\Exception $e) {
                    $result['maestras'][$table] = 'TABLE_NOT_FOUND';
                }
            }

            // Verificar operacionales
            foreach ($operacionales as $table) {
                try {
                    $count = DB::table($table)->count();
                    $result['operacionales'][$table] = $count;
                } catch (\Exception $e) {
                    $result['operacionales'][$table] = 'TABLE_NOT_FOUND';
                }
            }

            return response()->json($result);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Panel de control (interfaz web)
     * URL: /migration-panel?token=tu_token_secreto
     */
    public function panel()
    {
        $token = request('token');
        
        return view('migration-panel', ['token' => $token]);
    }
}
