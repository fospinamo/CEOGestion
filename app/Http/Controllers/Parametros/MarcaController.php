<?php

namespace App\Http\Controllers\Parametros;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

/**
 * MarcaController - Módulo Parámetros
 * Gestión de marcas de equipos TI
 * Ruta: /parametros/marcas
 */
class MarcaController extends Controller
{
    public function index(): View
    {
        $marcas = Marca::withCount('equipos')
            ->orderBy('nombre')
            ->get();

        return view('parametros.marcas.index', compact('marcas'));
    }

    public function create(): View
    {
        $marca = null;
        return view('parametros.marcas.create', compact('marca'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:marcas,nombre',
            'descripcion' => 'nullable|string|max:255',
            'logo_url' => 'nullable|url',
            'estado' => 'nullable|boolean',
        ]);

        $validated['estado'] = $validated['estado'] ?? true;

        Marca::create($validated);

        return redirect()->route('parametros.marcas.index')
            ->with('success', 'Marca creada exitosamente');
    }

    public function show(Marca $marca): View
    {
        $marca->load('equipos');
        return view('parametros.marcas.show', compact('marca'));
    }

    public function edit(Marca $marca): View
    {
        return view('parametros.marcas.create', compact('marca'));
    }

    public function update(Request $request, Marca $marca): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:marcas,nombre,' . $marca->id,
            'descripcion' => 'nullable|string|max:255',
            'logo_url' => 'nullable|url',
            'estado' => 'nullable|boolean',
        ]);

        $validated['estado'] = $validated['estado'] ?? true;

        $marca->update($validated);

        return redirect()->route('parametros.marcas.index')
            ->with('success', 'Marca actualizada exitosamente');
    }

    public function destroy(Marca $marca): RedirectResponse
    {
        if ($marca->equipos()->count() > 0) {
            return redirect()->route('parametros.marcas.index')
                ->with('error', 'No se puede eliminar una marca que tiene equipos asociados');
        }

        $marca->delete();

        return redirect()->route('parametros.marcas.index')
            ->with('success', 'Marca eliminada exitosamente');
    }
}
