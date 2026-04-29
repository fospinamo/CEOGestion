<?php

namespace App\Http\Controllers\Parametros;

use App\Models\Sede;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\Municipio;
use App\Models\Barrio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * SedeController - Módulo Parámetros
 * Gestión de sedes (empresa o cliente)
 * Ruta: /parametros/sedes
 */
class SedeController extends Controller
{
    public function index()
    {
        $sedes = Sede::with(['empresa', 'cliente', 'municipio', 'barrio'])->get();
        return view('parametros.sedes.index', compact('sedes'));
    }

    public function create()
    {
        $sede = null;
        $empresas = Empresa::where('estado', true)->get();
        $clientes = Cliente::where('estado', true)->get();
        $departamentos = \App\Models\Departamento::with('municipios')->orderBy('nombre')->get();
        $municipios = Municipio::with('departamento')->orderBy('nombre')->get();
        $barrios = Barrio::orderBy('nombre')->get();
        return view('parametros.sedes.create', compact('sede', 'empresas', 'clientes', 'departamentos', 'municipios', 'barrios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'nullable|exists:empresas,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:sedes|max:20',
            'direccion' => 'nullable|string|max:500',
            'municipio_id' => 'required|exists:municipios,id',
            'barrio_id' => 'nullable|exists:barrios,id',
            'codigo_postal' => 'nullable|string|max:10',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'estado' => 'boolean',
        ], [
            'empresa_id.exists' => 'La empresa seleccionada no existe',
            'cliente_id.exists' => 'El cliente seleccionado no existe',
            'municipio_id.required' => 'El municipio es requerido',
            'municipio_id.exists' => 'El municipio seleccionado no existe',
        ]);

        if (is_null($request->empresa_id) && is_null($request->cliente_id)) {
            return back()->withErrors([
                'propietario' => 'La sede debe pertenecer a una empresa O a un cliente'
            ])->withInput();
        }

        if (!is_null($request->empresa_id) && !is_null($request->cliente_id)) {
            return back()->withErrors([
                'propietario' => 'La sede no puede pertenecer simultáneamente a empresa y cliente'
            ])->withInput();
        }

        $sede = Sede::create($request->except(['_token', '_method']));

        return redirect()->route('parametros.sedes.index')
            ->with('success', 'Sede creada exitosamente');
    }

    public function show(Sede $sede)
    {
        $sede->load(['empresa', 'cliente', 'municipio.departamento.pais', 'barrio', 'areas']);
        return view('parametros.sedes.show', compact('sede'));
    }

    public function edit(Sede $sede)
    {
        $empresas = Empresa::where('estado', true)->get();
        $clientes = Cliente::where('estado', true)->get();
        $departamentos = \App\Models\Departamento::with('municipios')->orderBy('nombre')->get();
        $municipios = Municipio::with('departamento')->orderBy('nombre')->get();
        $barrios = Barrio::orderBy('nombre')->get();
        return view('parametros.sedes.edit', compact('sede', 'empresas', 'clientes', 'departamentos', 'municipios', 'barrios'));
    }

    public function update(Request $request, Sede $sede)
    {
        $validated = $request->validate([
            'empresa_id' => 'nullable|exists:empresas,id',
            'cliente_id' => 'nullable|exists:clientes,id',
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

        if (is_null($request->empresa_id) && is_null($request->cliente_id)) {
            return back()->withErrors([
                'propietario' => 'La sede debe pertenecer a una empresa O a un cliente'
            ])->withInput();
        }

        if (!is_null($request->empresa_id) && !is_null($request->cliente_id)) {
            return back()->withErrors([
                'propietario' => 'La sede no puede pertenecer simultáneamente a empresa y cliente'
            ])->withInput();
        }

        $sede->update($validated);

        return redirect()->route('parametros.sedes.index')
            ->with('success', 'Sede actualizada exitosamente');
    }

    public function destroy(Sede $sede)
    {
        $sede->delete();

        return redirect()->route('parametros.sedes.index')
            ->with('success', 'Sede eliminada exitosamente');
    }
}
