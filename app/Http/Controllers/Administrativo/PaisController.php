<?php

namespace App\Http\Controllers\Administrativo;

use App\Models\Pais;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * PaisController - Módulo Administrativo
 * Gestión de países
 * Ruta: /administrativo/paises
 */
class PaisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paises = Pais::with('departamentos')->paginate(15);
        return view('administrativo.paises.index', compact('paises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrativo.paises.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo_dane' => 'required|unique:paises',
            'nombre' => 'required|string|max:100|unique:paises',
        ]);

        Pais::create($validated);

        return redirect()
            ->route('administrativo.paises.index')
            ->with('success', 'País creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pais $paise)
    {
        $paise->load('departamentos');
        return view('administrativo.paises.show', compact('paise'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pais $paise)
    {
        return view('administrativo.paises.edit', compact('paise'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pais $paise)
    {
        $validated = $request->validate([
            'codigo_dane' => 'required|unique:paises,codigo_dane,' . $paise->id,
            'nombre' => 'required|string|max:100|unique:paises,nombre,' . $paise->id,
        ]);

        $paise->update($validated);

        return redirect()
            ->route('administrativo.paises.show', $paise)
            ->with('success', 'País actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pais $paise)
    {
        if ($paise->departamentos()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'No se puede eliminar un país que tiene departamentos asociados');
        }

        $paise->delete();

        return redirect()
            ->route('administrativo.paises.index')
            ->with('success', 'País eliminado exitosamente');
    }
}
