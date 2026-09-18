<?php

namespace App\Http\Controllers\Parametros;

use App\Models\InformeFormato;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Traits\PermissionCheckTrait;

class InformeFormatoController extends Controller
{
    use PermissionCheckTrait;
    public function index(): View
    {
        $this->checkPermission('informe-formatos.ver');
        $formatos = InformeFormato::withCount('empresas')->orderBy('nombre')->get();
        return view('parametros.informe-formatos.index', compact('formatos'));
    }

    public function create(): View
    {
        $this->checkPermission('informe-formatos.crear');
        $formato = null;
        return view('parametros.informe-formatos.create', compact('formato'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkPermission('informe-formatos.crear');
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:informe_formatos,nombre',
            'codigo' => 'required|string|max:50|unique:informe_formatos,codigo',
            'descripcion' => 'nullable|string|max:255',
            'blade_template' => 'required|string|max:255',
            'activo' => 'nullable|boolean',
        ]);

        $validated['activo'] = $validated['activo'] ?? true;

        InformeFormato::create($validated);

        return redirect()->route('parametros.informe-formatos.index')
            ->with('success', 'Formato de informe creado exitosamente');
    }

    public function show(InformeFormato $informeFormato): View
    {
        $this->checkPermission('informe-formatos.ver');
        $informeFormato->load('empresas');
        return view('parametros.informe-formatos.show', ['formato' => $informeFormato]);
    }

    public function edit(InformeFormato $informeFormato): View
    {
        $this->checkPermission('informe-formatos.editar');
        return view('parametros.informe-formatos.create', ['formato' => $informeFormato]);
    }

    public function update(Request $request, InformeFormato $informeFormato): RedirectResponse
    {
        $this->checkPermission('informe-formatos.editar');
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:informe_formatos,nombre,' . $informeFormato->id,
            'codigo' => 'required|string|max:50|unique:informe_formatos,codigo,' . $informeFormato->id,
            'descripcion' => 'nullable|string|max:255',
            'blade_template' => 'required|string|max:255',
            'activo' => 'nullable|boolean',
        ]);

        $validated['activo'] = $validated['activo'] ?? true;

        $informeFormato->update($validated);

        return redirect()->route('parametros.informe-formatos.index')
            ->with('success', 'Formato de informe actualizado exitosamente');
    }

    public function destroy(InformeFormato $informeFormato): RedirectResponse
    {
        $this->checkPermission('informe-formatos.eliminar');
        if ($informeFormato->empresas()->count() > 0) {
            return redirect()->route('parametros.informe-formatos.index')
                ->with('error', 'No se puede eliminar un formato que tiene empresas asignadas');
        }

        $informeFormato->delete();

        return redirect()->route('parametros.informe-formatos.index')
            ->with('success', 'Formato de informe eliminado exitosamente');
    }
}
