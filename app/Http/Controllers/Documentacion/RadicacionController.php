<?php

namespace App\Http\Controllers\Documentacion;

use App\Http\Controllers\Controller;
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
    public function index(): View
    {
        $radicaciones = Radicacion::with(['empresa', 'sede', 'documento'])
            ->orderByDesc('fecha_radicacion')
            ->get();

        return view('documentacion.radicaciones.index', compact('radicaciones'));
    }

    public function create(): View
    {
        $radicacion = null;
        $empresas = Empresa::orderBy('nombre')->get();
        $sedes = Sede::orderBy('nombre')->get();
        $documentos = Documento::orderBy('nombre')->get();

        return view('documentacion.radicaciones.form', compact('radicacion', 'empresas', 'sedes', 'documentos'));
    }

    public function store(StoreRadicacionRequest $request): RedirectResponse
    {
        $radicacion = Radicacion::create($request->validated());

        return redirect()
            ->route('documentacion.radicaciones.show', ['radicacion' => $radicacion->id])
            ->with('success', 'Radicacion creada correctamente.');
    }

    public function show(Radicacion $radicacion): View
    {
        $radicacion->load(['empresa', 'sede', 'documento']);

        return view('documentacion.radicaciones.show', compact('radicacion'));
    }

    public function edit(Radicacion $radicacion): View
    {
        $empresas = Empresa::orderBy('nombre')->get();
        $sedes = Sede::orderBy('nombre')->get();
        $documentos = Documento::orderBy('nombre')->get();

        return view('documentacion.radicaciones.form', compact('radicacion', 'empresas', 'sedes', 'documentos'));
    }

    public function update(UpdateRadicacionRequest $request, Radicacion $radicacion): RedirectResponse
    {
        $radicacion->update($request->validated());

        return redirect()
            ->route('documentacion.radicaciones.show', ['radicacion' => $radicacion->id])
            ->with('success', 'Radicacion actualizada correctamente.');
    }

    public function destroy(Radicacion $radicacion): RedirectResponse
    {
        $radicacion->delete();

        return redirect()
            ->route('documentacion.radicaciones.index')
            ->with('success', 'Radicacion eliminada correctamente.');
    }
}
