<?php

namespace App\Http\Controllers\Parametros;

use App\Models\Equipo;
use App\Models\Area;
use App\Models\TipoEquipo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Traits\PermissionCheckTrait;

/**
 * EquipoController - Módulo Parámetros
 * Gestión de equipos TI
 * Ruta: /parametros/equipos
 */
class EquipoController extends Controller
{
    use PermissionCheckTrait;

    public function index(Request $request): View
    {
        $this->checkPermission('equipos.ver');
        $query = Equipo::with(['area.sede.cliente.empresa', 'area.sede.empresa', 'tipoEquipo', 'servicios', 'marca']);

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

        $clientes = \App\Models\Cliente::orderBy('razon_social')->get();
        $empresas = \App\Models\Empresa::orderBy('nombre')->get();
        $tipos = TipoEquipo::orderBy('nombre')->get();
        $estados = ['OPERATIVO', 'MANTENIMIENTO', 'REPARACION', 'BAJA', 'OBSOLETO'];

        return view('parametros.equipos.index', compact('equipos', 'clientes', 'empresas', 'tipos', 'estados'));
    }

    public function create(): View
    {
        $this->checkPermission('equipos.crear');
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

        // Cargar todos los contratos activos, agrupados por cliente
        $contratos = \App\Models\Contrato::where('estado', 'ACTIVO')
            ->orderBy('cliente_id')
            ->orderBy('numero_contrato')
            ->get();

        $marcas = \App\Models\Marca::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('parametros.equipos.create', compact('equipo', 'areas', 'tipos', 'empresas', 'clientes', 'sedes', 'contratos', 'marcas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkPermission('equipos.crear');

        // Pre-procesar especificaciones_tecnicas: si no es JSON válido, nullificar
        if ($request->filled('especificaciones_tecnicas')) {
            $decoded = json_decode($request->especificaciones_tecnicas, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $request->merge(['especificaciones_tecnicas' => null]);
            } else {
                $request->merge(['especificaciones_tecnicas' => $decoded]);
            }
        }

        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'tipo_equipo_id' => 'required|exists:tipos_equipos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'sede_id' => 'nullable|exists:sedes,id',
            'contrato_id' => 'nullable|exists:contratos,id',
            'serial' => 'nullable|string|max:100|unique:equipos,serial',
            'marca_id' => 'required|exists:marcas,id',
            'modelo' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
            'especificaciones_tecnicas' => 'nullable|array',
            'estado_operativo' => 'required|in:OPERATIVO,MANTENIMIENTO,REPARACION,BAJA,OBSOLETO',
            'fecha_compra' => 'nullable|date',
            'fecha_instalacion' => 'nullable|date',
            'fecha_garantia' => 'nullable|date',
            'valor_compra' => 'nullable|numeric|min:0',
            'ip_asignada' => 'nullable|string|max:45',
            'mac_address' => 'nullable|string|max:17',
            'usuario_asignado' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'mantenimientos_anuales' => 'nullable|integer|min:0|max:12',
            'calibraciones_anuales' => 'nullable|integer|min:0|max:12',
            'fecha_ultimo_mantenimiento' => 'nullable|date',
            'fecha_ultima_calibracion' => 'nullable|date',
            'proxima_fecha_mantenimiento' => 'nullable|date',
            'proxima_fecha_calibracion' => 'nullable|date',
        ]);

        // Autogenerar código de activo basado en el prefijo del cliente
        $validated['codigo_activo_cliente'] = Equipo::generarCodigoActivoCliente($validated['cliente_id']);

        Equipo::create($validated);

        return redirect()->route('parametros.equipos.index')
            ->with('success', 'Equipo creado exitosamente con código: ' . $validated['codigo_activo_cliente']);
    }

    public function show(Equipo $equipo): View
    {
        $this->checkPermission('equipos.ver');
        $equipo->load('area.sede.cliente.empresa', 'tipoEquipo', 'servicios', 'contrato.cliente', 'marca');

        return view('parametros.equipos.show', compact('equipo'));
    }

    public function edit(Equipo $equipo): View
    {
        $this->checkPermission('equipos.editar');
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

        // Cargar todos los contratos activos, agrupados por cliente
        $contratos = \App\Models\Contrato::where('estado', 'ACTIVO')
            ->orderBy('cliente_id')
            ->orderBy('numero_contrato')
            ->get();

        $marcas = \App\Models\Marca::where('estado', true)
            ->orderBy('nombre')
            ->get();

        return view('parametros.equipos.edit', compact('equipo', 'areas', 'tipos', 'empresas', 'clientes', 'sedes', 'contratos', 'marcas'));
    }

    public function update(Request $request, Equipo $equipo): RedirectResponse
    {
        $this->checkPermission('equipos.editar');

        // Pre-procesar especificaciones_tecnicas: si no es JSON válido, nullificar
        if ($request->filled('especificaciones_tecnicas')) {
            $decoded = json_decode($request->especificaciones_tecnicas, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $request->merge(['especificaciones_tecnicas' => null]);
            } else {
                $request->merge(['especificaciones_tecnicas' => $decoded]);
            }
        }

        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'tipo_equipo_id' => 'required|exists:tipos_equipos,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'sede_id' => 'nullable|exists:sedes,id',
            'contrato_id' => 'nullable|exists:contratos,id',
            'codigo_activo_cliente' => 'required|string|max:50|unique:equipos,codigo_activo_cliente,' . $equipo->id,
            'serial' => 'nullable|string|max:100|unique:equipos,serial,' . $equipo->id,
            'marca_id' => 'required|exists:marcas,id',
            'modelo' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
            'especificaciones_tecnicas' => 'nullable|array',
            'estado_operativo' => 'required|in:OPERATIVO,MANTENIMIENTO,REPARACION,BAJA,OBSOLETO',
            'fecha_compra' => 'nullable|date',
            'fecha_instalacion' => 'nullable|date',
            'fecha_garantia' => 'nullable|date',
            'valor_compra' => 'nullable|numeric|min:0',
            'ip_asignada' => 'nullable|string|max:45',
            'mac_address' => 'nullable|string|max:17',
            'usuario_asignado' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'mantenimientos_anuales' => 'nullable|integer|min:0|max:12',
            'calibraciones_anuales' => 'nullable|integer|min:0|max:12',
            'fecha_ultimo_mantenimiento' => 'nullable|date',
            'fecha_ultima_calibracion' => 'nullable|date',
            'proxima_fecha_mantenimiento' => 'nullable|date',
            'proxima_fecha_calibracion' => 'nullable|date',
        ]);

        $equipo->update($validated);

        return redirect()->route('parametros.equipos.index')
            ->with('success', 'Equipo actualizado exitosamente');
    }

    public function destroy(Equipo $equipo): RedirectResponse
    {
        $this->checkPermission('equipos.eliminar');
        $equipo->delete();

        return redirect()->route('parametros.equipos.index')
            ->with('success', 'Equipo eliminado exitosamente');
    }

    /**
     * Exportar equipos a Excel
     */
    public function exportarExcel()
    {
        $this->checkPermission('equipos.exportar');
        // Obtener todos los equipos con relaciones
        $equipos = Equipo::with(['area.sede.cliente', 'area.sede.empresa', 'tipoEquipo', 'marca'])
            ->get();

        // Para ahora, devolver un archivo CSV simple
        $filename = 'equipos_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function() use ($equipos) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Código', 'Marca', 'Modelo', 'Serie', 'Estado', 'Tipo Equipo', 'Área', 'Sede', 'Cliente/Empresa']);
            
            foreach ($equipos as $equipo) {
                fputcsv($file, [
                    $equipo->id,
                    $equipo->codigo_activo_cliente,
                    $equipo->marca,
                    $equipo->modelo,
                    $equipo->serie,
                    $equipo->estado_operativo,
                    $equipo->tipoEquipo->nombre ?? 'N/A',
                    $equipo->area->nombre ?? 'N/A',
                    $equipo->area->sede->nombre ?? 'N/A',
                    $equipo->area->sede->cliente->razon_social ?? $equipo->area->sede->empresa->nombre ?? 'N/A',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar equipos a PDF
     * 
     * GET /parametros/equipos/exportar/pdf
     * 
     * Genera un archivo PDF con el listado completo de equipos
     * incluyendo información de marca, modelo, serie, tipo y ubicación
     * 
     * Usa librería barryvdh/laravel-dompdf para generación de PDF
     * 
     * @return \Illuminate\Http\Response PDF descargable
     */
    public function exportarPdf()
    {
        $this->checkPermission('equipos.exportar');
        // Obtener todos los equipos con relaciones
        $equipos = Equipo::with(['area.sede.cliente', 'area.sede.empresa', 'tipoEquipo', 'marca'])
            ->orderBy('codigo_activo_cliente')
            ->get();

        // Preparar datos para el PDF
        $data = [
            'titulo' => 'Listado de Equipos',
            'fecha_reporte' => now()->format('d/m/Y H:i:s'),
            'total_equipos' => $equipos->count(),
            'equipos' => $equipos,
        ];

        // Generar PDF usando DomPDF
        $pdf = \PDF::loadView('parametros.equipos.pdf', $data);
        
        // Configurar opciones de papel
        $pdf->setPaper('A4', 'landscape');
        
        // Descargar el PDF
        $filename = 'equipos_' . date('Y-m-d_His') . '.pdf';
        return $pdf->download($filename);
    }
}

