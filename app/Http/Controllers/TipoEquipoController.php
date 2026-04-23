<?php

namespace App\Http\Controllers;

use App\Models\TipoEquipo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * TipoEquipoController
 * 
 * Gestiona el catálogo de tipos de equipos disponibles en el sistema.
 * Permite categorizar y clasificar equipos por tipo.
 */
class TipoEquipoController extends Controller
{
    /**
     * Listar todos los tipos de equipos
     */
    public function index(): View
    {
        $tipos = TipoEquipo::withCount('equipos')
            ->orderBy('categoria')
            ->get();

        return view('tipos-equipos.index', compact('tipos'));
    }

    /**
     * Formulario para crear nuevo tipo de equipo
     */
    public function create(): View
    {
        $tipoEquipo = null;
        return view('tipos-equipos.create', compact('tipoEquipo'));
    }

    /**
     * Guardar nuevo tipo de equipo
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:tipos_equipos,nombre',
            'descripcion' => 'nullable|string|max:500',
            'categoria' => 'required|in:HARDWARE,SOFTWARE,RED,PERIFERICO,OTRO',
            'icono' => 'nullable|string|max:50',
        ]);

        TipoEquipo::create($validated);

        return redirect()->route('tipos-equipos.index')
            ->with('success', 'Tipo de equipo creado exitosamente');
    }

    /**
     * Mostrar detalles de un tipo de equipo
     */
    public function show(TipoEquipo $tipoEquipo): View
    {
        $tipoEquipo->load('equipos');

        return view('tipos-equipos.show', compact('tipoEquipo'));
    }

    /**
     * Formulario para editar tipo de equipo
     */
    public function edit(TipoEquipo $tipoEquipo): View
    {
        return view('tipos-equipos.edit', compact('tipoEquipo'));
    }

    /**
     * Actualizar tipo de equipo
     */
    public function update(Request $request, TipoEquipo $tipoEquipo): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:tipos_equipos,nombre,' . $tipoEquipo->id,
            'descripcion' => 'nullable|string|max:500',
            'categoria' => 'required|in:HARDWARE,SOFTWARE,RED,PERIFERICO,OTRO',
            'icono' => 'nullable|string|max:50',
        ]);

        $tipoEquipo->update($validated);

        return redirect()->route('tipos-equipos.show', $tipoEquipo)
            ->with('success', 'Tipo de equipo actualizado exitosamente');
    }

    /**
     * Eliminar tipo de equipo
     */
    public function destroy(TipoEquipo $tipoEquipo): RedirectResponse
    {
        // Verificar si hay equipos asociados
        if ($tipoEquipo->equipos()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un tipo con equipos asociados');
        }

        $tipoEquipo->delete();

        return redirect()->route('tipos-equipos.index')
            ->with('success', 'Tipo de equipo eliminado exitosamente');
    }
}
