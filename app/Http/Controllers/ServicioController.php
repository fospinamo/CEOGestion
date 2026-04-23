<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Equipo;
use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\ContratoServicio;
use App\Models\SeguimientoServicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

/**
 * ServicioController
 * 
 * Gestiona operaciones CRUD para servicios/atenciones TI.
 * Incluye tickets de soporte, diagnóstico, seguimiento y calificación del cliente.
 */
class ServicioController extends Controller
{
    /**
     * Listar todos los servicios con paginación
     */
    public function index(): View
    {
        $servicios = Servicio::with([
            'equipo.area.sede.cliente',
            'contrato.cliente'
        ])
        ->get();

        return view('servicios.index', compact('servicios'));
    }

    /**
     * Formulario para crear nuevo servicio
     */
    public function create(): View
    {
        $servicio = null;
        $clientes = Cliente::where('estado', true)
            ->orderBy('razon_social')
            ->get();

        return view('servicios.create', compact('servicio', 'clientes'));
    }

    /**
     * Guardar nuevo servicio con validaciones de negocio
     */
    public function store(Request $request): RedirectResponse
    {
        // Validaciones básicas
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'equipo_id' => 'required|exists:equipos,id',
            'tipo_servicio' => 'required|in:PREVENTIVO,CORRECTIVO,INSTALACION,CONFIGURACION,CAPACITACION,CONSULTA',
            'prioridad' => 'required|in:BAJA,MEDIA,ALTA,CRITICA',
            'reportado_por' => 'required|string|max:255',
            'telefono_contacto' => 'required|string|max:20',
            'email_contacto' => 'nullable|email',
            'descripcion_problema' => 'required|string|min:10',
        ]);

        // VALIDACIÓN 1: El equipo pertenece al cliente
        $equipo = Equipo::where('id', $request->equipo_id)
            ->where('cliente_id', $request->cliente_id)
            ->first();
        
        if (!$equipo) {
            return back()
                ->withErrors(['equipo_id' => 'El equipo seleccionado no pertenece al cliente'])
                ->withInput();
        }

        // VALIDACIÓN 2: Cliente tiene contrato activo
        $contrato = Contrato::where('cliente_id', $request->cliente_id)
            ->where('estado', 'ACTIVO')
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->first();
        
        if (!$contrato) {
            return back()
                ->withErrors(['cliente_id' => 'El cliente no tiene un contrato activo vigente'])
                ->withInput();
        }

        // VALIDACIÓN 3: Contrato cubre este tipo de servicio
        $cobertura = ContratoServicio::where('contrato_id', $contrato->id)
            ->where('tipo_servicio', $request->tipo_servicio)
            ->where('incluido', true)
            ->first();
        
        if (!$cobertura) {
            return back()
                ->withErrors(['tipo_servicio' => 'Este tipo de servicio no está cubierto por el contrato del cliente'])
                ->withInput();
        }

        // Calcular SLAs
        $slaRespuesta = $cobertura->sla_horas_respuesta ?? $contrato->sla_default_horas_respuesta;
        $slaSolucion = $cobertura->sla_horas_solucion ?? $contrato->sla_default_horas_solucion;

        // Crear servicio
        $servicio = Servicio::create([
            'cliente_id' => $request->cliente_id,
            'equipo_id' => $request->equipo_id,
            'contrato_id' => $contrato->id,
            'tipo_servicio' => $request->tipo_servicio,
            'prioridad' => $request->prioridad,
            'fecha_solicitud' => now(),
            'solicitado_por' => $request->reportado_por,
            'contacto_solicitante' => $request->telefono_contacto,
            'descripcion_problema' => $request->descripcion_problema,
            'estado' => 'PENDIENTE',
            'tecnico_asignado' => 'SIN ASIGNAR',
            'sla_horas_respuesta' => $slaRespuesta,
            'sla_horas_solucion' => $slaSolucion,
            'sla_fecha_limite_respuesta' => now()->addHours($slaRespuesta),
            'sla_fecha_limite_solucion' => now()->addHours($slaSolucion),
        ]);

        // Crear seguimiento inicial
        SeguimientoServicio::create([
            'servicio_id' => $servicio->id,
            'user_id' => auth()->id(),
            'accion' => 'CREACION',
            'observacion' => 'Servicio registrado por ' . auth()->user()->name,
            'estado_nuevo' => 'PENDIENTE',
            'metadata' => [
                'reportado_por' => $request->reportado_por,
                'telefono' => $request->telefono_contacto,
                'email' => $request->email_contacto,
            ]
        ]);

        return redirect()->route('servicios.show', $servicio)
            ->with('success', 'Servicio registrado exitosamente');
    }

    /**
     * Mostrar detalles de un servicio
     */
    public function show(Servicio $servicio): View
    {
        $servicio->load([
            'equipo.area.sede.cliente.empresa',
            'contrato.cliente',
            'documentosAdjuntos'
        ]);

        return view('servicios.show', compact('servicio'));
    }

    /**
     * Formulario para editar servicio
     */
    public function edit(Servicio $servicio): View
    {
        $equipos = Equipo::with('area.sede.cliente')
            ->where('estado_operativo', '!=', 'BAJA')
            ->orderBy('codigo_interno')
            ->get();

        $contratos = Contrato::with('cliente')
            ->where('estado', 'ACTIVO')
            ->orderBy('numero_contrato')
            ->get();

        return view('servicios.edit', compact('servicio', 'equipos', 'contratos'));
    }

    /**
     * Actualizar servicio
     */
    public function update(Request $request, Servicio $servicio): RedirectResponse
    {
        $validated = $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'contrato_id' => 'nullable|exists:contratos,id',
            'tipo_servicio' => 'required|in:PREVENTIVO,CORRECTIVO,INSTALACION,CONFIGURACION,CAPACITACION,CONSULTA',
            'prioridad' => 'required|in:BAJA,MEDIA,ALTA,URGENTE',
            'fecha_solicitud' => 'required|datetime',
            'fecha_atencion' => 'nullable|datetime',
            'fecha_cierre' => 'nullable|datetime',
            'solicitado_por' => 'required|string|max:255',
            'contacto_solicitante' => 'required|string|max:255',
            'descripcion_problema' => 'required|string',
            'diagnostico' => 'nullable|string',
            'solucion_aplicada' => 'nullable|string',
            'repuestos_utilizados' => 'nullable|string',
            'horas_trabajadas' => 'nullable|numeric|min:0|max:999.99',
            'tecnico_asignado' => 'required|string|max:255',
            'tecnico_cedula' => 'nullable|string|max:20',
            'estado' => 'required|in:PENDIENTE,EN_PROCESO,RESUELTO,CERRADO,CANCELADO',
            'calificacion_cliente' => 'nullable|integer|min:1|max:5',
            'comentarios_cliente' => 'nullable|string',
        ]);

        // Convertir repuestos a JSON si existe
        if ($request->has('repuestos_utilizados') && !empty($request->repuestos_utilizados)) {
            $validated['repuestos_utilizados'] = json_decode($request->repuestos_utilizados, true);
        }

        $servicio->update($validated);

        return redirect()->route('servicios.show', $servicio)
            ->with('success', 'Servicio actualizado exitosamente');
    }

    /**
     * Eliminar servicio (soft delete)
     */
    public function destroy(Servicio $servicio): RedirectResponse
    {
        $servicio->delete();

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio eliminado exitosamente');
    }

    /**
     * MÉTODO 1: Obtener equipos por cliente (AJAX)
     */
    public function getEquiposByCliente($cliente_id): JsonResponse
    {
        $equipos = Equipo::where('cliente_id', $cliente_id)
            ->where('estado_operativo', 'OPERATIVO')
            ->orderBy('codigo_interno')
            ->get(['id', 'codigo_interno', 'marca', 'modelo', 'serial']);
        
        return response()->json($equipos);
    }

    /**
     * MÉTODO 2: Obtener contrato activo del cliente (AJAX)
     */
    public function getContratoActivo($cliente_id): JsonResponse
    {
        $contrato = Contrato::where('cliente_id', $cliente_id)
            ->where('estado', 'ACTIVO')
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->first();
        
        if (!$contrato) {
            return response()->json([
                'error' => 'Cliente sin contrato activo'
            ], 404);
        }
        
        $serviciosCubiertos = ContratoServicio::where('contrato_id', $contrato->id)
            ->where('incluido', true)
            ->pluck('tipo_servicio');
        
        return response()->json([
            'contrato' => $contrato,
            'servicios_cubiertos' => $serviciosCubiertos,
            'sla_respuesta' => $contrato->sla_default_horas_respuesta ?? 4,
            'sla_solucion' => $contrato->sla_default_horas_solucion ?? 24
        ]);
    }

    /**
     * MÉTODO 3: Generar código único de servicio
     */
    private function generarCodigoServicio(): string
    {
        $year = date('Y');
        $last = Servicio::whereYear('created_at', $year)->count();
        $numero = str_pad($last + 1, 5, '0', STR_PAD_LEFT);
        return "SVC-{$year}-{$numero}";
    }

    /**
     * MÉTODO 4: Asignar técnico a servicio
     */
    public function asignarTecnico(Request $request, $id): RedirectResponse
    {
        $servicio = Servicio::findOrFail($id);
        
        $request->validate([
            'tecnico_id' => 'required|exists:users,id',
        ]);
        
        $tecnico = User::find($request->tecnico_id);
        
        $servicio->update([
            'tecnico_asignado_id' => $request->tecnico_id,
            'tecnico_asignado' => $tecnico->name,
            'fecha_asignacion' => now(),
            'estado' => 'ASIGNADO'
        ]);
        
        SeguimientoServicio::create([
            'servicio_id' => $servicio->id,
            'user_id' => auth()->id(),
            'accion' => 'ASIGNACION',
            'observacion' => 'Técnico asignado: ' . $tecnico->name,
            'estado_anterior' => 'PENDIENTE',
            'estado_nuevo' => 'ASIGNADO'
        ]);
        
        return redirect()->route('servicios.show', $servicio)
            ->with('success', 'Técnico asignado correctamente');
    }

    /**
     * MÉTODO 5: Cambiar estado del servicio
     */
    public function cambiarEstado(Request $request, $id): RedirectResponse
    {
        $servicio = Servicio::findOrFail($id);
        $estadoAnterior = $servicio->estado;
        
        $request->validate([
            'estado' => 'required|in:PENDIENTE,EN_PROCESO,RESUELTO,CERRADO,CANCELADO',
            'observacion' => 'nullable|string',
        ]);
        
        $actualizaciones = ['estado' => $request->estado];
        
        // Registrar fechas automáticamente según estado
        if ($request->estado == 'EN_PROCESO' && !$servicio->fecha_inicio_atencion) {
            $actualizaciones['fecha_inicio_atencion'] = now();
        }
        
        if ($request->estado == 'RESUELTO' && !$servicio->fecha_resolucion) {
            $actualizaciones['fecha_resolucion'] = now();
        }
        
        if ($request->estado == 'CERRADO' && !$servicio->fecha_cierre_real) {
            $actualizaciones['fecha_cierre_real'] = now();
            $actualizaciones['fecha_cierre'] = now();
        }
        
        $servicio->update($actualizaciones);
        
        SeguimientoServicio::create([
            'servicio_id' => $servicio->id,
            'user_id' => auth()->id(),
            'accion' => 'CAMBIO_ESTADO',
            'observacion' => $request->observacion ?? "Cambio de estado: $estadoAnterior → {$request->estado}",
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $request->estado
        ]);
        
        return redirect()->route('servicios.show', $servicio)
            ->with('success', 'Estado actualizado correctamente');
    }
}
