<?php

namespace App\Http\Controllers\Parametros;

use App\Models\Equipo;
use App\Models\Area;
use App\Models\TipoEquipo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

/**
 * EquipoController - Módulo Parámetros
 * Gestión de equipos TI
 * Ruta: /parametros/equipos
 */
class EquipoController extends Controller
{
    public function index(Request $request): View
    {
        $query = Equipo::with(['area.sede.cliente.empresa', 'area.sede.empresa', 'tipoEquipo', 'servicios']);

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

        return view('parametros.equipos.create', compact('equipo', 'areas', 'tipos', 'empresas', 'clientes', 'sedes', 'contratos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'tipo_equipo_id' => 'required|exists:tipos_equipos,id',
            'contrato_id' => 'nullable|exists:contratos,id',
            'codigo_interno' => 'required|string|unique:equipos,codigo_interno',
            'serial' => 'nullable|string|unique:equipos,serial',
            'marca' => 'required|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
            'especificaciones_tecnicas' => 'nullable|json',
            'estado_operativo' => 'required|in:OPERATIVO,MANTENIMIENTO,REPARACION,BAJA,OBSOLETO',
            'fecha_compra' => 'nullable|date',
            'fecha_instalacion' => 'nullable|date',
            'fecha_garantia' => 'nullable|date',
            'valor_compra' => 'nullable|numeric|min:0',
            'ip_asignada' => 'nullable|string|max:45',
            'mac_address' => 'nullable|string|max:17',
            'usuario_asignado' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'mantenimientos_por_ano' => 'nullable|integer|min:0|max:12',
            'calibraciones_por_ano' => 'nullable|integer|min:0|max:12',
        ]);

        Equipo::create($validated);

        return redirect()->route('parametros.equipos.index')
            ->with('success', 'Equipo creado exitosamente');
    }

    public function show(Equipo $equipo): View
    {
        $equipo->load('area.sede.cliente.empresa', 'tipoEquipo', 'servicios', 'contrato.cliente');

        return view('parametros.equipos.show', compact('equipo'));
    }

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

        // Cargar todos los contratos activos, agrupados por cliente
        $contratos = \App\Models\Contrato::where('estado', 'ACTIVO')
            ->orderBy('cliente_id')
            ->orderBy('numero_contrato')
            ->get();

        return view('parametros.equipos.edit', compact('equipo', 'areas', 'tipos', 'empresas', 'clientes', 'sedes', 'contratos'));
    }

    public function update(Request $request, Equipo $equipo): RedirectResponse
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'tipo_equipo_id' => 'required|exists:tipos_equipos,id',
            'contrato_id' => 'nullable|exists:contratos,id',
            'codigo_interno' => 'required|string|unique:equipos,codigo_interno,' . $equipo->id,
            'serial' => 'nullable|string|unique:equipos,serial,' . $equipo->id,
            'marca' => 'required|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
            'especificaciones_tecnicas' => 'nullable|json',
            'estado_operativo' => 'required|in:OPERATIVO,MANTENIMIENTO,REPARACION,BAJA,OBSOLETO',
            'fecha_compra' => 'nullable|date',
            'fecha_instalacion' => 'nullable|date',
            'fecha_garantia' => 'nullable|date',
            'valor_compra' => 'nullable|numeric|min:0',
            'ip_asignada' => 'nullable|string|max:45',
            'mac_address' => 'nullable|string|max:17',
            'usuario_asignado' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'mantenimientos_por_ano' => 'nullable|integer|min:0|max:12',
            'calibraciones_por_ano' => 'nullable|integer|min:0|max:12',
        ]);

        $equipo->update($validated);

        return redirect()->route('parametros.equipos.index')
            ->with('success', 'Equipo actualizado exitosamente');
    }

    public function destroy(Equipo $equipo): RedirectResponse
    {
        $equipo->delete();

        return redirect()->route('parametros.equipos.index')
            ->with('success', 'Equipo eliminado exitosamente');
    }

    /**
     * Exportar equipos a Excel
     */
    public function exportarExcel()
    {
        // Obtener todos los equipos con relaciones
        $equipos = Equipo::with(['area.sede.cliente', 'area.sede.empresa', 'tipoEquipo'])
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
                    $equipo->codigo_interno,
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
        // Obtener todos los equipos con relaciones
        $equipos = Equipo::with(['area.sede.cliente', 'area.sede.empresa', 'tipoEquipo'])
            ->orderBy('codigo_interno')
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

