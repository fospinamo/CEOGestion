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
        // Validaciones básicas
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'sede_id' => 'required|exists:sedes,id',
            'area_id' => 'required|exists:areas,id',
            'equipo_id' => 'required|exists:equipos,id',
            'tipo_servicio' => 'required|in:PREVENTIVO,CORRECTIVO,INSTALACION,CONFIGURACION,CAPACITACION,CONSULTA',
            'prioridad' => 'required|in:BAJA,MEDIA,ALTA,CRITICA',
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
     * Formulario para editar servicio
     */
    public function edit(Servicio $servicio): View
    {
        try {
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
        $equipos = Equipo::where('cliente_id', $cliente_id)
            ->where('estado_operativo', 'OPERATIVO')
            ->orderBy('codigo_interno')
            ->get(['id', 'codigo_interno', 'marca', 'modelo', 'serial']);
        
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
        $equipos = Equipo::where('area_id', $area_id)
            ->whereNotIn('estado_operativo', ['BAJA', 'OBSOLETO'])
            ->orderBy('codigo_interno')
            ->get(['id', 'codigo_interno', 'marca', 'modelo', 'serial', 'estado_operativo']);
        
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
            'codigo_interno' => 'required|string|max:50|unique:equipos',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'serial' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $equipo = Equipo::create([
                'area_id' => $validated['area_id'],
                'codigo_interno' => $validated['codigo_interno'],
                'marca' => $validated['marca'],
                'modelo' => $validated['modelo'],
                'serial' => $validated['serial'] ?? null,
                'descripcion' => $validated['descripcion'] ?? null,
                'estado_operativo' => 'OPERATIVO',  // Nuevo equipo comienza operativo
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Equipo creado exitosamente',
                'equipo' => [
                    'id' => $equipo->id,
                    'codigo_interno' => $equipo->codigo_interno,
                    'marca' => $equipo->marca,
                    'modelo' => $equipo->modelo,
                    'serial' => $equipo->serial,
                    'estado_operativo' => $equipo->estado_operativo,
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
        $validated = $request->validate([
            'descripcion_atencion' => 'required|string|min:20|max:5000',
            'persona_receptora_completa' => 'required|string|max:200',
            'persona_receptora_nombre' => 'nullable|string|max:100',
            'persona_receptora_apellido' => 'nullable|string|max:100',
            'persona_receptora_documento' => 'required|string|max:50',
            'firma_persona_receptora' => 'required|string',
            'equipos_adicionales' => 'nullable|array',
            'equipos_adicionales.*' => 'exists:equipos,id',
        ], [
            'descripcion_atencion.required' => 'Debe describir lo realizado',
            'descripcion_atencion.min' => 'La descripción debe tener al menos 20 caracteres',
            'persona_receptora_completa.required' => 'Debe ingresar nombre y apellido',
            'persona_receptora_documento.required' => 'Debe ingresar el documento del receptor',
            'firma_persona_receptora.required' => 'Debe capturar la firma',
        ]);

        // Procesar nombre y apellido desde persona_receptora_completa
        $partes = explode(' ', trim($validated['persona_receptora_completa']), 2);
        $validated['persona_receptora_nombre'] = $partes[0];
        $validated['persona_receptora_apellido'] = $partes[1] ?? '';

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

        // Actualizar servicio con datos de atención
        $servicio->update([
            'persona_receptora_nombre' => $validated['persona_receptora_nombre'],
            'persona_receptora_apellido' => $validated['persona_receptora_apellido'],
            'persona_receptora_documento' => $validated['persona_receptora_documento'],
            'firma_persona_receptora' => $validated['firma_persona_receptora'],
            'descripcion_atencion' => $validated['descripcion_atencion'],
            'equipos_adicionales_atendidos' => $validated['equipos_adicionales'] ?? [],
            'fecha_firma' => now(),
            'estado' => 'CERRADO',
            'fecha_cierre_real' => now(),
        ]);

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
            'fecha_asignacion' => 'required|date',
        ], [
            'tecnico_id.required' => 'Debe seleccionar un técnico',
            'fecha_asignacion.required' => 'Debe ingresar la fecha de asignación',
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
            'fecha_asignacion' => $validated['fecha_asignacion'],
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

        return redirect()->route('incidencias.servicios.show', $servicio)
            ->with('success', "Servicio asignado a {$tecnico->name} exitosamente");
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
            'equipo.area.sede.cliente',
            'contrato.cliente',
            'tecnicoResponsable',
            'estadoServicio'
        ]);

        // Obtener equipos adicionales disponibles en la misma área
        $equiposAdicionales = Equipo::where('area_id', $servicio->equipo->area_id)
            ->where('id', '!=', $servicio->equipo_id)
            ->where('estado_operativo', 'OPERATIVO')
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
            'equipo.area.sede.cliente',
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
        $firmaBase64 = null;
        if ($servicio->firma_persona_receptora) {
            $rutaFirma = storage_path('app/' . $servicio->firma_persona_receptora);
            if (file_exists($rutaFirma)) {
                $tipo = mime_content_type($rutaFirma);
                $datos = file_get_contents($rutaFirma);
                $firmaBase64 = 'data:' . $tipo . ';base64,' . base64_encode($datos);
            }
        }

        // Generar PDF con datos de imágenes procesados
        $pdf = Pdf::loadView('incidencias.servicios.pdf.informe-tecnico-new', [
            'servicio' => $servicio,
            'imagenesBase64' => $imagenesBase64,
            'firmaBase64' => $firmaBase64
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
            'equipo.area.sede.cliente',
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
        $firmaBase64 = null;
        if ($servicio->firma_persona_receptora) {
            $rutaFirma = storage_path('app/' . $servicio->firma_persona_receptora);
            if (file_exists($rutaFirma)) {
                $tipo = mime_content_type($rutaFirma);
                $datos = file_get_contents($rutaFirma);
                $firmaBase64 = 'data:' . $tipo . ';base64,' . base64_encode($datos);
            }
        }

        // Generar PDF con datos de imágenes procesados
        $pdf = Pdf::loadView('incidencias.servicios.pdf.informe-tecnico-new', [
            'servicio' => $servicio,
            'imagenesBase64' => $imagenesBase64,
            'firmaBase64' => $firmaBase64
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
