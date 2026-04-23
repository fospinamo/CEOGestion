<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TipoEquipoController;
use App\Http\Controllers\DocumentoAdjuntoController;
use App\Http\Controllers\AuthController;

// =======================================
// RUTAS PÚBLICAS
// =======================================

// Home
Route::get('/', function () {
    return view('home');
})->name('home');

// =======================================
// AUTENTICACIÓN (Login/Register)
// =======================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

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
    Route::get('/dashboard', function () {
        return view('home');
    })->name('dashboard');

    // =======================================
    // GESTIÓN GENERAL (Empresas, Sedes, Usuarios)
    // =======================================
    Route::resource('empresas', EmpresaController::class);
    Route::resource('sedes', SedeController::class);
    Route::resource('usuarios', UsuarioController::class);

    // =======================================
    // UBICACIÓN DANE
    // =======================================
    Route::resource('departamentos', DepartamentoController::class)->only(['index', 'show']);
    Route::resource('municipios', MunicipioController::class)->only(['index', 'show']);

    // =======================================
    // GESTIÓN DE SERVICIOS TI
    // =======================================
    
    // Clientes
    Route::resource('clientes', ClienteController::class);

    // Contratos
    Route::resource('contratos', ContratoController::class);

    // Áreas (dentro de sedes)
    Route::resource('areas', AreaController::class);

    // Equipos TI
    Route::resource('equipos', EquipoController::class);

    // Servicios/Atenciones TI
    Route::resource('servicios', ServicioController::class);

    // Catálogo de Tipos de Equipos
    Route::resource('tipos-equipos', TipoEquipoController::class);

    // Documentos Adjuntos (polimórficos)
    Route::resource('documentos', DocumentoAdjuntoController::class);
    Route::get('documentos/{documento}/download', [DocumentoAdjuntoController::class, 'download'])
        ->name('documentos.download');

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
    Route::get('/api/municipios-por-departamento', function () {
        $departamento_id = request()->query('departamento_id');
        
        if (!$departamento_id) {
            return [];
        }
        
        return \App\Models\Municipio::where('departamento_id', $departamento_id)
            ->orderBy('nombre')
            ->get(['id', 'nombre']);
    });

    // ========== RUTAS AJAX PARA SERVICIOS ==========
    Route::get('/servicios/equipos/{cliente_id}', [ServicioController::class, 'getEquiposByCliente'])
        ->name('servicios.equipos');
    Route::get('/servicios/contrato-activo/{cliente_id}', [ServicioController::class, 'getContratoActivo'])
        ->name('servicios.contrato');
    Route::post('/servicios/{id}/asignar-tecnico', [ServicioController::class, 'asignarTecnico'])
        ->name('servicios.asignar-tecnico');
    Route::post('/servicios/{id}/cambiar-estado', [ServicioController::class, 'cambiarEstado'])
        ->name('servicios.cambiar-estado');
});

// =======================================
// RUTAS DE AUTENTICACIÓN
// =======================================

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    $credentials = request()->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (auth()->attempt($credentials)) {
        return redirect('/dashboard');
    }

    return back()->withErrors(['email' => 'Credenciales inválidas']);
})->name('login.store');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function () {
    $data = request()->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
    ]);

    \App\Models\User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
    ]);

    return redirect('/login')->with('success', 'Registro exitoso. Por favor inicia sesión.');
})->name('register.store');
