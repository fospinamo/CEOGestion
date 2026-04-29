<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\Municipio;
use App\Models\Barrio;
use Illuminate\Http\Request;

/**
 * SedeController
 * 
 * Gestiona el CRUD de sedes que pueden pertenecer a:
 * - La empresa (CEOGestion): sedes propias
 * - Un cliente: ubicaciones del cliente
 */
class SedeController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * Obtiene todas las sedes con sus relaciones (empresa/cliente, municipio, barrio)
     * ordenadas de forma legible.
     */
    public function index()
    {
        $sedes = Sede::with(['empresa', 'cliente', 'municipio', 'barrio'])->get();
        return view('sedes.index', compact('sedes'));
    }

    /**
     * Show the form for creating a new resource.
     * 
     * Carga el formulario con opciones para crear una sede de empresa O cliente,
     * así como los datos de ubicación (departamentos, municipios, barrios).
     */
    public function create()
    {
        $sede = null;
        $empresas = Empresa::where('estado', true)->get();
        $clientes = Cliente::where('estado', true)->get();
        $departamentos = \App\Models\Departamento::with('municipios')->orderBy('nombre')->get();
        $municipios = Municipio::with('departamento')->orderBy('nombre')->get();
        $barrios = Barrio::orderBy('nombre')->get();
        return view('sedes.create', compact('sede', 'empresas', 'clientes', 'departamentos', 'municipios', 'barrios'));
    }

    /**
     * Store a newly created resource in storage.
     * 
     * Valida y guarda los datos de la nueva sede.
     * Asegura que pertenezca a empresa O cliente, pero no a ambos.
     */
    public function store(Request $request)
    {
        // Validar que UNA de las dos opciones sea seteada
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

        // Validar que UNA de las dos sea NO NULL
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
     * 
     * Carga el formulario de edición con opciones de empresa y cliente.
     * Permite cambiar la pertenencia (empresa a cliente o viceversa).
     */
    public function edit(Sede $sede)
    {
        $empresas = Empresa::where('estado', true)->get();
        $clientes = Cliente::where('estado', true)->get();
        $departamentos = \App\Models\Departamento::with('municipios')->orderBy('nombre')->get();
        $municipios = Municipio::with('departamento')->orderBy('nombre')->get();
        $barrios = Barrio::orderBy('nombre')->get();
        return view('sedes.edit', compact('sede', 'empresas', 'clientes', 'departamentos', 'municipios', 'barrios'));
    }

    /**
     * Update the specified resource in storage.
     * 
     * Valida y actualiza los datos de la sede.
     * Asegura que siga perteneciendo a empresa O cliente, pero no a ambos.
     */
    public function update(Request $request, Sede $sede)
    {
        $request->validate([
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

        // Validar que UNA de las dos sea NO NULL
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

        $sede->update($request->except(['_token', '_method']));

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

