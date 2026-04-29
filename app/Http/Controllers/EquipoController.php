<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Area;
use App\Models\TipoEquipo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * EquipoController
 * 
 * Gestiona operaciones CRUD para equipos TI.
 * Incluye información técnica, ubicación, estado operativo y asignación de usuarios.
 */
class EquipoController extends Controller
{
    /**
     * Listar todos los equipos con paginación y filtros
     */
    public function index(Request $request): View
    {
        $query = Equipo::with(['area.sede.cliente.empresa', 'area.sede.empresa', 'tipoEquipo', 'servicios']);

        // Filtro por cliente
        if ($request->filled('cliente_id')) {
            $query->whereHas('area.sede', function ($q) {
                $q->where('cliente_id', request('cliente_id'));
            });
        }

        // Filtro por empresa
        if ($request->filled('empresa_id')) {
            $query->whereHas('area.sede', function ($q) {
                $q->where('empresa_id', request('empresa_id'));
            });
        }

        // Filtro por estado operativo
        if ($request->filled('estado_operativo')) {
            $query->where('estado_operativo', $request->estado_operativo);
        }

        // Filtro por tipo de equipo
        if ($request->filled('tipo_equipo_id')) {
            $query->where('tipo_equipo_id', $request->tipo_equipo_id);
        }

        $equipos = $query->get();

        // Obtener datos para filtros
        $clientes = \App\Models\Cliente::orderBy('razon_social')->get();
        $empresas = \App\Models\Empresa::orderBy('nombre')->get();
        $tipos = TipoEquipo::orderBy('nombre')->get();
        $estados = ['OPERATIVO', 'MANTENIMIENTO', 'REPARACION', 'BAJA', 'OBSOLETO'];

        return view('equipos.index', compact('equipos', 'clientes', 'empresas', 'tipos', 'estados'));
    }

    /**
     * Formulario para crear nuevo equipo
     */
    public function create(): View
    {
        $equipo = null;
        $areas = Area::with('sede.cliente.empresa')
            ->where('estado', true)
            ->orderBy('nombre')
            ->get();

        $tipos = TipoEquipo::orderBy('nombre')->get();
        
        $empresas = \App\Models\Empresa::where('estado', true)
            ->orderBy('nombre')
            ->get();
        
        $clientes = \App\Models\Cliente::where('estado', true)
            ->orderBy('razon_social')
            ->get();
        
        $sedes = \App\Models\Sede::with('cliente.empresa')
            ->where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('equipos.create', compact('equipo', 'areas', 'tipos', 'empresas', 'clientes', 'sedes'));
    }

    /**
     * Guardar nuevo equipo
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'tipo_equipo_id' => 'required|exists:tipos_equipos,id',
            'codigo_interno' => 'required|string|unique:equipos,codigo_interno',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'serial' => 'required|string|unique:equipos,serial',
            'fecha_compra' => 'nullable|date',
            'fecha_instalacion' => 'nullable|date',
            'fecha_garantia' => 'nullable|date',
            'valor_compra' => 'nullable|numeric|min:0',
            'estado_operativo' => 'required|in:OPERATIVO,MANTENIMIENTO,REPARACION,BAJA,OBSOLETO',
            'especificaciones_tecnicas' => 'nullable|string',
            'ip_asignada' => 'nullable|string|max:15',
            'mac_address' => 'nullable|string|max:17',
            'usuario_asignado' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        // Convertir especificaciones técnicas a JSON si existe
        if ($request->has('especificaciones_tecnicas') && !empty($request->especificaciones_tecnicas)) {
            $validated['especificaciones_tecnicas'] = json_decode($request->especificaciones_tecnicas, true);
        }

        Equipo::create($validated);

        return redirect()->route('equipos.index')
            ->with('success', 'Equipo registrado exitosamente');
    }

    /**
     * Mostrar detalles de un equipo
     */
    public function show(Equipo $equipo): View
    {
        $equipo->load([
            'area.sede.cliente.empresa',
            'tipoEquipo',
            'servicios.contrato'
        ]);

        return view('equipos.show', compact('equipo'));
    }

    /**
     * Formulario para editar equipo
     */
    public function edit(Equipo $equipo): View
    {
        $areas = Area::with('sede.cliente.empresa')
            ->where('estado', true)
            ->orderBy('nombre')
            ->get();

        $tipos = TipoEquipo::orderBy('nombre')->get();
        
        $empresas = \App\Models\Empresa::where('estado', true)
            ->orderBy('nombre')
            ->get();
        
        $clientes = \App\Models\Cliente::where('estado', true)
            ->orderBy('razon_social')
            ->get();
        
        $sedes = \App\Models\Sede::with('cliente.empresa')
            ->where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('equipos.create', compact('equipo', 'areas', 'tipos', 'empresas', 'clientes', 'sedes'));
    }

    /**
     * Actualizar equipo
     */
    public function update(Request $request, Equipo $equipo): RedirectResponse
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'tipo_equipo_id' => 'required|exists:tipos_equipos,id',
            'codigo_interno' => 'required|string|unique:equipos,codigo_interno,' . $equipo->id,
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'serial' => 'required|string|unique:equipos,serial,' . $equipo->id,
            'fecha_compra' => 'nullable|date',
            'fecha_instalacion' => 'nullable|date',
            'fecha_garantia' => 'nullable|date',
            'valor_compra' => 'nullable|numeric|min:0',
            'estado_operativo' => 'required|in:OPERATIVO,MANTENIMIENTO,REPARACION,BAJA,OBSOLETO',
            'especificaciones_tecnicas' => 'nullable|string',
            'ip_asignada' => 'nullable|string|max:15',
            'mac_address' => 'nullable|string|max:17',
            'usuario_asignado' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        // Convertir especificaciones técnicas a JSON si existe
        if ($request->has('especificaciones_tecnicas') && !empty($request->especificaciones_tecnicas)) {
            $validated['especificaciones_tecnicas'] = json_decode($request->especificaciones_tecnicas, true);
        }

        $equipo->update($validated);

        return redirect()->route('equipos.show', $equipo)
            ->with('success', 'Equipo actualizado exitosamente');
    }

    /**
     * Eliminar equipo (soft delete)
     */
    public function destroy(Equipo $equipo): RedirectResponse
    {
        $equipo->delete();

        return redirect()->route('equipos.index')
            ->with('success', 'Equipo eliminado exitosamente');
    }

    /**
     * Exportar equipos a Excel
     */
    public function exportarExcel(Request $request)
    {
        // Aplicar mismos filtros que en index
        $query = Equipo::with(['area.sede.cliente.empresa', 'area.sede.empresa', 'tipoEquipo']);

        if ($request->filled('cliente_id')) {
            $query->whereHas('area.sede', function ($q) {
                $q->where('cliente_id', request('cliente_id'));
            });
        }

        if ($request->filled('empresa_id')) {
            $query->whereHas('area.sede', function ($q) {
                $q->where('empresa_id', request('empresa_id'));
            });
        }

        if ($request->filled('estado_operativo')) {
            $query->where('estado_operativo', $request->estado_operativo);
        }

        if ($request->filled('tipo_equipo_id')) {
            $query->where('tipo_equipo_id', $request->tipo_equipo_id);
        }

        $equipos = $query->get();

        // Preparar datos para exportar
        $data = [];
        $data[] = ['CÓDIGO', 'TIPO', 'MARCA', 'MODELO', 'SERIAL', 'UBICACIÓN', 'EMPRESA/CLIENTE', 'ESTADO', 'FECHA COMPRA', 'USUARIO ASIGNADO'];

        foreach ($equipos as $equipo) {
            $propietario = $equipo->area?->sede?->cliente?->razon_social ?? 
                          $equipo->area?->sede?->empresa?->nombre ?? 'N/A';
            
            $data[] = [
                $equipo->codigo_interno,
                $equipo->tipoEquipo?->nombre ?? 'N/A',
                $equipo->marca,
                $equipo->modelo,
                $equipo->serial,
                ($equipo->area?->nombre ?? 'N/A') . ' - ' . ($equipo->area?->sede?->nombre ?? 'N/A'),
                $propietario,
                $equipo->estado_operativo,
                $equipo->fecha_compra?->format('d/m/Y') ?? 'N/A',
                $equipo->usuario_asignado ?? 'N/A'
            ];
        }

        // Generar respuesta CSV que Excel puede abrir
        $fileName = 'equipos_' . date('Y-m-d_His') . '.csv';
        
        $response = response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            
            // Encoding UTF-8 para Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            foreach ($data as $row) {
                fputcsv($handle, $row, ',', '"');
            }
            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=' . $fileName,
        ]);

        return $response;
    }

    /**
     * Exportar equipos a PDF
     */
    public function exportarPDF(Request $request)
    {
        // Aplicar mismos filtros que en index
        $query = Equipo::with(['area.sede.cliente.empresa', 'area.sede.empresa', 'tipoEquipo']);

        if ($request->filled('cliente_id')) {
            $query->whereHas('area.sede', function ($q) {
                $q->where('cliente_id', request('cliente_id'));
            });
        }

        if ($request->filled('empresa_id')) {
            $query->whereHas('area.sede', function ($q) {
                $q->where('empresa_id', request('empresa_id'));
            });
        }

        if ($request->filled('estado_operativo')) {
            $query->where('estado_operativo', $request->estado_operativo);
        }

        if ($request->filled('tipo_equipo_id')) {
            $query->where('tipo_equipo_id', $request->tipo_equipo_id);
        }

        $equipos = $query->get();

        // Retornar vista con datos para PDF
        return view('equipos.export-pdf', compact('equipos'));
    }
}
