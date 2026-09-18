<?php

namespace App\Http\Controllers\Documentacion;

use App\Http\Controllers\Controller;
use App\Traits\PermissionCheckTrait;
use App\Http\Requests\Documentacion\StoreRadicacionRequest;
use App\Http\Requests\Documentacion\UpdateRadicacionRequest;
use App\Models\Documento;
use App\Models\Empresa;
use App\Models\Radicacion;
use App\Models\Sede;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RadicacionController extends Controller
{
    use PermissionCheckTrait;
    public function index(): View
    {
        $this->checkPermission('radicaciones.ver');
        $radicaciones = Radicacion::with(['empresa', 'sede', 'documento'])
            ->orderByDesc('fecha_radicacion')
            ->get();

        return view('documentacion.radicaciones.index', compact('radicaciones'));
    }

    public function create(): View
    {
        $this->checkPermission('radicaciones.crear');
        $radicacion = null;
        $empresas = Empresa::orderBy('nombre')->get();
        $sedes = Sede::orderBy('nombre')->get();
        $documentos = Documento::orderBy('nombre')->get();

        return view('documentacion.radicaciones.form', compact('radicacion', 'empresas', 'sedes', 'documentos'));
    }

    public function store(StoreRadicacionRequest $request): RedirectResponse
    {
        $this->checkPermission('radicaciones.crear');
        $radicacion = Radicacion::create($request->validated());

        return redirect()
            ->route('documentacion.radicaciones.show', ['radicacion' => $radicacion->id])
            ->with('success', 'Radicacion creada correctamente.');
    }

    public function show(Radicacion $radicacion): View
    {
        $this->checkPermission('radicaciones.ver');
        $radicacion->load(['empresa', 'sede', 'documento']);

        return view('documentacion.radicaciones.show', compact('radicacion'));
    }

    public function edit(Radicacion $radicacion): View
    {
        $this->checkPermission('radicaciones.editar');
        $empresas = Empresa::orderBy('nombre')->get();
        $sedes = Sede::orderBy('nombre')->get();
        $documentos = Documento::orderBy('nombre')->get();

        return view('documentacion.radicaciones.form', compact('radicacion', 'empresas', 'sedes', 'documentos'));
    }

    public function update(UpdateRadicacionRequest $request, Radicacion $radicacion): RedirectResponse
    {
        $this->checkPermission('radicaciones.editar');
        $radicacion->update($request->validated());

        return redirect()
            ->route('documentacion.radicaciones.show', ['radicacion' => $radicacion->id])
            ->with('success', 'Radicacion actualizada correctamente.');
    }

    public function destroy(Radicacion $radicacion): RedirectResponse
    {
        $this->checkPermission('radicaciones.eliminar');
        $radicacion->delete();

        return redirect()
            ->route('documentacion.radicaciones.index')
            ->with('success', 'Radicacion eliminada correctamente.');
    }
}
