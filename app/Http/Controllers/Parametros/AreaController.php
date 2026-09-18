<?php

namespace App\Http\Controllers\Parametros;

use App\Models\Area;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Traits\PermissionCheckTrait;

/**
 * AreaController - Módulo Parámetros
 * Gestión de áreas dentro de sedes
 * Ruta: /parametros/areas
 */
class AreaController extends Controller
{
    use PermissionCheckTrait;

    public function index(Request $request): View
    {
        $this->checkPermission('areas.ver');

        $empresaId = $request->query('empresa_id');
        $clienteId = $request->query('cliente_id');

        $query = Area::with(['sede.cliente.empresa', 'equipos']);

        if ($empresaId) {
            $query->whereHas('sede', function ($q) use ($empresaId) {
                $q->where('empresa_id', $empresaId)
                  ->whereNull('cliente_id');
            });
        } elseif ($clienteId) {
            $query->whereHas('sede', function ($q) use ($clienteId) {
                $q->where('cliente_id', $clienteId)
                  ->whereNull('empresa_id');
            });
        }

        $areas = $query->orderBy('nombre')->get();

        $empresas = \App\Models\Empresa::where('estado', true)
            ->orderBy('nombre')
            ->get();
        
        $clientes = \App\Models\Cliente::where('estado', true)
            ->orderBy('razon_social')
            ->get();

        return view('parametros.areas.index', compact('areas', 'empresas', 'clientes', 'empresaId', 'clienteId'));
    }

    public function create(): View
    {
        $this->checkPermission('areas.crear');

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

        return view('parametros.areas.create', compact('area', 'sedes', 'empresas', 'clientes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkPermission('areas.crear');

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

        return redirect()->route('parametros.areas.index')
            ->with('success', 'Área creada exitosamente');
    }

    public function show(Area $area): View
    {
        $this->checkPermission('areas.ver');

        $area->load('sede.cliente.empresa', 'equipos');

        return view('parametros.areas.show', compact('area'));
    }

    public function edit(Area $area): View
    {
        $this->checkPermission('areas.editar');

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

        return view('parametros.areas.edit', compact('area', 'sedes', 'empresas', 'clientes'));
    }

    public function update(Request $request, Area $area): RedirectResponse
    {
        $this->checkPermission('areas.editar');

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

        return redirect()->route('parametros.areas.index')
            ->with('success', 'Área actualizada exitosamente');
    }

    public function destroy(Area $area): RedirectResponse
    {
        $this->checkPermission('areas.eliminar');

        $area->delete();

        return redirect()->route('parametros.areas.index')
            ->with('success', 'Área eliminada exitosamente');
    }
}
