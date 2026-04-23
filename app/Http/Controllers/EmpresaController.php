<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = Empresa::get();
        return view('empresas.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('empresas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:empresas|max:255',
            'nit' => 'required|string|unique:empresas|max:20',
            'digito_verificacion' => 'required|string|size:1',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pagina_web' => 'nullable|string|max:255',
            'tipo_contribuyente' => 'required|in:persona_natural,persona_juridica,gran_contribuyente',
            'responsabilidades_fiscales' => 'nullable|array',
            'direccion' => 'nullable|string|max:500',
            'estado' => 'boolean',
        ]);

        Empresa::create($validated);

        return redirect()->route('empresas.index')
            ->with('success', 'Empresa creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
        return view('empresas.show', compact('empresa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        return view('empresas.edit', compact('empresa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:empresas,nombre,' . $empresa->id . '|max:255',
            'nit' => 'required|string|unique:empresas,nit,' . $empresa->id . '|max:20',
            'digito_verificacion' => 'required|string|size:1',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pagina_web' => 'nullable|string|max:255',
            'tipo_contribuyente' => 'required|in:persona_natural,persona_juridica,gran_contribuyente',
            'responsabilidades_fiscales' => 'nullable|array',
            'direccion' => 'nullable|string|max:500',
            'estado' => 'boolean',
        ]);

        $empresa->update($validated);

        return redirect()->route('empresas.index')
            ->with('success', 'Empresa actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        $empresa->delete();

        return redirect()->route('empresas.index')
            ->with('success', 'Empresa eliminada exitosamente');
    }
}
