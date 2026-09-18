<?php

namespace App\Http\Controllers\Parametros;

use App\Http\Controllers\Controller;
use App\Traits\PermissionCheckTrait;
use App\Models\Cargo;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CargoController extends Controller
{
    use PermissionCheckTrait;

    public function index(): View
    {
        $this->checkPermission('cargos.ver');
        $cargos = Cargo::with('empresa')
            ->orderBy('empresa_id')
            ->orderBy('consecutivo')
            ->get();

        return view('parametros.cargos.index', compact('cargos'));
    }

    public function create(): View
    {
        $this->checkPermission('cargos.crear');
        $cargo = null;
        $empresas = Empresa::orderBy('nombre')->get();

        return view('parametros.cargos.form', compact('cargo', 'empresas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkPermission('cargos.crear');

        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'descripcion' => 'required|string|max:255',
            'estado' => 'nullable|boolean',
        ]);

        $maxConsecutivo = Cargo::where('empresa_id', $validated['empresa_id'])->max('consecutivo') ?? 0;
        $validated['consecutivo'] = $maxConsecutivo + 1;
        $validated['codigo'] = 'CAR-' . str_pad($validated['consecutivo'], 4, '0', STR_PAD_LEFT);
        $validated['estado'] = $validated['estado'] ?? true;

        Cargo::create($validated);

        return redirect()->route('parametros.cargos.index')
            ->with('success', 'Cargo creado exitosamente');
    }

    public function show(Cargo $cargo): View
    {
        $this->checkPermission('cargos.ver');
        $cargo->load(['empresa', 'usuarios']);

        return view('parametros.cargos.show', compact('cargo'));
    }

    public function edit(Cargo $cargo): View
    {
        $this->checkPermission('cargos.editar');
        $empresas = Empresa::orderBy('nombre')->get();

        return view('parametros.cargos.form', compact('cargo', 'empresas'));
    }

    public function update(Request $request, Cargo $cargo): RedirectResponse
    {
        $this->checkPermission('cargos.editar');

        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'descripcion' => 'required|string|max:255',
            'estado' => 'nullable|boolean',
        ]);

        $validated['estado'] = $validated['estado'] ?? true;

        $cargo->update($validated);

        return redirect()->route('parametros.cargos.index')
            ->with('success', 'Cargo actualizado exitosamente');
    }

    public function destroy(Cargo $cargo): RedirectResponse
    {
        $this->checkPermission('cargos.eliminar');

        if ($cargo->usuarios()->count() > 0) {
            return redirect()->route('parametros.cargos.index')
                ->with('error', 'No se puede eliminar un cargo que tiene usuarios asociados');
        }

        $cargo->delete();

        return redirect()->route('parametros.cargos.index')
            ->with('success', 'Cargo eliminado exitosamente');
    }
}
