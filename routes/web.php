<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DocumentoAdjuntoController;
use App\Http\Controllers\MigrationController;

// =======================================
// MIGRACIONES WEB (SIN TERMINAL CPANEL)
// =======================================
Route::get('/api/migrate-db', [MigrationController::class, 'runMigrations'])->name('migrate.run');
Route::get('/api/seed-db', [MigrationController::class, 'runSeeds'])->name('seed.run');
Route::get('/api/verify-db', [MigrationController::class, 'verifyDatabase'])->name('verify.db');
Route::get('/migration-panel', [MigrationController::class, 'panel'])->name('migration.panel');

// =======================================
// RUTAS PÚBLICAS
// =======================================

// Home - Redirige a dashboard si está autenticado, sino a login
Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard')->middleware('auth');

// =======================================
// AUTENTICACIÓN (Login/Register)
// =======================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.store')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Register (Registro público)
Route::get('/register', function () {
    return view('auth.register');
})->name('register')->middleware('guest');

Route::post('/register', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users|max:255',
        'password' => 'required|min:8|confirmed',
    ]);

    \App\Models\User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
    ]);

    return redirect('/login')->with('success', 'Registro exitoso. Por favor inicia sesión.');
})->name('register.store')->middleware('guest');

// =======================================
// PORTAL DEL CLIENTE (Acceso con Token)
// =======================================
Route::middleware(['auth.token:cliente'])->group(function () {
    // Controlador: PortalClienteController
    Route::get('/portal/cliente', 'App\Http\Controllers\PortalClienteController@dashboard')
        ->name('portal.dashboard');
    
    Route::get('/portal/cliente/contratos', 'App\Http\Controllers\PortalClienteController@contratos')
        ->name('portal.contratos');
    
    Route::get('/portal/cliente/equipos', 'App\Http\Controllers\PortalClienteController@equipos')
        ->name('portal.equipos');
    
    Route::get('/portal/cliente/servicios', 'App\Http\Controllers\PortalClienteController@servicios')
        ->name('portal.servicios');
    
    Route::post('/portal/cliente/servicios/crear', 'App\Http\Controllers\PortalClienteController@crearServicio')
        ->name('portal.servicios.crear');
    
    Route::get('/portal/cliente/servicios/{servicio}/detalle', 'App\Http\Controllers\PortalClienteController@detalleServicio')
        ->name('portal.servicios.detalle');
    
    Route::get('/portal/cliente/servicios/{servicio}/descargar', 'App\Http\Controllers\PortalClienteController@descargarAtencion')
        ->name('portal.servicios.descargar');
});

// Acceso inicial al portal con token
Route::get('/portal/acceso/{token}', 'App\Http\Controllers\PortalClienteController@verificarToken')
    ->name('portal.acceso');

// =======================================
// RUTAS AUTENTICADAS
// =======================================
Route::middleware(['auth'])->group(function () {
    // NOTA: Dashboard está manejado por HomeController en routes incluidos
    // Eliminar esta ruta anónima para evitar conflictos de variables no definidas
    // Route::get('/dashboard', function () {
    //     return view('home');
    // })->name('dashboard');

    // API para cargar entidades dinámicamente
    Route::get('/api/entidades', function () {
        $type = request()->query('type');
        
        if ($type === 'App\Models\Contrato') {
            return \App\Models\Contrato::where('estado', 'ACTIVO')
                ->get()
                ->map(fn($c) => ['id' => $c->id, 'nombre' => $c->numero_contrato . ' - ' . $c->cliente->razon_social]);
        } elseif ($type === 'App\Models\Servicio') {
            return \App\Models\Servicio::get()
                ->map(fn($s) => ['id' => $s->id, 'nombre' => 'Servicio #' . $s->id . ' - ' . $s->equipo->codigo_interno]);
        }
        
        return [];
    });

    // API para cargar municipios por departamento
    // ⚠️ BUENAS PRÁCTICAS: Validación + Error Handling + Logging
    Route::get('/api/municipios-por-departamento', function () {
        try {
            $departamento_id = request()->query('departamento_id');
            
            // Validación de entrada: debe ser numérico
            if (!$departamento_id || !is_numeric($departamento_id)) {
                return response()->json([], 200); // Retorna JSON vacío, no HTML error
            }
            
            // Query segura con Eloquent (previene SQL injection)
            $municipios = \App\Models\Municipio::where('departamento_id', (int) $departamento_id)
                ->orderBy('nombre')
                ->get(['id', 'nombre']);
            
            return response()->json($municipios, 200);
            
        } catch (\Exception $e) {
            // Log del error en storage/logs/laravel.log
            \Illuminate\Support\Facades\Log::error('API municipios-por-departamento error', [
                'departamento_id' => request()->query('departamento_id'),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            
            // Retorna JSON error, nunca HTML
            return response()->json([], 200);
        }
    });

    // API para cargar barrios por municipio
    // ⚠️ BUENAS PRÁCTICAS: Validación + Error Handling + Logging
    Route::get('/api/barrios-por-municipio', function () {
        try {
            $municipio_id = request()->query('municipio_id');
            
            // Validación de entrada: debe ser numérico
            if (!$municipio_id || !is_numeric($municipio_id)) {
                return response()->json([], 200);
            }
            
            // Query segura con Eloquent
            $barrios = \App\Models\Barrio::where('municipio_id', (int) $municipio_id)
                ->orderBy('nombre')
                ->get(['id', 'nombre']);
            
            return response()->json($barrios, 200);
            
        } catch (\Exception $e) {
            // Log del error
            \Illuminate\Support\Facades\Log::error('API barrios-por-municipio error', [
                'municipio_id' => request()->query('municipio_id'),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            
            return response()->json([], 200);
        }
    });

    // API PARA SEDES DINÁMICAS
    // ⚠️ BUENAS PRÁCTICAS: Validación + Error Handling
    Route::get('/api/sedes-por-empresa', function () {
        try {
            $empresa_id = request()->query('empresa_id');
            
            if (!$empresa_id || !is_numeric($empresa_id)) {
                return response()->json([], 200);
            }
            
            $sedes = \App\Models\Sede::where('empresa_id', (int) $empresa_id)
                ->where('estado', true)
                ->orderBy('nombre')
                ->get(['id', 'nombre']);
            
            return response()->json($sedes, 200);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API sedes-por-empresa error', [
                'empresa_id' => request()->query('empresa_id'),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return response()->json([], 200);
        }
    });

    Route::get('/api/sedes-por-cliente', function () {
        try {
            $cliente_id = request()->query('cliente_id');
            
            if (!$cliente_id || !is_numeric($cliente_id)) {
                return response()->json([], 200);
            }
            
            $sedes = \App\Models\Sede::where('cliente_id', (int) $cliente_id)
                ->where('estado', true)
                ->orderBy('nombre')
                ->get(['id', 'nombre']);
            
            return response()->json($sedes, 200);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API sedes-por-cliente error', [
                'cliente_id' => request()->query('cliente_id'),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return response()->json([], 200);
        }
    });

    /**
     * NOTA: Las rutas de recursos están organizadas por módulos:
     * 
     * ✅ SEGURIDAD: /seguridad/usuarios, /seguridad/roles, /seguridad/permissions
     *    → Protegidas con middleware role:admin
     *    → Ubicación: routes/seguridad.php
     * 
     * ✅ ADMINISTRATIVO: /administrativo/paises, /administrativo/departamentos, /administrativo/municipios
     *    → Ubicación: routes/administrativo.php
     * 
     * ✅ PARÁMETROS: /parametros/empresas, /parametros/sedes, /parametros/clientes, etc.
     *    → Ubicación: routes/parametros.php
     * 
     * ✅ INCIDENCIAS: /incidencias/servicios
     *    → Ubicación: routes/incidencias.php
     * 
     * NO colocar rutas de CRUD aquí en web.php para evitar duplicación y conflictos
     * Las rutas modularizadas tienen protección adecuada según el rol del usuario
     */
});

// =======================================
// IMPORTAR RUTAS MODULARES
// =======================================

// Módulo Parámetros - Empresas, Sedes, Clientes, Áreas, Equipos
require __DIR__ . '/parametros.php';

// Módulo Seguridad - Usuarios, Roles y Permisos
require __DIR__ . '/seguridad.php';

// Módulo Administrativo - Tablas básicas
require __DIR__ . '/administrativo.php';

// Módulo Incidencias - Servicios técnicos
require __DIR__ . '/incidencias.php';
