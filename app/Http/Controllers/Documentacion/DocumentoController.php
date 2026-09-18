<?php

namespace App\Http\Controllers\Documentacion;

use App\Http\Controllers\Controller;
use App\Traits\PermissionCheckTrait;
use App\Http\Requests\Documentacion\StoreDocumentoRequest;
use App\Http\Requests\Documentacion\UpdateDocumentoRequest;
use App\Models\Documento;
use App\Models\Empresa;
use App\Models\Proceso;
use App\Models\Sede;
use App\Models\Subproceso;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DocumentoController extends Controller
{
    use PermissionCheckTrait;
    public function index(): View
    {
        $this->checkPermission('documentos.ver');
        $documentos = Documento::with(['empresa', 'sede', 'proceso', 'subproceso'])
            ->orderBy('nombre')
            ->get();

        return view('documentacion.documentos.index', compact('documentos'));
    }

    public function create(): View
    {
        $this->checkPermission('documentos.crear');
        $documento = null;
        $empresas = Empresa::orderBy('nombre')->get();
        $sedes = Sede::orderBy('nombre')->get();
        $procesos = Proceso::orderBy('proceso')->get();
        $subprocesos = Subproceso::orderBy('nombre')->get();

        return view('documentacion.documentos.form', compact(
            'documento',
            'empresas',
            'sedes',
            'procesos',
            'subprocesos'
        ));
    }

    public function store(StoreDocumentoRequest $request): RedirectResponse
    {
        $this->checkPermission('documentos.crear');
        $documento = Documento::create($request->validated());

        return redirect()
            ->route('documentacion.documentos.show', ['documento' => $documento->id])
            ->with('success', 'Documento creado correctamente.');
    }

    public function show(Documento $documento): View
    {
        $this->checkPermission('documentos.ver');
        $documento->load(['empresa', 'sede', 'proceso', 'subproceso']);

        return view('documentacion.documentos.show', compact('documento'));
    }

    public function edit(Documento $documento): View
    {
        $this->checkPermission('documentos.editar');
        $empresas = Empresa::orderBy('nombre')->get();
        $sedes = Sede::orderBy('nombre')->get();
        $procesos = Proceso::orderBy('proceso')->get();
        $subprocesos = Subproceso::orderBy('nombre')->get();

        return view('documentacion.documentos.form', compact(
            'documento',
            'empresas',
            'sedes',
            'procesos',
            'subprocesos'
        ));
    }

    public function update(UpdateDocumentoRequest $request, Documento $documento): RedirectResponse
    {
        $this->checkPermission('documentos.editar');
        $documento->update($request->validated());

        return redirect()
            ->route('documentacion.documentos.show', ['documento' => $documento->id])
            ->with('success', 'Documento actualizado correctamente.');
    }

    public function destroy(Documento $documento): RedirectResponse
    {
        $this->checkPermission('documentos.eliminar');
        $documento->delete();

        return redirect()
            ->route('documentacion.documentos.index')
            ->with('success', 'Documento eliminado correctamente.');
    }
}
