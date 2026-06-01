<?php

namespace App\Http\Controllers\Incidencias;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\Equipo;
use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\Sede;
use App\Models\Area;
use App\Models\EstadoServicio;
use App\Models\ContratoServicio;
use App\Models\SeguimientoServicio;
use App\Models\DocumentoAdjunto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * ServicioController
 * 
 * Gestiona operaciones CRUD para servicios/atenciones TI.
 * Incluye tickets de soporte, diagnóstico, seguimiento y calificación del cliente.
 */
class ServicioController extends Controller
{
    /**
     * Listar todos los servicios con paginación y filtros
     */
    public function index(): View
    {
        // Obtener parámetros de filtro
        $clienteFilter = request('cliente_id');
        $fechaDesde = request('fecha_desde');
        $fechaHasta = request('fecha_hasta');
        $estadoFilter = request('estado');

        // Base query
        $query = Servicio::with([
            'equipo.area.sede.cliente',
            'contrato.cliente',
            'tecnicoResponsable',
            'estadoServicio'
        ]);

        // Aplicar filtros
        if ($clienteFilter) {
            $query->whereHas('equipo.area.sede', function($q) use ($clienteFilter) {
                $q->where('cliente_id', $clienteFilter);
            });
        }

        if ($fechaDesde) {
            $query->whereDate('fecha_solicitud', '>=', $fechaDesde);
        }

        if ($fechaHasta) {
            $query->whereDate('fecha_solicitud', '<=', $fechaHasta);
        }

        if ($estadoFilter) {
            if ($estadoFilter === 'SIN_ASIGNAR') {
                $query->whereNull('tecnico_id');
            } else if ($estadoFilter === 'ASIGNADO') {
                $query->whereNotNull('tecnico_id')->whereNull('estado_servicio_id');
            } else {
                // Buscar por nombre de estado en la tabla estado_servicios
                $query->whereHas('estadoServicio', function($q) use ($estadoFilter) {
                    $q->where('nombre', $estadoFilter);
                });
            }
        }

        $servicios = $query->orderByDesc('fecha_solicitud')->get();

        // Obtener datos para los filtros
        $clientes = Cliente::where('estado', true)->orderBy('razon_social')->get();
        $estados = EstadoServicio::activos()->get();

        return view('incidencias.servicios.index', compact('servicios', 'clientes', 'estados', 'clienteFilter', 'fechaDesde', 'fechaHasta', 'estadoFilter'));
    }

    /**
     * Formulario para crear nuevo servicio
     */
    public function create(): View
    {
        try {
            $servicio = null;
            $clientes = Cliente::where('estado', true)
                ->orderBy('razon_social')
                ->get();
            
            $sedes = \App\Models\Sede::with('cliente', 'empresa')
                ->where('estado', true)
                ->orderBy('nombre')
                ->get();
            
            $areas = \App\Models\Area::with('sede')
                ->where('estado', true)
                ->orderBy('nombre')
                ->get();

            return view('incidencias.servicios.create', compact('servicio', 'clientes', 'sedes', 'areas'));
        } catch (\Exception $e) {
            // En caso de error, retornar con datos por defecto
            return view('incidencias.servicios.create', [
                'servicio' => null,
                'clientes' => Cliente::where('estado', true)->orderBy('razon_social')->get(),
                'sedes' => \App\Models\Sede::where('estado', true)->orderBy('nombre')->get(),
                'areas' => \App\Models\Area::where('estado', true)->orderBy('nombre')->get(),
            ])->withErrors(['error' => 'Error al cargar los datos']);
        }
    }

    /**
     * Guardar nuevo servicio con validaciones de negocio
     */
    public function store(Request $request): RedirectResponse
    {
        // Compatibilidad con valores antiguos enviados por UI/cache previo.
        // La BD usa enum: BAJA, MEDIA, ALTA, URGENTE.
        if ($request->prioridad === 'CRITICA') {
            $request->merge(['prioridad' => 'URGENTE']);
        }

        // Validaciones básicas
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'sede_id' => 'required|exists:sedes,id',
            'area_id' => 'required|exists:areas,id',
            'equipo_id' => 'required|exists:equipos,id',
            'tipo_servicio' => 'required|in:PREVENTIVO,CORRECTIVO,INSTALACION,CONFIGURACION,CAPACITACION,CONSULTA',
            'prioridad' => 'required|in:BAJA,MEDIA,ALTA,URGENTE',
            'reportado_por' => 'required|string|max:255',
            'telefono_contacto' => 'required|string|max:20',
            'email_contacto' => 'nullable|email',
            'descripcion_problema' => 'required|string|min:10',
            'observaciones' => 'nullable|string',
            'documentos_adjuntos.*' => 'nullable|file|max:5120', // 5MB por archivo
        ]);

        // VALIDACIÓN 1: La sede pertenece al cliente
        $sede = Sede::where('id', $request->sede_id)
            ->where('cliente_id', $request->cliente_id)
            ->first();
        
        if (!$sede) {
            return back()
                ->withErrors(['sede_id' => 'La sede seleccionada no pertenece al cliente'])
                ->withInput();
        }

        // VALIDACIÓN 2: El área pertenece a la sede
        $area = Area::where('id', $request->area_id)
            ->where('sede_id', $request->sede_id)
            ->first();
        
        if (!$area) {
            return back()
                ->withErrors(['area_id' => 'El área seleccionada no pertenece a la sede'])
                ->withInput();
        }

        // VALIDACIÓN 3: El equipo pertenece al área y NO está dado de baja ni obsoleto
        // Permite estados: OPERATIVO, MANTENIMIENTO, REPARACION
        $equipo = Equipo::where('id', $request->equipo_id)
            ->where('area_id', $request->area_id)
            ->whereNotIn('estado_operativo', ['BAJA', 'OBSOLETO'])
            ->first();
        
        if (!$equipo) {
            return back()
                ->withErrors(['equipo_id' => 'El equipo seleccionado no pertenece al área o no está disponible (BAJA/OBSOLETO)'])
                ->withInput();
        }

        // VALIDACIÓN 4: Cliente tiene contrato activo (OPCIONAL)
        // Se permite registrar servicio sin contrato, pero se registra como fuera de contrato
        $contrato = Contrato::where('cliente_id', $request->cliente_id)
            ->where('estado', 'ACTIVO')
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->first();
        
        $slaRespuesta = 4;  // Default SLA: 4 horas
        $slaSolucion = 24;  // Default SLA: 24 horas
        $contratoId = null;

        // Si existe contrato, verificar cobertura y obtener SLAs
        if ($contrato) {
            $contratoId = $contrato->id;
            
            // VALIDACIÓN 5: Contrato cubre este tipo de servicio
            $cobertura = ContratoServicio::where('contrato_id', $contrato->id)
                ->where('tipo_servicio', $request->tipo_servicio)
                ->where('incluido', true)
                ->first();
            
            // Si hay servicios específicos pero este no está incluido, error
            $tieneServicios = ContratoServicio::where('contrato_id', $contrato->id)->exists();
            
            if ($tieneServicios && !$cobertura) {
                return back()
                    ->withErrors(['tipo_servicio' => 'Este tipo de servicio no está cubierto por el contrato del cliente. El servicio se registrará fuera de contrato.'])
                    ->withInput();
            }

            // Calcular SLAs desde cobertura o contrato
            if ($cobertura) {
                $slaRespuesta = $cobertura->sla_horas_respuesta ?? $contrato->sla_default_horas_respuesta ?? 4;
                $slaSolucion = $cobertura->sla_horas_solucion ?? $contrato->sla_default_horas_solucion ?? 24;
            } else {
                $slaRespuesta = $contrato->sla_default_horas_respuesta ?? 4;
                $slaSolucion = $contrato->sla_default_horas_solucion ?? 24;
            }
        }

        // Crear servicio
        $servicio = Servicio::create([
            'cliente_id' => $request->cliente_id,
            'equipo_id' => $request->equipo_id,
            'contrato_id' => $contratoId,  // Puede ser NULL si es fuera de contrato
            'tipo_servicio' => $request->tipo_servicio,
            'prioridad' => $request->prioridad,
            'fecha_solicitud' => now(),
            'solicitado_por' => $request->reportado_por,
            'contacto_solicitante' => $request->telefono_contacto,
            'descripcion_problema' => $request->descripcion_problema,
            'observaciones' => $request->observaciones,
            'estado' => 'PENDIENTE',
            'tecnico_asignado' => 'SIN ASIGNAR',
            'sla_horas_respuesta' => $slaRespuesta,
            'sla_horas_solucion' => $slaSolucion,
            'sla_fecha_limite_respuesta' => now()->addHours($slaRespuesta),
            'sla_fecha_limite_solucion' => now()->addHours($slaSolucion),
        ]);

        // Procesar archivos adjuntos si existen
        if ($request->hasFile('documentos_adjuntos')) {
            foreach ($request->file('documentos_adjuntos') as $archivo) {
                if ($archivo->isValid()) {
                    $ruta = $archivo->store('servicios/' . $servicio->id, 'private');
                    
                    \App\Models\DocumentoAdjunto::create([
                        'entidad_type' => Servicio::class,
                        'entidad_id' => $servicio->id,
                        'nombre_archivo' => $archivo->getClientOriginalName(),
                        'ruta_archivo' => $ruta,
                        'tipo_documento' => 'OTRO', // Clasificar como OTRO para documentos adjuntos en servicios
                        'mime_type' => $archivo->getMimeType(),
                        'tamaño_bytes' => $archivo->getSize(),
                        'descripcion' => 'Documento adjunto al servicio durante registro',
                        'subido_por' => auth()->id(),
                    ]);
                }
            }
        }

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

        return redirect()->route('incidencias.servicios.show', $servicio)
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

        return view('incidencias.servicios.show', compact('servicio'));
    }

    /**
     * Ver documento adjunto de un servicio (inline).
     */
    public function verDocumentoAdjunto(Servicio $servicio, DocumentoAdjunto $documento)
    {
        if (
            $documento->entidad_type !== Servicio::class ||
            (int) $documento->entidad_id !== (int) $servicio->id
        ) {
            abort(404);
        }

        if (!Storage::disk('private')->exists($documento->ruta_archivo)) {
            abort(404);
        }

        $contenido = Storage::disk('private')->get($documento->ruta_archivo);
        $mimeType = $documento->mime_type ?: Storage::disk('private')->mimeType($documento->ruta_archivo);

        return response($contenido, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $documento->nombre_archivo . '"',
        ]);
    }

    /**
     * Descargar documento adjunto de un servicio.
     */
    public function descargarDocumentoAdjunto(Servicio $servicio, DocumentoAdjunto $documento)
    {
        if (
            $documento->entidad_type !== Servicio::class ||
            (int) $documento->entidad_id !== (int) $servicio->id
        ) {
            abort(404);
        }

        if (!Storage::disk('private')->exists($documento->ruta_archivo)) {
            abort(404);
        }

        return Storage::disk('private')->download($documento->ruta_archivo, $documento->nombre_archivo);
    }

    /**
     * Formulario para editar servicio
     */
    public function edit(Servicio $servicio): View
    {
        try {
            $servicio->load([
                'equipo.marca',
                'equipo.area.sede.cliente.empresa',
                'contrato.cliente',
                'documentosAdjuntos',
            ]);

            $clientes = Cliente::where('estado', true)
                ->orderBy('razon_social')
                ->get();
            
            $sedes = \App\Models\Sede::with('cliente', 'empresa')
                ->where('estado', true)
                ->orderBy('nombre')
                ->get();
            
            $areas = \App\Models\Area::with('sede')
                ->where('estado', true)
                ->orderBy('nombre')
                ->get();

            $isEdit = true;

            return view('incidencias.servicios.create', compact('servicio', 'clientes', 'sedes', 'areas', 'isEdit'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al cargar los datos']);
        }
    }

    /**
     * Actualizar servicio
     */
    public function update(Request $request, Servicio $servicio): RedirectResponse
    {
        // Compatibilidad con valores antiguos enviados por UI/cache previo.
        if ($request->prioridad === 'CRITICA') {
            $request->merge(['prioridad' => 'URGENTE']);
        }

        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'sede_id' => 'required|exists:sedes,id',
            'area_id' => 'required|exists:areas,id',
            'equipo_id' => 'required|exists:equipos,id',
            'tipo_servicio' => 'required|in:PREVENTIVO,CORRECTIVO,INSTALACION,CONFIGURACION,CAPACITACION,CONSULTA',
            'prioridad' => 'required|in:BAJA,MEDIA,ALTA,URGENTE',
            'reportado_por' => 'required|string|max:255',
            'telefono_contacto' => 'required|string|max:20',
            'email_contacto' => 'nullable|email',
            'descripcion_problema' => 'required|string|min:10',
            'observaciones' => 'nullable|string',
            'documentos_adjuntos.*' => 'nullable|file|max:5120',
        ]);

        // VALIDACIÓN 1: La sede pertenece al cliente
        $sede = Sede::where('id', $request->sede_id)
            ->where('cliente_id', $request->cliente_id)
            ->first();

        if (!$sede) {
            return back()
                ->withErrors(['sede_id' => 'La sede seleccionada no pertenece al cliente'])
                ->withInput();
        }

        // VALIDACIÓN 2: El área pertenece a la sede
        $area = Area::where('id', $request->area_id)
            ->where('sede_id', $request->sede_id)
            ->first();

        if (!$area) {
            return back()
                ->withErrors(['area_id' => 'El área seleccionada no pertenece a la sede'])
                ->withInput();
        }

        // VALIDACIÓN 3: El equipo pertenece al área y está disponible
        $equipo = Equipo::where('id', $request->equipo_id)
            ->where('area_id', $request->area_id)
            ->whereNotIn('estado_operativo', ['BAJA', 'OBSOLETO'])
            ->first();

        if (!$equipo) {
            return back()
                ->withErrors(['equipo_id' => 'El equipo seleccionado no pertenece al área o no está disponible (BAJA/OBSOLETO)'])
                ->withInput();
        }

        // Resolver contrato y SLA según cliente seleccionado
        $contrato = Contrato::where('cliente_id', $request->cliente_id)
            ->where('estado', 'ACTIVO')
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->first();

        $slaRespuesta = 4;
        $slaSolucion = 24;
        $contratoId = null;

        if ($contrato) {
            $contratoId = $contrato->id;

            $cobertura = ContratoServicio::where('contrato_id', $contrato->id)
                ->where('tipo_servicio', $request->tipo_servicio)
                ->where('incluido', true)
                ->first();

            $tieneServicios = ContratoServicio::where('contrato_id', $contrato->id)->exists();

            if ($tieneServicios && !$cobertura) {
                return back()
                    ->withErrors(['tipo_servicio' => 'Este tipo de servicio no está cubierto por el contrato del cliente.'])
                    ->withInput();
            }

            if ($cobertura) {
                $slaRespuesta = $cobertura->sla_horas_respuesta ?? $contrato->sla_default_horas_respuesta ?? 4;
                $slaSolucion = $cobertura->sla_horas_solucion ?? $contrato->sla_default_horas_solucion ?? 24;
            } else {
                $slaRespuesta = $contrato->sla_default_horas_respuesta ?? 4;
                $slaSolucion = $contrato->sla_default_horas_solucion ?? 24;
            }
        }

        $servicio->update([
            'equipo_id' => $request->equipo_id,
            'contrato_id' => $contratoId,
            'tipo_servicio' => $request->tipo_servicio,
            'prioridad' => $request->prioridad,
            'solicitado_por' => $request->reportado_por,
            'contacto_solicitante' => $request->telefono_contacto,
            'descripcion_problema' => $request->descripcion_problema,
            'observaciones' => $request->observaciones,
            'sla_horas_respuesta' => $slaRespuesta,
            'sla_horas_solucion' => $slaSolucion,
            'sla_fecha_limite_respuesta' => now()->addHours($slaRespuesta),
            'sla_fecha_limite_solucion' => now()->addHours($slaSolucion),
        ]);

        if ($request->hasFile('documentos_adjuntos')) {
            foreach ($request->file('documentos_adjuntos') as $archivo) {
                if ($archivo->isValid()) {
                    $ruta = $archivo->store('servicios/' . $servicio->id, 'private');

                    \App\Models\DocumentoAdjunto::create([
                        'entidad_type' => Servicio::class,
                        'entidad_id' => $servicio->id,
                        'nombre_archivo' => $archivo->getClientOriginalName(),
                        'ruta_archivo' => $ruta,
                        'tipo_documento' => 'OTRO',
                        'mime_type' => $archivo->getMimeType(),
                        'tamaño_bytes' => $archivo->getSize(),
                        'descripcion' => 'Documento adjunto al servicio durante edición',
                        'subido_por' => auth()->id(),
                    ]);
                }
            }
        }

        return redirect()->route('incidencias.servicios.show', $servicio)
            ->with('success', 'Servicio actualizado exitosamente');
    }

    /**
     * Eliminar servicio (soft delete)
     */
    public function destroy(Servicio $servicio): RedirectResponse
    {
        $servicio->delete();

        return redirect()->route('incidencias.servicios.index')
            ->with('success', 'Servicio eliminado exitosamente');
    }

    /**
     * MÉTODO 1: Obtener equipos por cliente (AJAX)
     */
    public function getEquiposByCliente($cliente_id): JsonResponse
    {
        $equipos = Equipo::with('marca')
            ->where('cliente_id', $cliente_id)
            ->where('estado_operativo', 'OPERATIVO')
            ->orderBy('codigo_activo_cliente')
            ->get(['id', 'codigo_activo_cliente', 'marca_id', 'modelo', 'serial'])
            ->map(fn($e) => [
                'id'              => $e->id,
                'codigo_interno'  => $e->codigo_activo_cliente,
                'marca'           => $e->marca?->nombre ?? '',
                'modelo'          => $e->modelo,
                'serial'          => $e->serial,
            ]);
        
        return response()->json($equipos);
    }

    /**
     * MÉTODO 1B: Obtener equipos por área (AJAX)
     */
    public function getEquiposByArea($area_id): JsonResponse
    {
        // Obtener equipos del área que NO están dados de baja ni obsoletos
        // Estados permitidos: OPERATIVO, MANTENIMIENTO, REPARACION
        // Estados NO permitidos: BAJA, OBSOLETO
        $equipos = Equipo::with('marca')
            ->where('area_id', $area_id)
            ->whereNotIn('estado_operativo', ['BAJA', 'OBSOLETO'])
            ->orderBy('codigo_activo_cliente')
            ->get(['id', 'codigo_activo_cliente', 'marca_id', 'modelo', 'serial', 'estado_operativo'])
            ->map(fn($e) => [
                'id'              => $e->id,
                'codigo_interno'  => $e->codigo_activo_cliente,
                'marca'           => $e->marca?->nombre ?? '',
                'modelo'          => $e->modelo,
                'serial'          => $e->serial,
                'estado_operativo'=> $e->estado_operativo,
            ]);
        
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
     * MÉTODO 4: Crear equipo rápidamente desde formulario (AJAX)
     */
    public function crearEquipo(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'codigo_activo_cliente' => 'required|string|max:50|unique:equipos,codigo_activo_cliente',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'serial' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $equipo = Equipo::create([
                'area_id'              => $validated['area_id'],
                'codigo_activo_cliente'=> $validated['codigo_activo_cliente'],
                'marca_id'             => null,
                'modelo'               => $validated['modelo'],
                'serial'               => $validated['serial'] ?? null,
                'descripcion'          => $validated['descripcion'] ?? null,
                'estado_operativo'     => 'OPERATIVO',
                'tipo_equipo_id'       => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Equipo creado exitosamente',
                'equipo' => [
                    'id'              => $equipo->id,
                    'codigo_interno'  => $equipo->codigo_activo_cliente,
                    'marca'           => $equipo->marca_id,
                    'modelo'          => $equipo->modelo,
                    'serial'          => $equipo->serial,
                    'estado_operativo'=> $equipo->estado_operativo,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear equipo: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * MÉTODO 5: Generar código único de servicio
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
        
        return redirect()->route('incidencias.servicios.show', $servicio)
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
        
        return redirect()->route('incidencias.servicios.show', $servicio)
            ->with('success', 'Estado actualizado correctamente');
    }

    /**
     * Formulario para atender/resolver servicio con firma
     */
    public function attend(Servicio $servicio): View
    {
        // Cargar equipos adicionales disponibles en la misma área
        $equiposAdicionalesDisponibles = $servicio->equiposAdicionalesDisponibles();
        
        // Cargar relaciones necesarias
        $servicio->load([
            'equipo.area.sede.cliente',
            'contrato.cliente',
            'tecnico'
        ]);

        return view('incidencias.servicios.attend', compact('servicio', 'equiposAdicionalesDisponibles'));
    }

    /**
     * Guardar atención del servicio con firma
     */
    public function storeAttendance(Request $request, Servicio $servicio): RedirectResponse
    {
        $isDetailedReport = $request->hasAny([
            'fecha_atencion',
            'hora_inicio_atencion',
            'hora_fin_atencion',
            'diagnostico_validacion',
            'observaciones_informe',
        ]);

        $validated = $request->validate([
            'fecha_atencion' => $isDetailedReport ? 'required|date' : 'nullable|date',
            'hora_inicio_atencion' => $isDetailedReport ? 'required|date_format:H:i' : 'nullable|date_format:H:i',
            'hora_fin_atencion' => $isDetailedReport ? 'required|date_format:H:i' : 'nullable|date_format:H:i',
            'descripcion_atencion' => 'nullable|string|min:20|max:5000',
            'diagnostico_validacion' => $isDetailedReport ? 'required|string|min:10|max:5000' : 'nullable|string|min:20|max:5000',
            'observaciones_informe' => 'nullable|string|max:2000',
            'estado_servicio_id' => 'nullable|exists:estado_servicios,id',
            'persona_receptora_completa' => 'nullable|string|max:200',
            'persona_receptora_nombre' => 'nullable|string|max:100',
            'persona_receptora_apellido' => 'nullable|string|max:100',
            'persona_receptora_documento' => 'required|string|max:50',
            'firma_persona_receptora' => 'required|string',
            'equipos_adicionales' => 'nullable|array',
            'equipos_adicionales.*' => 'exists:equipos,id',
        ], [
            'descripcion_atencion.min' => 'La descripción debe tener al menos 20 caracteres',
            'persona_receptora_documento.required' => 'Debe ingresar el documento del receptor',
            'firma_persona_receptora.required' => 'Debe capturar la firma',
        ]);

        // Compatibilidad entre formularios:
        // - attend.blade.php envía descripcion_atencion
        // - report-technician-v2.blade.php envía diagnostico_validacion
        $descripcionAtencion = trim((string) ($validated['descripcion_atencion'] ?? $validated['diagnostico_validacion'] ?? ''));
        if (!$isDetailedReport && $descripcionAtencion === '') {
            return back()
                ->withInput()
                ->withErrors(['descripcion_atencion' => 'Debe describir lo realizado']);
        }

        // Compatibilidad para nombre/apellido:
        // - Algunos formularios envían persona_receptora_completa
        // - Otros envían persona_receptora_nombre + persona_receptora_apellido
        $nombre = trim((string) ($validated['persona_receptora_nombre'] ?? ''));
        $apellido = trim((string) ($validated['persona_receptora_apellido'] ?? ''));
        $personaCompleta = trim((string) ($validated['persona_receptora_completa'] ?? ''));

        if ($personaCompleta === '' && ($nombre !== '' || $apellido !== '')) {
            $personaCompleta = trim($nombre . ' ' . $apellido);
        }

        if ($personaCompleta === '') {
            return back()
                ->withInput()
                ->withErrors(['persona_receptora_completa' => 'Debe ingresar nombre y apellido']);
        }

        // Validar que los equipos seleccionados pertenezcan a la misma ubicación del servicio.
        if (!empty($validated['equipos_adicionales'])) {
            $ids = array_map('intval', $validated['equipos_adicionales']);
            $count = Equipo::where('area_id', $servicio->equipo->area_id)
                ->whereIn('id', $ids)
                ->count();

            if ($count !== count($ids)) {
                return back()
                    ->withInput()
                    ->withErrors(['equipos_adicionales' => 'Solo puede seleccionar equipos de la misma ubicación del servicio']);
            }
        }

        // Procesar nombre y apellido desde persona_receptora_completa
        if ($nombre === '' && $apellido === '') {
            $partes = explode(' ', $personaCompleta, 2);
            $validated['persona_receptora_nombre'] = $partes[0];
            $validated['persona_receptora_apellido'] = $partes[1] ?? '';
        } else {
            $validated['persona_receptora_nombre'] = $nombre;
            $validated['persona_receptora_apellido'] = $apellido;
        }

        // Validar que la firma no esté vacía
        if ($validated['firma_persona_receptora'] === 'data:image/png;base64,' || empty($validated['firma_persona_receptora'])) {
            return back()->withErrors(['firma_persona_receptora' => 'La firma no debe estar vacía']);
        }

        // Procesar firma base64 si es necesario (guardar como archivo o base64)
        $firmaData = $validated['firma_persona_receptora'];
        
        // Si la firma es base64 de canvas, convertir a archivo
        if (str_starts_with($firmaData, 'data:image')) {
            // Extraer base64 puro
            $firmaData = str_replace('data:image/png;base64,', '', $firmaData);
            
            // Generar nombre único para la firma
            $firmaPath = 'firmas/servicio_' . $servicio->id . '_' . time() . '.png';
            
            // Guardar archivo en storage
            \Storage::disk('public')->put($firmaPath, base64_decode($firmaData));
            
            $validated['firma_persona_receptora'] = $firmaPath;
        }

        $updateData = [
            'persona_receptora_nombre' => $validated['persona_receptora_nombre'],
            'persona_receptora_apellido' => $validated['persona_receptora_apellido'],
            'persona_receptora_documento' => $validated['persona_receptora_documento'],
            'firma_persona_receptora' => $validated['firma_persona_receptora'],
            'descripcion_atencion' => $descripcionAtencion,
            'diagnostico_validacion' => $validated['diagnostico_validacion'] ?? $descripcionAtencion,
            'diagnostico' => $validated['diagnostico_validacion'] ?? $descripcionAtencion,
            'observaciones_informe' => $validated['observaciones_informe'] ?? null,
            'equipos_adicionales_atendidos' => $validated['equipos_adicionales'] ?? [],
            'fecha_firma' => now(),
            'estado' => 'CERRADO',
            'fecha_cierre_real' => now(),
        ];

        if (!empty($validated['fecha_atencion'])) {
            $updateData['fecha_atencion'] = $validated['fecha_atencion'];
        }

        if (!empty($validated['hora_inicio_atencion'])) {
            $updateData['hora_inicio_atencion'] = $validated['hora_inicio_atencion'];
        }

        if (!empty($validated['hora_fin_atencion'])) {
            $updateData['hora_fin_atencion'] = $validated['hora_fin_atencion'];
        }

        if (!empty($validated['estado_servicio_id'])) {
            $updateData['estado_servicio_id'] = $validated['estado_servicio_id'];
        }

        // Calcular duración en horas decimales cuando hay hora inicio y fin.
        if (!empty($validated['hora_inicio_atencion']) && !empty($validated['hora_fin_atencion'])) {
            $inicioMin = ((int) substr($validated['hora_inicio_atencion'], 0, 2) * 60)
                + (int) substr($validated['hora_inicio_atencion'], 3, 2);
            $finMin = ((int) substr($validated['hora_fin_atencion'], 0, 2) * 60)
                + (int) substr($validated['hora_fin_atencion'], 3, 2);

            if ($finMin < $inicioMin) {
                $finMin += 24 * 60;
            }

            $duracionHoras = round(($finMin - $inicioMin) / 60, 2);
            $updateData['horas_trabajadas'] = $duracionHoras;
        }

        // Actualizar servicio con datos de atención
        $servicio->update($updateData);

        // Registrar seguimiento
        SeguimientoServicio::create([
            'servicio_id' => $servicio->id,
            'user_id' => auth()->id(),
            'accion' => 'CIERRE',
            'observacion' => 'Servicio cerrado por: ' . $validated['persona_receptora_nombre'] . ' ' . $validated['persona_receptora_apellido'],
            'metadata' => [
                'equipos_atendidos' => count($validated['equipos_adicionales'] ?? []),
                'documento_receptor' => $validated['persona_receptora_documento'],
            ]
        ]);

        return redirect()->route('incidencias.servicios.show', $servicio)
            ->with('success', 'Servicio atendido y cerrado correctamente. Firma capturada.');
    }

    /**
     * Panel del técnico - Lista sus servicios asignados
     */
    public function technicianPanel(): View
    {
        $tecnicoId = auth()->id();
        
        // Obtener servicios por estado
        // Estados posibles: PENDIENTE, EN_PROCESO, RESUELTO, CERRADO, CANCELADO
        
        // Servicios pendientes: con tecnico asignado pero estado PENDIENTE
        $serviciosPendientes = Servicio::where('tecnico_id', $tecnicoId)
            ->where('estado', 'PENDIENTE')
            ->with(['equipo.area.sede.cliente', 'estadoServicio'])
            ->orderByDesc('fecha_asignacion')
            ->get();

        // Servicios en proceso
        $serviciosEnProceso = Servicio::where('tecnico_id', $tecnicoId)
            ->where('estado', 'EN_PROCESO')
            ->with(['equipo.area.sede.cliente', 'estadoServicio'])
            ->orderByDesc('fecha_asignacion')
            ->get();

        // Servicios pendientes de repuesto
        $serviciosPendienteRepuesto = Servicio::where('tecnico_id', $tecnicoId)
            ->where('estado', 'PENDIENTE_REPUESTO')
            ->with(['equipo.area.sede.cliente', 'estadoServicio'])
            ->orderByDesc('fecha_asignacion')
            ->get();

        // Servicios completados (resueltos o cerrados)
        $serviciosCompletados = Servicio::where('tecnico_id', $tecnicoId)
            ->whereIn('estado', ['RESUELTO', 'CERRADO'])
            ->with(['equipo.area.sede.cliente', 'estadoServicio'])
            ->orderByDesc('fecha_cierre')
            ->get();

        return view('incidencias.servicios.technician-panel', compact(
            'serviciosPendientes',
            'serviciosEnProceso',
            'serviciosPendienteRepuesto',
            'serviciosCompletados'
        ));
    }

    /**
     * Panel de admin para ver todos los servicios asignados
     */
    public function adminAssignedPanel(): View
    {
        // Verificar que el usuario sea admin o coordinador
        abort_if(
            !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('coordinador'),
            403,
            'No tienes permisos para acceder a este panel'
        );

        // Obtener todos los técnicos con servicios asignados
        $tecnicos = User::whereRelation('role', 'slug', 'tecnico')
            ->with([
                'servicios' => function($q) {
                    $q->whereNotNull('tecnico_id')
                        ->with(['equipo.area.sede.cliente', 'estadoServicio'])
                        ->orderByDesc('fecha_solicitud');
                }
            ])
            ->where('estado', true)
            ->orderBy('name')
            ->get();

        // Contar servicios por estado
        $totalAsignados = Servicio::whereNotNull('tecnico_id')->count();
        $pendientes = Servicio::whereNotNull('tecnico_id')
            ->whereHas('estadoServicio', function($q) {
                $q->where('nombre', 'Asignado');
            })->count();
        $enProceso = Servicio::whereNotNull('tecnico_id')
            ->whereHas('estadoServicio', function($q) {
            $q->where('es_en_proceso', true);
        })->count();
        $completados = Servicio::whereNotNull('tecnico_id')
            ->whereHas('estadoServicio', function($q) {
            $q->where('es_cierre', true);
        })->count();

        return view('incidencias.servicios.admin-panel', compact(
            'tecnicos',
            'totalAsignados',
            'pendientes',
            'enProceso',
            'completados'
        ));
    }

    /**
     * Formulario para asignar técnico a servicio
     */
    public function assign(Servicio $servicio): View
    {
        $servicio->load([
            'equipo.area.sede.cliente',
            'contrato.cliente',
            'tecnicoResponsable'
        ]);

        // Obtener todos los técnicos activos (usando el nuevo sistema de roles)
        $tecnicos = User::whereRelation('role', 'slug', 'tecnico')
            ->where('estado', true)
            ->orderBy('name')
            ->get();

        return view('incidencias.servicios.assign', compact('servicio', 'tecnicos'));
    }

    /**
     * Guardar asignación de técnico
     */
    public function storeAssign(Request $request, Servicio $servicio): RedirectResponse
    {
        $validated = $request->validate([
            'tecnico_id' => 'required|exists:users,id',
            'fecha_asignacion' => 'required|date_format:Y-m-d\\TH:i',
            'enviar_whatsapp' => 'nullable|boolean',
        ], [
            'tecnico_id.required' => 'Debe seleccionar un técnico',
            'fecha_asignacion.required' => 'Debe ingresar la fecha y hora de asignación',
            'fecha_asignacion.date_format' => 'El formato de fecha y hora no es válido',
        ]);

        $tecnico = User::findOrFail($validated['tecnico_id']);

        // Validar que sea técnico (usando nuevo sistema de roles)
        if (!$tecnico->hasRole('tecnico')) {
            return back()->withErrors(['tecnico_id' => 'El usuario seleccionado no es un técnico']);
        }

        // Obtener o crear estado "ASIGNADO"
        $estadoAsignado = EstadoServicio::where('nombre', 'ASIGNADO')->first();

        // Actualizar servicio
        $updateData = [
            'tecnico_id' => $validated['tecnico_id'],
            'tecnico_asignado' => $tecnico->name, // Mantener compatibilidad
            'fecha_asignacion' => \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $validated['fecha_asignacion'])
                ->format('Y-m-d H:i:s'),
            'estado' => 'PENDIENTE', // Mantener estado ENUM válido
        ];

        // Si existe estado ASIGNADO, actualizar referencia
        if ($estadoAsignado) {
            $updateData['estado_servicio_id'] = $estadoAsignado->id;
        }

        $servicio->update($updateData);

        // Registrar seguimiento
        SeguimientoServicio::create([
            'servicio_id' => $servicio->id,
            'user_id' => auth()->id(),
            'accion' => 'ASIGNACION',
            'observacion' => "Servicio asignado al técnico: {$tecnico->name}",
            'estado_nuevo' => 'ASIGNADO',
            'metadata' => [
                'tecnico_id' => $tecnico->id,
                'tecnico_nombre' => $tecnico->name,
                'tecnico_email' => $tecnico->email,
                'tecnico_telefono' => $tecnico->telefono,
            ]
        ]);

        $successMessage = "Servicio asignado a {$tecnico->name} exitosamente";

        if ($request->boolean('enviar_whatsapp')) {
            $whatsappUrl = $this->construirUrlWhatsAppAsignacion($servicio, $tecnico);

            if ($whatsappUrl) {
                return redirect()->route('incidencias.servicios.show', $servicio)
                    ->with('success', $successMessage)
                    ->with('whatsapp_url', $whatsappUrl)
                    ->with('whatsapp_notice', 'Se abrió WhatsApp en una nueva pestaña con el mensaje prellenado.');
            }

            return redirect()->route('incidencias.servicios.show', $servicio)
                ->with('success', $successMessage)
                ->with('warning', 'No se pudo abrir WhatsApp porque el técnico no tiene un número válido.');
        }

        return redirect()->route('incidencias.servicios.show', $servicio)
            ->with('success', $successMessage);
    }

    /**
     * Formulario para informe técnico (para técnico)
     */
    public function report(Servicio $servicio): View
    {
        // Validar que el servicio esté asignado
        if ($servicio->tecnico_id === null) {
            return redirect()->route('incidencias.servicios.index')
                ->withErrors(['error' => 'Este servicio no está asignado a un técnico']);
        }

        $servicio->load([
            'equipo.area.sede.cliente.empresa',
            'equipo.area.sede.municipio.departamento',
            'equipo.area.sede.barrio',
            'contrato.cliente.empresa',
            'tecnicoResponsable',
            'estadoServicio'
        ]);

        // Obtener TODOS los equipos de la misma ubicación (área)
        $equiposAdicionales = Equipo::with(['tipoEquipo', 'marca', 'contrato'])
            ->where('area_id', $servicio->equipo->area_id)
            ->orderBy('codigo_activo_cliente')
            ->get();

        // Obtener estados disponibles
        $estadosDisponibles = EstadoServicio::activos()->get();

        return view('incidencias.servicios.report-technician-v2', compact('servicio', 'equiposAdicionales', 'estadosDisponibles'));
    }

    /**
     * Guardar informe técnico
     */
    public function storeReport(Request $request, Servicio $servicio): RedirectResponse
    {
        $validated = $request->validate([
            // Fechas y tiempos
            'fecha_atencion' => 'required|date',
            'hora_inicio_atencion' => 'required|date_format:H:i',
            'hora_fin_atencion' => 'required|date_format:H:i',
            // Tipo de servicio
            'tipo_servicio_informe' => 'required|in:INSTALACION,MANTENIMIENTO_PREVENTIVO,MANTENIMIENTO_CORRECTIVO,SOPORTE',
            // Descripción de la solicitud
            'descripcion_solicitud' => 'required|string|min:10|max:5000',
            // Diagnóstico
            'diagnostico_validacion' => 'required|string|min:10|max:5000',
            // Pendientes
            'pendientes' => 'nullable|string|max:2000',
            // Observaciones
            'observaciones_informe' => 'nullable|string|max:2000',
            // Repuestos
            'repuestos_codigo' => 'nullable|array',
            'repuestos_descripcion' => 'nullable|array',
            'repuestos_marca' => 'nullable|array',
            'repuestos_modelo' => 'nullable|array',
            'repuestos_serie' => 'nullable|array',
            'repuestos_cantidad' => 'nullable|array',
            // Equipos atendidos
            'equipos_adicionales_atendidos' => 'nullable|array',
            'equipos_adicionales_atendidos.*' => 'exists:equipos,id',
            // Persona receptora
            'persona_receptora_nombre' => 'required|string|max:100',
            'persona_receptora_apellido' => 'required|string|max:100',
            'persona_receptora_documento' => 'required|string|max:50',
            // Firma y estado
            'firma_persona_receptora' => 'required|string',
            'estado_servicio_id' => 'required|exists:estado_servicios,id',
            // Imágenes y facturación
            'imagenes' => 'nullable|array|max:10',
            'imagenes.*' => 'image|max:5120',
            'puede_facturarse' => 'boolean',
            'es_soporte_contrato' => 'boolean',
        ], [
            'fecha_atencion.required' => 'Debe ingresar la fecha de atención',
            'hora_inicio_atencion.required' => 'Debe ingresar la hora de inicio',
            'hora_fin_atencion.required' => 'Debe ingresar la hora de fin',
            'tipo_servicio_informe.required' => 'Debe seleccionar el tipo de servicio',
            'descripcion_solicitud.required' => 'Debe describir la solicitud',
            'diagnostico_validacion.required' => 'Debe ingresar el diagnóstico/validación',
            'persona_receptora_nombre.required' => 'Debe ingresar el nombre del receptor',
            'firma_persona_receptora.required' => 'Debe capturar la firma',
            'estado_servicio_id.required' => 'Debe seleccionar un estado para el servicio',
        ]);

        // Validación adicional: hora de fin debe ser mayor que hora de inicio
        $horaInicio = \DateTime::createFromFormat('H:i', $validated['hora_inicio_atencion']);
        $horaFin = \DateTime::createFromFormat('H:i', $validated['hora_fin_atencion']);
        if ($horaFin <= $horaInicio) {
            return back()->withErrors(['hora_fin_atencion' => 'La hora de fin debe ser posterior a la hora de inicio'])
                ->withInput();
        }

        // Validar que la firma no esté vacía
        $firma = $validated['firma_persona_receptora'] ?? '';
        if (empty($firma) || strlen($firma) < 50 || $firma === 'data:image/png;base64,') {
            return back()
                ->withErrors(['firma_persona_receptora' => 'Debe dibujar la firma del receptor en el recuadro.'])
                ->withInput();
        }

        // Procesar firma base64
        $firmaData = $validated['firma_persona_receptora'];
        if (str_starts_with($firmaData, 'data:image')) {
            $firmaData = str_replace('data:image/png;base64,', '', $firmaData);
            $firmaPath = 'servicios/firmas/servicio_' . $servicio->id . '_' . time() . '.png';
            \Storage::disk('private')->put($firmaPath, base64_decode($firmaData));
            $validated['firma_persona_receptora'] = $firmaPath;
        }

        // Procesar imágenes
        $imagenesPath = [];
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('servicios/imagenes/servicio_' . $servicio->id, 'private');
                $imagenesPath[] = $path;
            }
        }
        $validated['imagenes_servicio'] = $imagenesPath;

        // Procesar repuestos
        $repuestos = [];
        if (!empty($validated['repuestos_codigo'])) {
            foreach ($validated['repuestos_codigo'] as $index => $codigo) {
                if (!empty($codigo) || !empty($validated['repuestos_descripcion'][$index] ?? '')) {
                    $repuestos[] = [
                        'codigo' => $codigo,
                        'descripcion' => $validated['repuestos_descripcion'][$index] ?? '',
                        'marca' => $validated['repuestos_marca'][$index] ?? '',
                        'modelo' => $validated['repuestos_modelo'][$index] ?? '',
                        'serie' => $validated['repuestos_serie'][$index] ?? '',
                        'cantidad' => $validated['repuestos_cantidad'][$index] ?? 1,
                    ];
                }
            }
        }

        // Actualizar servicio
        $servicio->update([
            // Fechas y tiempos
            'fecha_atencion' => $validated['fecha_atencion'],
            'hora_inicio_atencion' => $validated['hora_inicio_atencion'],
            'hora_fin_atencion' => $validated['hora_fin_atencion'],
            // Tipo de servicio
            'tipo_servicio_informe' => $validated['tipo_servicio_informe'],
            // Descripciones
            'descripcion_solicitud' => $validated['descripcion_solicitud'],
            'diagnostico_validacion' => $validated['diagnostico_validacion'],
            'pendientes' => $validated['pendientes'],
            'observaciones_informe' => $validated['observaciones_informe'],
            // Repuestos y equipos
            'repuestos_utilizados' => $repuestos,
            'equipos_adicionales_atendidos' => $validated['equipos_adicionales_atendidos'] ?? [],
            // Persona receptora
            'persona_receptora_nombre' => $validated['persona_receptora_nombre'],
            'persona_receptora_apellido' => $validated['persona_receptora_apellido'],
            'persona_receptora_documento' => $validated['persona_receptora_documento'],
            'firma_persona_receptora' => $validated['firma_persona_receptora'],
            // Estado y facturación
            'estado_servicio_id' => $validated['estado_servicio_id'],
            'puede_facturarse' => $validated['puede_facturarse'] ?? true,
            'es_soporte_contrato' => $validated['es_soporte_contrato'] ?? false,
            'imagenes_servicio' => $imagenesPath,
            'fecha_firma' => now(),
        ]);

        // Registrar seguimiento
        $estadoServicio = EstadoServicio::find($validated['estado_servicio_id']);
        SeguimientoServicio::create([
            'servicio_id' => $servicio->id,
            'user_id' => auth()->id(),
            'accion' => 'INFORME_TECNICO',
            'observacion' => "Informe técnico completado. Tipo: {$validated['tipo_servicio_informe']}. Estado: {$estadoServicio->nombre}",
            'estado_nuevo' => $estadoServicio->nombre,
            'metadata' => [
                'tipo_servicio' => $validated['tipo_servicio_informe'],
                'equipos_adicionales' => count($validated['equipos_adicionales_atendidos'] ?? []),
                'repuestos_instalados' => count($repuestos),
                'imagenes' => count($imagenesPath),
                'puede_facturarse' => $validated['puede_facturarse'] ?? true,
                'es_soporte_contrato' => $validated['es_soporte_contrato'] ?? false,
            ]
        ]);

        return redirect()->route('incidencias.servicios.show', $servicio)
            ->with('success', 'Informe técnico registrado exitosamente');
    }

    /**
     * Descargar informe técnico en PDF
     */
    public function downloadInformePDF(Servicio $servicio)
    {
        // Verificar que el servicio tenga informe registrado
        if (!$servicio->persona_receptora_nombre) {
            return redirect()->route('incidencias.servicios.show', $servicio)
                ->withErrors(['error' => 'Este servicio aún no tiene informe técnico registrado']);
        }

        // Cargar relaciones necesarias
        $servicio->load([
            'equipo.area.sede.cliente.ciudadNotificacion',
            'equipo.area.sede.municipio',
            'contrato.cliente',
            'tecnicoResponsable',
            'estadoServicio'
        ]);

        // Procesar imágenes a base64 para evitar problemas con GD
        $imagenesBase64 = [];
        if ($servicio->imagenes_servicio && is_array($servicio->imagenes_servicio)) {
            foreach ($servicio->imagenes_servicio as $imagen) {
                $ruta = storage_path('app/' . $imagen);
                if (file_exists($ruta)) {
                    $tipo = mime_content_type($ruta);
                    $datos = file_get_contents($ruta);
                    $imagenesBase64[] = 'data:' . $tipo . ';base64,' . base64_encode($datos);
                }
            }
        }

        // Procesar firma a base64
        $firmaBase64 = $this->resolverFirmaBase64((string) ($servicio->firma_persona_receptora ?? ''));

        // Equipos atendidos: principal + seleccionados durante el informe
        $equiposIdsAtendidos = collect($servicio->equipos_adicionales_atendidos ?? [])
            ->map(fn($id) => (int) $id)
            ->filter()
            ->push((int) $servicio->equipo_id)
            ->unique()
            ->values();

        $equiposAtendidos = Equipo::with(['marca', 'contrato', 'tipoEquipo'])
            ->whereIn('id', $equiposIdsAtendidos)
            ->orderBy('codigo_activo_cliente')
            ->get();

        // Resolver logo de la empresa (equipo o contrato) y convertirlo a base64 para DomPDF
        $empresaLogoPath = $this->resolverRutaLogoEmpresa($servicio);
        $empresaLogoBase64 = $this->convertirImagenABase64($empresaLogoPath);

        $pdf = Pdf::loadView('incidencias.servicios.pdf.informe-tecnico-new', [
            'servicio' => $servicio,
            'imagenesBase64' => $imagenesBase64,
            'firmaBase64' => $firmaBase64,
            'equiposAtendidos' => $equiposAtendidos,
            'empresaLogoPath' => $empresaLogoPath,
            'empresaLogoBase64' => $empresaLogoBase64,
        ]);

        // Configurar opciones de DomPDF
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'defaultFont' => 'Arial',
        ]);

        // Configurar opciones de papel y tamaño
        $pdf->setPaper('A4', 'portrait');

        // Descargar PDF
        return $pdf->download('Informe-Tecnico-Servicio-' . $servicio->id . '.pdf');
    }

    /**
     * Ver informe técnico en PDF en el navegador
     */
    public function viewInformePDF(Servicio $servicio)
    {
        // Verificar que el servicio tenga informe registrado
        if (!$servicio->persona_receptora_nombre) {
            return redirect()->route('incidencias.servicios.show', $servicio)
                ->withErrors(['error' => 'Este servicio aún no tiene informe técnico registrado']);
        }

        // Cargar relaciones necesarias
        $servicio->load([
            'equipo.area.sede.cliente.empresa',
            'equipo.area.sede.municipio.departamento',
            'equipo.area.sede.barrio',
            'contrato.cliente.empresa',
            'tecnicoResponsable',
            'estadoServicio'
        ]);

        // Procesar imágenes a base64 para evitar problemas con GD
        $imagenesBase64 = [];
        if ($servicio->imagenes_servicio && is_array($servicio->imagenes_servicio)) {
            foreach ($servicio->imagenes_servicio as $imagen) {
                $ruta = storage_path('app/' . $imagen);
                if (file_exists($ruta)) {
                    $tipo = mime_content_type($ruta);
                    $datos = file_get_contents($ruta);
                    $imagenesBase64[] = 'data:' . $tipo . ';base64,' . base64_encode($datos);
                }
            }
        }

        // Procesar firma a base64
        $firmaBase64 = $this->resolverFirmaBase64((string) ($servicio->firma_persona_receptora ?? ''));

        // Equipos atendidos: principal + seleccionados durante el informe
        $equiposIdsAtendidos = collect($servicio->equipos_adicionales_atendidos ?? [])
            ->map(fn($id) => (int) $id)
            ->filter()
            ->push((int) $servicio->equipo_id)
            ->unique()
            ->values();

        $equiposAtendidos = Equipo::with(['marca', 'contrato', 'tipoEquipo'])
            ->whereIn('id', $equiposIdsAtendidos)
            ->orderBy('codigo_activo_cliente')
            ->get();

        // Resolver logo de la empresa (equipo o contrato) y convertirlo a base64 para DomPDF
        $empresaLogoPath = $this->resolverRutaLogoEmpresa($servicio);
        $empresaLogoBase64 = $this->convertirImagenABase64($empresaLogoPath);

        $pdf = Pdf::loadView('incidencias.servicios.pdf.informe-tecnico-new', [
            'servicio' => $servicio,
            'imagenesBase64' => $imagenesBase64,
            'firmaBase64' => $firmaBase64,
            'equiposAtendidos' => $equiposAtendidos,
            'empresaLogoPath' => $empresaLogoPath,
            'empresaLogoBase64' => $empresaLogoBase64,
        ]);

        // Configurar opciones de DomPDF
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'defaultFont' => 'Arial',
        ]);

        // Configurar opciones de papel y tamaño
        $pdf->setPaper('A4', 'portrait');

        // Mostrar en el navegador
        return $pdf->stream('Informe-Tecnico-Servicio-' . $servicio->id . '.pdf');
    }

    /**
     * Panel para el técnico (alias de technicianPanel)
     */
    public function panel(Servicio $servicio): View
    {
        return $this->technicianPanel();
    }

    /**
     * Resuelve la ruta absoluta del logo de empresa asociado al servicio.
     */
    private function resolverRutaLogoEmpresa(Servicio $servicio): ?string
    {
        $logos = [
            $servicio->equipo?->area?->sede?->cliente?->empresa?->logo,
            $servicio->contrato?->cliente?->empresa?->logo,
        ];

        foreach ($logos as $logo) {
            if (!$logo || !is_string($logo)) {
                continue;
            }

            $logo = trim($logo);
            if ($logo === '') {
                continue;
            }

            $candidatePaths = [];

            // Ruta absoluta en sistema de archivos
            if (preg_match('/^[A-Za-z]:\\\\/', $logo) || str_starts_with($logo, '/') || str_starts_with($logo, '\\\\')) {
                $candidatePaths[] = $logo;
            }

            $normalizedLogo = ltrim(str_replace('\\\\', '/', $logo), '/');

            // Casos frecuentes: empresas/archivo.png o /empresas/archivo.png
            $candidatePaths[] = public_path($normalizedLogo);

            // Caso frecuente: storage/empresas/archivo.png
            if (str_starts_with($normalizedLogo, 'storage/')) {
                $candidatePaths[] = storage_path('app/public/' . substr($normalizedLogo, strlen('storage/')));
            }

            // Caso legado: public/empresas/archivo.png
            if (str_starts_with($normalizedLogo, 'public/')) {
                $candidatePaths[] = base_path($normalizedLogo);
            }

            foreach ($candidatePaths as $path) {
                if (is_string($path) && $path !== '' && file_exists($path) && is_file($path)) {
                    return $path;
                }
            }
        }

        return null;
    }

    /**
     * Convierte una imagen local a base64 para render estable en DomPDF.
     */
    private function convertirImagenABase64(?string $rutaImagen): ?string
    {
        if (!$rutaImagen || !file_exists($rutaImagen) || !is_file($rutaImagen)) {
            return null;
        }

        $mimeType = mime_content_type($rutaImagen) ?: 'image/png';
        $datos = file_get_contents($rutaImagen);

        if ($datos === false) {
            return null;
        }

        return 'data:' . $mimeType . ';base64,' . base64_encode($datos);
    }

    /**
     * Construye URL de WhatsApp para notificar al técnico una asignación.
     */
    private function construirUrlWhatsAppAsignacion(Servicio $servicio, User $tecnico): ?string
    {
        $telefono = $this->normalizarTelefonoWhatsApp($tecnico->telefono);
        if (!$telefono) {
            return null;
        }

        $cliente = $servicio->equipo?->area?->sede?->cliente?->razon_social
            ?? $servicio->contrato?->cliente?->razon_social
            ?? 'Cliente';

        $sede = $servicio->equipo?->area?->sede?->nombre
            ?? 'N/A';

        $direccion = $servicio->equipo?->area?->sede?->direccion
            ?? 'N/A';

        $telefonoContacto = $servicio->contacto_solicitante
            ?? $servicio->equipo?->area?->sede?->telefono
            ?? 'N/A';

        $nombreContacto = $servicio->solicitado_por
            ?? $servicio->equipo?->area?->sede?->cliente?->contacto_nombre
            ?? 'N/A';

        $equipo = $servicio->equipo?->codigo_interno
            ?? $servicio->equipo?->codigo_activo_cliente
            ?? ('Equipo #' . (string) $servicio->equipo_id);

        $fechaAsignacion = $servicio->fecha_asignacion?->format('d/m/Y H:i')
            ?? now()->format('d/m/Y H:i');

        $relativeServiceUrl = route('incidencias.servicios.show', $servicio, false);
        $basePublicUrl = rtrim((string) env('WHATSAPP_PUBLIC_URL', config('app.url', '')), '/');
        $urlServicio = $basePublicUrl !== ''
            ? ($basePublicUrl . $relativeServiceUrl)
            : route('incidencias.servicios.show', $servicio);

        $mensaje = "Hola {$tecnico->name}, se te asigno un nuevo servicio.\n"
            . "Servicio: #{$servicio->id}\n"
            . "Cliente: {$cliente}\n"
            . "Sede: {$sede}\n"
            . "Direccion: {$direccion}\n"
            . "Nombre contacto: {$nombreContacto}\n"
            . "Telefono contacto: {$telefonoContacto}\n"
            . "Equipo: {$equipo}\n"
            . "Fecha asignacion: {$fechaAsignacion}\n"
            . "Detalle del servicio:\n"
            . "{$urlServicio}";

        return 'https://wa.me/' . $telefono . '?text=' . rawurlencode($mensaje);
    }

    /**
     * Normaliza un numero para WhatsApp (solo digitos + prefijo de pais por defecto).
     */
    private function normalizarTelefonoWhatsApp(?string $telefono): ?string
    {
        $digits = preg_replace('/\\D+/', '', (string) $telefono);
        $digits = ltrim((string) $digits, '0');

        if ($digits === '') {
            return null;
        }

        $countryCode = preg_replace('/\\D+/', '', (string) env('WHATSAPP_DEFAULT_COUNTRY_CODE', '57'));

        if (
            $countryCode !== '' &&
            strlen($digits) === 10 &&
            !str_starts_with($digits, $countryCode)
        ) {
            $digits = $countryCode . $digits;
        }

        if (strlen($digits) < 10) {
            return null;
        }

        return $digits;
    }

    /**
     * Resuelve firma del receptor a base64 desde data URI, discos de storage o rutas legadas.
     */
    private function resolverFirmaBase64(string $firma): ?string
    {
        $firma = trim($firma);
        if ($firma === '') {
            return null;
        }

        // Si ya viene en data URI, usarla directamente.
        if (str_starts_with($firma, 'data:image')) {
            return $firma;
        }

        $normalized = ltrim(str_replace('\\', '/', $firma), '/');
        $pathVariants = [
            $normalized,
            preg_replace('#^(public|private|storage)/#', '', $normalized),
        ];

        foreach (array_filter(array_unique($pathVariants)) as $path) {
            foreach (['private', 'public'] as $disk) {
                try {
                    if (Storage::disk($disk)->exists($path)) {
                        $bytes = Storage::disk($disk)->get($path);
                        $mime = Storage::disk($disk)->mimeType($path) ?: 'image/png';
                        return 'data:' . $mime . ';base64,' . base64_encode($bytes);
                    }
                } catch (\Throwable $e) {
                    // Ignorar y continuar con otras rutas/discos
                }
            }
        }

        // Fallback para rutas físicas legadas
        $candidatePaths = [
            $firma,
            public_path($normalized),
            storage_path('app/' . $normalized),
            storage_path('app/public/' . $normalized),
            storage_path('app/private/' . $normalized),
        ];

        if (str_starts_with($normalized, 'storage/')) {
            $candidatePaths[] = storage_path('app/public/' . substr($normalized, strlen('storage/')));
        }

        foreach ($candidatePaths as $path) {
            $base64 = $this->convertirImagenABase64($path);
            if (!empty($base64)) {
                return $base64;
            }
        }

        return null;
    }

    /**
     * Mostrar estadísticas de servicios
     * 
     * GET /incidencias/servicios/estadisticas
     * 
     * Muestra:
     * - Total de servicios por mes
     * - Servicios por estado
     * - Servicios por técnico
     * - Tasa de resolución
     * - Tiempo promedio de resolución
     * 
     * @return View
     */
    public function estadisticas(): View
    {
        // Total de servicios
        $totalServicios = Servicio::count();
        
        // Servicios por estado
        $serviciosPorEstado = Servicio::selectRaw('estado_servicio_id, COUNT(*) as cantidad')
            ->groupBy('estado_servicio_id')
            ->get()
            ->load('estadoServicio');
        
        // Servicios por técnico (últimos 30 días)
        $serviciosPorTecnico = Servicio::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('tecnico_id, COUNT(*) as cantidad')
            ->groupBy('tecnico_id')
            ->get()
            ->load('tecnico');
        
        // Servicios por mes (últimos 12 meses)
        $serviciosPorMes = Servicio::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes")
            ->selectRaw('COUNT(*) as cantidad')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();
        
        // Servicios completados
        $serviciosCompletados = Servicio::whereNotNull('fecha_cierre')
            ->count();
        
        // Tasa de resolución
        $tasaResolucion = $totalServicios > 0 
            ? round(($serviciosCompletados / $totalServicios) * 100, 2)
            : 0;
        
        // Servicios por cliente (top 5)
        // Relación: Servicio → Equipo → Area → Sede → Cliente
        try {
            $serviciosPorCliente = Servicio::select('clientes.id', 'clientes.razon_social')
                ->selectRaw('COUNT(servicios.id) as cantidad')
                ->join('equipos', 'servicios.equipo_id', '=', 'equipos.id')
                ->join('areas', 'equipos.area_id', '=', 'areas.id')
                ->join('sedes', 'areas.sede_id', '=', 'sedes.id')
                ->join('clientes', 'sedes.cliente_id', '=', 'clientes.id')
                ->whereNull('servicios.deleted_at')
                ->groupBy('clientes.id', 'clientes.razon_social')
                ->orderByRaw('COUNT(servicios.id) DESC')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // Fallback si hay error
            $serviciosPorCliente = collect([]);
        }

        return view('incidencias.servicios.estadisticas', [
            'totalServicios' => $totalServicios,
            'serviciosPorEstado' => $serviciosPorEstado,
            'serviciosPorTecnico' => $serviciosPorTecnico,
            'serviciosPorMes' => $serviciosPorMes,
            'serviciosCompletados' => $serviciosCompletados,
            'tasaResolucion' => $tasaResolucion,
            'serviciosPorCliente' => $serviciosPorCliente,
        ]);
    }
}
