<?php

namespace App\Http\Controllers\Parametros;

use App\Models\Empresa;
use App\Models\InformeFormato;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\PermissionCheckTrait;
use Illuminate\Support\Facades\Storage;

/**
 * EmpresaController - Módulo Parámetros
 * Gestión de empresas en el sistema
 * Ruta: /parametros/empresas
 */
class EmpresaController extends Controller
{
    use PermissionCheckTrait;

    public function index()
    {
        $this->checkPermission('empresas.ver');
        $empresas = Empresa::get();
        return view('parametros.empresas.index', compact('empresas'));
    }

    public function create()
    {
        $this->checkPermission('empresas.crear');
        $formatos = InformeFormato::activos()->orderBy('nombre')->get();
        return view('parametros.empresas.create', compact('formatos'));
    }

    public function store(Request $request)
    {
        $this->checkPermission('empresas.crear');
        $validated = $request->validate([
            'nombre' => 'required|string|unique:empresas|max:255',
            'nit' => 'required|string|unique:empresas|max:20',
            'digito_verificacion' => 'required|string|size:1',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pagina_web' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'descripcion' => 'nullable|string|max:1000',
            'tipo_contribuyente' => 'required|in:persona_natural,persona_juridica,gran_contribuyente',
            'responsabilidades_fiscales' => 'nullable|array',
            'direccion' => 'nullable|string|max:500',
            'estado' => 'boolean',
            'informe_formato_id' => 'nullable|exists:informe_formatos,id',
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('empresas'), $filename);
            $validated['logo'] = 'empresas/' . $filename;
        }

        Empresa::create($validated);

        return redirect()->route('parametros.empresas.index')
            ->with('success', 'Empresa creada exitosamente');
    }

    public function show(Empresa $empresa)
    {
        $this->checkPermission('empresas.ver');
        return view('parametros.empresas.show', compact('empresa'));
    }

    public function edit(Empresa $empresa)
    {
        $this->checkPermission('empresas.editar');
        $formatos = InformeFormato::activos()->orderBy('nombre')->get();
        return view('parametros.empresas.edit', compact('empresa', 'formatos'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $this->checkPermission('empresas.editar');
        $validated = $request->validate([
            'nombre' => 'required|string|unique:empresas,nombre,' . $empresa->id . '|max:255',
            'nit' => 'required|string|unique:empresas,nit,' . $empresa->id . '|max:20',
            'digito_verificacion' => 'required|string|size:1',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pagina_web' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'descripcion' => 'nullable|string|max:1000',
            'tipo_contribuyente' => 'required|in:persona_natural,persona_juridica,gran_contribuyente',
            'responsabilidades_fiscales' => 'nullable|array',
            'direccion' => 'nullable|string|max:500',
            'estado' => 'boolean',
            'informe_formato_id' => 'nullable|exists:informe_formatos,id',
        ]);

        // Manejar subida de logo
        if ($request->hasFile('logo')) {
            // Eliminar logo anterior si existe
            if ($empresa->logo && file_exists(public_path($empresa->logo))) {
                @unlink(public_path($empresa->logo));
            }
            
            // Guardar nuevo logo
            $logo = $request->file('logo');
            $filename = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('empresas'), $filename);
            $validated['logo'] = 'empresas/' . $filename;
        }

        $empresa->update($validated);

        return redirect()->route('parametros.empresas.index')
            ->with('success', 'Empresa actualizada exitosamente');
    }

    public function destroy(Empresa $empresa)
    {
        $this->checkPermission('empresas.eliminar');
        $empresa->delete();

        return redirect()->route('parametros.empresas.index')
            ->with('success', 'Empresa eliminada exitosamente');
    }
}
