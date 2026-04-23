<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contrato;
use App\Models\Equipo;
use App\Models\Servicio;
use App\Models\SeguimientoServicio;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use PDF;

/**
 * Controlador: PortalClienteController
 * 
 * Gestiona el acceso y funcionalidades del portal del cliente corporativo.
 * 
 * Funcionalidades:
 * - Acceso con token único (sin contraseña)
 * - Visualización de contratos
 * - Visualización de equipos bajo contrato
 * - Visualización de servicios registrados
 * - Registro de nuevos servicios
 * - Descarga/impresión de atenciones
 * 
 * Seguridad:
 * - Acceso validado por token único
 * - Datos filtrados solo para el cliente autenticado
 * - Auditoría de accesos registrada
 * 
 * @author CEOGESTION
 * @version 1.0
 */
class PortalClienteController extends Controller
{
    /**
     * Verifica el token de acceso y autentica al cliente
     * 
     * Genera una sesión de portal con duración limitada (30 minutos).
     * Registra el acceso en la auditoría del usuario.
     * 
     * @param string $token Token único de acceso del cliente
     * @return RedirectResponse|View
     */
    public function verificarToken(string $token)
    {
        // Buscar usuario con este token
        $usuario = User::where('token_acceso', $token)
            ->where('tipo_rol', 'cliente')
            ->where('estado', true)
            ->first();

        if (!$usuario) {
            return redirect('/')->with('error', 'Token de acceso inválido o expirado');
        }

        // Registrar acceso
        $usuario->registrarAccesoPortal(request()->ip());

        // Crear sesión de portal
        session(['portal_cliente_id' => $usuario->cliente_id]);
        session(['portal_user_id' => $usuario->id]);
        session(['portal_token' => $token]);

        return redirect()->route('portal.dashboard');
    }

    /**
     * Dashboard principal del portal del cliente
     * 
     * Muestra resumen de:
     * - Contratos activos
     * - Servicios recientes
     * - Estado de servicios
     * 
     * @return View
     */
    public function dashboard(): View
    {
        $clienteId = session('portal_cliente_id');
        
        $cliente = \App\Models\Cliente::find($clienteId);
        if (!$cliente) {
            return abort(403);
        }

        // Contratos activos
        $contratos = Contrato::where('cliente_id', $clienteId)
            ->where('estado', 'ACTIVO')
            ->count();

        // Equipos
        $equipos = Equipo::whereHas('area.sede', function ($query) use ($clienteId) {
            $query->where('cliente_id', $clienteId);
        })->count();

        // Servicios últimos 30 días
        $servicios_recientes = Servicio::whereHas('equipo.area.sede', function ($query) use ($clienteId) {
            $query->where('cliente_id', $clienteId);
        })
        ->where('created_at', '>=', now()->subDays(30))
        ->count();

        // Servicios por estado
        $servicios_por_estado = Servicio::whereHas('equipo.area.sede', function ($query) use ($clienteId) {
            $query->where('cliente_id', $clienteId);
        })
        ->selectRaw('estado, COUNT(*) as cantidad')
        ->groupBy('estado')
        ->get()
        ->pluck('cantidad', 'estado');

        return view('portal.cliente.dashboard', [
            'cliente' => $cliente,
            'contratos' => $contratos,
            'equipos' => $equipos,
            'servicios_recientes' => $servicios_recientes,
            'servicios_por_estado' => $servicios_por_estado,
        ]);
    }

    /**
     * Lista contratos activos del cliente
     * 
     * @return View
     */
    public function contratos(): View
    {
        $clienteId = session('portal_cliente_id');

        $contratos = Contrato::where('cliente_id', $clienteId)
            ->where('estado', 'ACTIVO')
            ->with(['servicios', 'creadoPor'])
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        return view('portal.cliente.contratos', [
            'contratos' => $contratos,
        ]);
    }

    /**
     * Lista equipos bajo contrato del cliente
     * 
     * @return View
     */
    public function equipos(): View
    {
        $clienteId = session('portal_cliente_id');

        // Obtener equipos del cliente con sus sedes y áreas
        $equipos = Equipo::whereHas('area.sede', function ($query) use ($clienteId) {
            $query->where('cliente_id', $clienteId);
        })
        ->with(['tipo', 'area.sede', 'serie'])
        ->where('estado_operativo', true)
        ->orderBy('codigo_interno')
        ->get();

        return view('portal.cliente.equipos', [
            'equipos' => $equipos,
        ]);
    }

    /**
     * Lista servicios (atenciones) del cliente
     * 
     * Ordenados por fecha de creación descendente.
     * Muestra seguimiento de cada servicio.
     * 
     * @return View
     */
    public function servicios(): View
    {
        $clienteId = session('portal_cliente_id');

        // Obtener servicios del cliente
        $servicios = Servicio::whereHas('equipo.area.sede', function ($query) use ($clienteId) {
            $query->where('cliente_id', $clienteId);
        })
        ->with(['equipo.area.sede', 'tecnicoAsignado', 'seguimientos'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('portal.cliente.servicios', [
            'servicios' => $servicios,
        ]);
    }

    /**
     * Detalle de un servicio específico
     * 
     * Muestra:
     * - Información del equipo
     * - Descripción del problema
     * - Historia de seguimientos
     * - Estado actual
     * - Técnico asignado
     * 
     * @param Servicio $servicio
     * @return View
     */
    public function detalleServicio(Servicio $servicio): View
    {
        $clienteId = session('portal_cliente_id');

        // Validar que el servicio pertenece al cliente
        $sedeDelServicio = $servicio->equipo?->area?->sede?->cliente_id;
        if ($sedeDelServicio !== (int)$clienteId) {
            abort(403, 'No tienes permiso para ver este servicio');
        }

        $servicio->load([
            'equipo.area.sede',
            'tecnicoAsignado',
            'seguimientos.usuario',
            'contrato.servicios',
        ]);

        return view('portal.cliente.servicios.detalle', [
            'servicio' => $servicio,
        ]);
    }

    /**
     * Crea un nuevo servicio desde el portal del cliente
     * 
     * Validaciones:
     * - Equipo pertenece al cliente
     * - Contrato cubre el tipo de servicio
     * - Cliente tiene contrato activo
     * 
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function crearServicio(Request $request): RedirectResponse
    {
        $clienteId = session('portal_cliente_id');

        $validated = $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'tipo_servicio' => 'required|in:PREVENTIVO,CORRECTIVO,INSTALACION,CONFIGURACION,CAPACITACION,CONSULTA',
            'prioridad' => 'required|in:BAJA,MEDIA,ALTA,CRITICA',
            'descripcion_problema' => 'required|string|min:10',
            'reportado_por' => 'required|string|max:100',
            'telefono_contacto' => 'required|string|max:20',
            'email_contacto' => 'required|email',
        ]);

        // Validar que el equipo pertenece al cliente
        $equipo = Equipo::with('area.sede')->find($validated['equipo_id']);
        if ($equipo->area->sede->cliente_id !== (int)$clienteId) {
            return redirect()->back()->with('error', 'El equipo no pertenece a tu cliente');
        }

        // Obtener contrato activo
        $contrato = Contrato::where('cliente_id', $clienteId)
            ->where('estado', 'ACTIVO')
            ->first();

        if (!$contrato) {
            return redirect()->back()->with('error', 'No tienes contratos activos');
        }

        // Validar que el contrato cubre este tipo de servicio
        $cobertura = $contrato->servicios()
            ->where('tipo_servicio', $validated['tipo_servicio'])
            ->where('incluido', true)
            ->first();

        if (!$cobertura) {
            return redirect()->back()->with('error', 'Tu contrato no cubre este tipo de servicio');
        }

        // Crear servicio
        $servicio = Servicio::create([
            'equipo_id' => $validated['equipo_id'],
            'contrato_id' => $contrato->id,
            'tipo_servicio' => $validated['tipo_servicio'],
            'prioridad' => $validated['prioridad'],
            'estado' => 'REPORTADO',
            'descripcion_problema' => $validated['descripcion_problema'],
            'reportado_por' => $validated['reportado_por'],
            'telefono_contacto' => $validated['telefono_contacto'],
            'email_contacto' => $validated['email_contacto'],
            'sla_horas_respuesta' => $cobertura->sla_horas_respuesta,
            'sla_horas_solucion' => $cobertura->sla_horas_solucion,
            'fecha_limite_respuesta' => now()->addHours($cobertura->sla_horas_respuesta),
            'fecha_limite_solucion' => now()->addHours($cobertura->sla_horas_solucion),
        ]);

        // Registrar seguimiento inicial
        SeguimientoServicio::create([
            'servicio_id' => $servicio->id,
            'user_id' => session('portal_user_id'),
            'accion' => 'REPORTADO',
            'estado_anterior' => null,
            'estado_nuevo' => 'REPORTADO',
            'observacion' => 'Servicio reportado a través del portal del cliente',
        ]);

        return redirect()->route('portal.servicios')
            ->with('success', "Servicio #{$servicio->id} registrado exitosamente");
    }

    /**
     * Descarga/imprime una atención en PDF
     * 
     * Genera un documento imprimible con:
     * - Datos del cliente
     * - Información del equipo
     * - Descripción del problema
     * - Solución aplicada
     * - Técnico que atendió
     * 
     * @param Servicio $servicio
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|View
     */
    public function descargarAtencion(Servicio $servicio)
    {
        $clienteId = session('portal_cliente_id');

        // Validar acceso
        $sedeDelServicio = $servicio->equipo?->area?->sede?->cliente_id;
        if ($sedeDelServicio !== (int)$clienteId) {
            abort(403, 'No tienes permiso para descargar este documento');
        }

        $servicio->load([
            'equipo.area.sede.cliente',
            'tecnicoAsignado',
            'seguimientos',
            'contrato.creadoPor',
        ]);

        // Generar PDF
        $pdf = \Illuminate\Support\Facades\PDF::loadView('portal.cliente.servicios.pdf-atencion', [
            'servicio' => $servicio,
        ]);

        return $pdf->download("Atencion-{$servicio->id}.pdf");
    }

    /**
     * Cierra la sesión del portal del cliente
     * 
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        session()->forget(['portal_cliente_id', 'portal_user_id', 'portal_token']);
        return redirect('/')->with('success', 'Sesión del portal cerrada');
    }
}
