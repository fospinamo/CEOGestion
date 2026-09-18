<?php

namespace App\Http\Controllers\Documentacion;

use App\Http\Controllers\Controller;
use App\Traits\PermissionCheckTrait;
use App\Http\Requests\Documentacion\StoreClaseDocumentalRequest;
use App\Http\Requests\Documentacion\UpdateClaseDocumentalRequest;
use App\Models\ClaseDocumental;
use App\Models\ClaseDocumentalHistorial;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ClaseDocumentalController extends Controller
{
    use PermissionCheckTrait;

    public function index(): View
    {
        $this->checkPermission('clases_documentales.ver');
        $clasesDocumentales = ClaseDocumental::with(['realizadoPor', 'registradoPor', 'revisadoPor'])
            ->orderByDesc('created_at')
            ->get();

        return view('documentacion.parametros.clases-documentales.index', compact('clasesDocumentales'));
    }

    public function create(): View
    {
        $this->checkPermission('clases_documentales.crear');
        $claseDocumental = null;
        $usuarios = User::orderBy('name')->get();

        return view('documentacion.parametros.clases-documentales.form', compact('claseDocumental', 'usuarios'));
    }

    public function store(StoreClaseDocumentalRequest $request): RedirectResponse
    {
        $this->checkPermission('clases_documentales.crear');

        $data = $request->validated();

        $maxConsecutivo = ClaseDocumental::max('consecutivo') ?? 0;
        $data['consecutivo'] = $maxConsecutivo + 1;

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $ruta = $archivo->store('documentacion/clases-documentales', 'private');
            $data['imagen'] = $ruta;
        }

        ClaseDocumental::create($data);

        return redirect()
            ->route('documentacion.parametros.clases_documentales.index')
            ->with('success', 'Clase documental creada correctamente.');
    }

    public function show(ClaseDocumental $claseDocumental): View
    {
        $this->checkPermission('clases_documentales.ver');
        $claseDocumental->load(['realizadoPor', 'registradoPor', 'revisadoPor', 'historiales']);

        return view('documentacion.parametros.clases-documentales.show', compact('claseDocumental'));
    }

    public function edit(ClaseDocumental $claseDocumental): View
    {
        $this->checkPermission('clases_documentales.editar');
        $usuarios = User::orderBy('name')->get();

        return view('documentacion.parametros.clases-documentales.form', compact('claseDocumental', 'usuarios'));
    }

    public function update(UpdateClaseDocumentalRequest $request, ClaseDocumental $claseDocumental): RedirectResponse
    {
        $this->checkPermission('clases_documentales.editar');

        ClaseDocumentalHistorial::create([
            'clase_documental_id' => $claseDocumental->id,
            'codigo' => $claseDocumental->consecutivo,
            'version' => $claseDocumental->version,
            'fecha_cambio' => now(),
            'imagen' => $claseDocumental->imagen,
        ]);

        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            if ($claseDocumental->imagen) {
                Storage::disk('private')->delete($claseDocumental->imagen);
            }
            $archivo = $request->file('imagen');
            $ruta = $archivo->store('documentacion/clases-documentales', 'private');
            $data['imagen'] = $ruta;
        }

        $claseDocumental->update($data);

        return redirect()
            ->route('documentacion.parametros.clases_documentales.show', ['clase_documental' => $claseDocumental->id])
            ->with('success', 'Clase documental actualizada correctamente.');
    }

    public function destroy(ClaseDocumental $claseDocumental): RedirectResponse
    {
        $this->checkPermission('clases_documentales.eliminar');

        if ($claseDocumental->imagen) {
            Storage::disk('private')->delete($claseDocumental->imagen);
        }

        $claseDocumental->delete();

        return redirect()
            ->route('documentacion.parametros.clases_documentales.index')
            ->with('success', 'Clase documental eliminada correctamente.');
    }
}
