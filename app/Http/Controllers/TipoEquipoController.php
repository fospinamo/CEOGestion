<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\TipoEquipo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * TipoEquipoController
 * 
 * Gestiona el catálogo de tipos de equipos disponibles en el sistema.
 * Permite categorizar y clasificar equipos por tipo usando categorías parametrizables.
 */
class TipoEquipoController extends Controller
{
    /**
     * Listar todos los tipos de equipos
     */
    public function index(): View
    {
        $tipos = TipoEquipo::with('categoriaObj')
            ->withCount('equipos')
            ->orderBy('nombre')
            ->get();

        return view('tipos-equipos.index', compact('tipos'));
    }

    /**
     * Formulario para crear nuevo tipo de equipo
     */
    public function create(): View
    {
        $categorias = Categoria::activas()->orderBy('nombre')->get();
        $tipoEquipo = null;
        return view('tipos-equipos.create', compact('tipoEquipo', 'categorias'));
    }

    /**
     * Guardar nuevo tipo de equipo
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:tipos_equipos,nombre',
            'descripcion' => 'nullable|string|max:500',
            'categoria_id' => 'required|exists:categorias,id',
            'icono' => 'nullable|string|max:50',
        ], [
            'categoria_id.exists' => 'La categoría seleccionada no existe',
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
        $tipoEquipo->load('categoriaObj', 'equipos');

        return view('tipos-equipos.show', compact('tipoEquipo'));
    }

    /**
     * Formulario para editar tipo de equipo
     */
    public function edit(TipoEquipo $tipoEquipo): View
    {
        $categorias = Categoria::activas()->orderBy('nombre')->get();
        return view('tipos-equipos.edit', compact('tipoEquipo', 'categorias'));
    }

    /**
     * Actualizar tipo de equipo
     */
    public function update(Request $request, TipoEquipo $tipoEquipo): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:tipos_equipos,nombre,' . $tipoEquipo->id,
            'descripcion' => 'nullable|string|max:500',
            'categoria_id' => 'required|exists:categorias,id',
            'icono' => 'nullable|string|max:50',
        ], [
            'categoria_id.exists' => 'La categoría seleccionada no existe',
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

