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
     * Listar todas las áreas con paginación
     */
    public function index(): View
    {
        $areas = Area::with(['sede.cliente.empresa', 'equipos'])
            ->get();

        return view('areas.index', compact('areas'));
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

        return view('areas.create', compact('area', 'sedes'));
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

        return view('areas.edit', compact('area', 'sedes'));
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
