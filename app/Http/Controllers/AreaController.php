<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * AreaController
 * 
 * Gestiona operaciones CRUD para áreas dentro de las sedes.
 * Las áreas son departamentos o secciones de una sede que tienen equipos TI.
 */
class AreaController extends Controller
{
    /**
     * Listar todas las áreas con filtro por empresa o cliente
     * 
     * Filtros disponibles:
     * - empresa_id: Mostrar áreas de sedes de una empresa
     * - cliente_id: Mostrar áreas de sedes de un cliente
     */
    public function index(Request $request): View
    {
        // Obtener parámetros de filtro
        $empresaId = $request->query('empresa_id');
        $clienteId = $request->query('cliente_id');

        // Construir query con filtros
        $query = Area::with(['sede.cliente.empresa', 'equipos']);

        if ($empresaId) {
            // Filtrar por sedes de empresa
            $query->whereHas('sede', function ($q) use ($empresaId) {
                $q->where('empresa_id', $empresaId)
                  ->whereNull('cliente_id');
            });
        } elseif ($clienteId) {
            // Filtrar por sedes de cliente
            $query->whereHas('sede', function ($q) use ($clienteId) {
                $q->where('cliente_id', $clienteId)
                  ->whereNull('empresa_id');
            });
        }

        $areas = $query->orderBy('nombre')->get();

        // Obtener empresas y clientes para los filtros
        $empresas = \App\Models\Empresa::where('estado', true)
            ->orderBy('nombre')
            ->get();
        
        $clientes = \App\Models\Cliente::where('estado', true)
            ->orderBy('razon_social')
            ->get();

        return view('areas.index', compact('areas', 'empresas', 'clientes', 'empresaId', 'clienteId'));
    }

    /**
     * Formulario para crear nueva área
     */
    public function create(): View
    {
        $area = null;
        $sedes = Sede::with('cliente.empresa')
            ->where('estado', true)
            ->orderBy('nombre')
            ->get();

        $empresas = \App\Models\Empresa::where('estado', true)
            ->orderBy('nombre')
            ->get();
        
        $clientes = \App\Models\Cliente::where('estado', true)
            ->orderBy('razon_social')
            ->get();

        return view('areas.create', compact('area', 'sedes', 'empresas', 'clientes'));
    }

    /**
     * Guardar nueva área
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sede_id' => 'required|exists:sedes,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'responsable_nombre' => 'nullable|string|max:255',
            'responsable_contacto' => 'nullable|string|max:20',
            'nivel_riesgo' => 'required|in:BAJO,MEDIO,ALTO,CRITICO',
            'estado' => 'boolean',
        ]);

        Area::create($validated);

        return redirect()->route('areas.index')
            ->with('success', 'Área creada exitosamente');
    }

    /**
     * Mostrar detalles de un área
     */
    public function show(Area $area): View
    {
        $area->load([
            'sede.cliente.empresa',
            'sede.municipio.departamento',
            'equipos.tipoEquipo'
        ]);

        return view('areas.show', compact('area'));
    }

    /**
     * Formulario para editar área
     */
    public function edit(Area $area): View
    {
        $sedes = Sede::with('cliente.empresa')
            ->where('estado', true)
            ->orderBy('nombre')
            ->get();

        $empresas = \App\Models\Empresa::where('estado', true)
            ->orderBy('nombre')
            ->get();
        
        $clientes = \App\Models\Cliente::where('estado', true)
            ->orderBy('razon_social')
            ->get();

        return view('areas.edit', compact('area', 'sedes', 'empresas', 'clientes'));
    }

    /**
     * Actualizar área
     */
    public function update(Request $request, Area $area): RedirectResponse
    {
        $validated = $request->validate([
            'sede_id' => 'required|exists:sedes,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'responsable_nombre' => 'nullable|string|max:255',
            'responsable_contacto' => 'nullable|string|max:20',
            'nivel_riesgo' => 'required|in:BAJO,MEDIO,ALTO,CRITICO',
            'estado' => 'boolean',
        ]);

        $area->update($validated);

        return redirect()->route('areas.show', $area)
            ->with('success', 'Área actualizada exitosamente');
    }

    /**
     * Eliminar área
     */
    public function destroy(Area $area): RedirectResponse
    {
        // Verificar si hay equipos asociados
        if ($area->equipos()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un área con equipos asociados');
        }

        $area->delete();

        return redirect()->route('areas.index')
            ->with('success', 'Área eliminada exitosamente');
    }
}
