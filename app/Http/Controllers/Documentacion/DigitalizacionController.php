<?php

namespace App\Http\Controllers\Documentacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Documentacion\StoreDigitalizacionRequest;
use App\Http\Requests\Documentacion\UpdateDigitalizacionRequest;
use App\Models\Digitalizacion;
use App\Models\Documento;
use App\Models\Empresa;
use App\Models\Proceso;
use App\Models\Radicacion;
use App\Models\Sede;
use App\Models\Subproceso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DigitalizacionController extends Controller
{
    public function index(): View
    {
        $digitalizaciones = Digitalizacion::with([
            'empresa',
            'sede',
            'proceso',
            'subproceso',
            'documento',
            'user',
        ])->orderByDesc('created_at')->get();

        return view('documentacion.digitalizaciones.index', compact('digitalizaciones'));
    }

    public function create(): View
    {
        $digitalizacion = null;
        $empresas = Empresa::orderBy('nombre')->get();
        $sedes = Sede::orderBy('nombre')->get();
        $procesos = Proceso::orderBy('proceso')->get();
        $subprocesos = Subproceso::orderBy('nombre')->get();
        $documentos = Documento::orderBy('nombre')->get();
        $radicaciones = Radicacion::orderByDesc('fecha_radicacion')->get();

        return view('documentacion.digitalizaciones.form', compact(
            'digitalizacion',
            'empresas',
            'sedes',
            'procesos',
            'subprocesos',
            'documentos',
            'radicaciones'
        ));
    }

    public function store(StoreDigitalizacionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $ruta = $archivo->store('documentacion/digitalizaciones', 'private');
            $data['ruta'] = $ruta;
            $data['nombre_archivo'] = $archivo->getClientOriginalName();
            $data['extension'] = $archivo->getClientOriginalExtension();
            $data['tamano_bytes'] = $archivo->getSize();
        }

        unset($data['archivo']);

        $digitalizacion = Digitalizacion::create($data);

        return redirect()
            ->route('documentacion.digitalizaciones.show', ['digitalizacion' => $digitalizacion->id])
            ->with('success', 'Digitalizacion creada correctamente.');
    }

    public function show(Digitalizacion $digitalizacion): View
    {
        $digitalizacion->load(['empresa', 'sede', 'proceso', 'subproceso', 'documento', 'user', 'radicacion']);

        return view('documentacion.digitalizaciones.show', compact('digitalizacion'));
    }

    public function edit(Digitalizacion $digitalizacion): View
    {
        $empresas = Empresa::orderBy('nombre')->get();
        $sedes = Sede::orderBy('nombre')->get();
        $procesos = Proceso::orderBy('proceso')->get();
        $subprocesos = Subproceso::orderBy('nombre')->get();
        $documentos = Documento::orderBy('nombre')->get();
        $radicaciones = Radicacion::orderByDesc('fecha_radicacion')->get();

        return view('documentacion.digitalizaciones.form', compact(
            'digitalizacion',
            'empresas',
            'sedes',
            'procesos',
            'subprocesos',
            'documentos',
            'radicaciones'
        ));
    }

    public function update(UpdateDigitalizacionRequest $request, Digitalizacion $digitalizacion): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $digitalizacion->user_id ?? $request->user()->id;

        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $ruta = $archivo->store('documentacion/digitalizaciones', 'private');
            $data['ruta'] = $ruta;
            $data['nombre_archivo'] = $archivo->getClientOriginalName();
            $data['extension'] = $archivo->getClientOriginalExtension();
            $data['tamano_bytes'] = $archivo->getSize();

            if ($digitalizacion->ruta) {
                Storage::disk('private')->delete($digitalizacion->ruta);
            }
        }

        unset($data['archivo']);

        $digitalizacion->update($data);

        return redirect()
            ->route('documentacion.digitalizaciones.show', ['digitalizacion' => $digitalizacion->id])
            ->with('success', 'Digitalizacion actualizada correctamente.');
    }

    public function destroy(Digitalizacion $digitalizacion): RedirectResponse
    {
        if ($digitalizacion->ruta) {
            Storage::disk('private')->delete($digitalizacion->ruta);
        }

        $digitalizacion->delete();

        return redirect()
            ->route('documentacion.digitalizaciones.index')
            ->with('success', 'Digitalizacion eliminada correctamente.');
    }
}
