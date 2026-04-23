<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\Empresa;
use App\Models\Municipio;
use App\Models\Barrio;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sedes = Sede::with(['empresa', 'municipio', 'barrio'])->get();
        return view('sedes.index', compact('sedes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sede = null;
        $empresas = Empresa::where('estado', true)->get();
        $departamentos = \App\Models\Departamento::with('municipios')->orderBy('nombre')->get();
        $municipios = Municipio::with('departamento')->orderBy('nombre')->get();
        $barrios = Barrio::orderBy('nombre')->get();
        return view('sedes.create', compact('sede', 'empresas', 'departamentos', 'municipios', 'barrios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:sedes|max:20',
            'direccion' => 'nullable|string|max:500',
            'municipio_id' => 'required|exists:municipios,id',
            'barrio_id' => 'nullable|exists:barrios,id',
            'codigo_postal' => 'nullable|string|max:10',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'estado' => 'boolean',
        ]);

        Sede::create($validated);

        return redirect()->route('sedes.index')
            ->with('success', 'Sede creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sede $sede)
    {
        return view('sedes.show', compact('sede'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sede $sede)
    {
        $empresas = Empresa::where('estado', true)->get();
        $departamentos = \App\Models\Departamento::with('municipios')->orderBy('nombre')->get();
        $municipios = Municipio::with('departamento')->orderBy('nombre')->get();
        $barrios = Barrio::orderBy('nombre')->get();
        return view('sedes.edit', compact('sede', 'empresas', 'departamentos', 'municipios', 'barrios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sede $sede)
    {
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:sedes,codigo,' . $sede->id . '|max:20',
            'direccion' => 'nullable|string|max:500',
            'municipio_id' => 'required|exists:municipios,id',
            'barrio_id' => 'nullable|exists:barrios,id',
            'codigo_postal' => 'nullable|string|max:10',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'estado' => 'boolean',
        ]);

        $sede->update($validated);

        return redirect()->route('sedes.index')
            ->with('success', 'Sede actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sede $sede)
    {
        $sede->delete();

        return redirect()->route('sedes.index')
            ->with('success', 'Sede eliminada exitosamente');
    }
}
