<?php

namespace App\Http\Controllers\Parametros;

use App\Models\Categoria;
use App\Models\TipoEquipo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

/**
 * TipoEquipoController - Módulo Parámetros
 * Gestión de tipos de equipos
 * Ruta: /parametros/tipos-equipos
 */
class TipoEquipoController extends Controller
{
    public function index(): View
    {
        $tipos = TipoEquipo::with('categoriaObj')
            ->withCount('equipos')
            ->orderBy('nombre')
            ->get();

        return view('parametros.tipos-equipos.index', compact('tipos'));
    }

    public function create(): View
    {
        $categorias = Categoria::activas()->orderBy('nombre')->get();
        $tipoEquipo = null;
        return view('parametros.tipos-equipos.create', compact('tipoEquipo', 'categorias'));
    }

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

        return redirect()->route('parametros.tipos-equipos.index')
            ->with('success', 'Tipo de equipo creado exitosamente');
    }

    public function show(TipoEquipo $tipoEquipo): View
    {
        $tipoEquipo->load('categoriaObj', 'equipos');

        return view('parametros.tipos-equipos.show', compact('tipoEquipo'));
    }

    public function edit(TipoEquipo $tipoEquipo): View
    {
        // Cargar relaciones necesarias para la vista
        $tipoEquipo->load('categoriaObj');
        $categorias = Categoria::activas()->orderBy('nombre')->get();
        
        return view('parametros.tipos-equipos.edit', compact('tipoEquipo', 'categorias'));
    }

    public function update(Request $request, TipoEquipo $tipoEquipo): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:tipos_equipos,nombre,' . $tipoEquipo->id,
            'descripcion' => 'nullable|string|max:500',
            'categoria_id' => 'required|exists:categorias,id',
            'icono' => 'nullable|string|max:50',
        ], [
            'nombre.unique' => 'Este nombre ya existe',
            'categoria_id.required' => 'La categoría es obligatoria',
            'categoria_id.exists' => 'La categoría seleccionada no existe',
        ]);

        $tipoEquipo->update($validated);

        return redirect()->route('parametros.tipos-equipos.show', $tipoEquipo)
            ->with('success', 'Tipo de equipo actualizado exitosamente');
    }

    public function destroy(TipoEquipo $tipoEquipo): RedirectResponse
    {
        $tipoEquipo->delete();

        return redirect()->route('parametros.tipos-equipos.index')
            ->with('success', 'Tipo de equipo eliminado exitosamente');
    }
}
