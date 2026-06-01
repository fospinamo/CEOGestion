<?php

namespace App\Http\Controllers\Parametros;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Proceso;
use App\Models\Sede;
use App\Models\Subproceso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcesoController extends Controller
{
    public function index(Request $request): View
    {
        $query = Proceso::with(['empresa', 'sede.cliente', 'sede.empresa', 'subprocesos']);

        if ($request->filled('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        $procesos = $query->orderBy('proceso')->get();
        $empresas = Empresa::orderBy('nombre')->get();
        $sedes = Sede::with('cliente', 'empresa')->orderBy('nombre')->get();

        return view('parametros.procesos.index', compact('procesos', 'empresas', 'sedes'));
    }

    public function create(): View
    {
        $proceso = null;
        $empresas = Empresa::where('estado', true)->orderBy('nombre')->get();
        $sedes = Sede::with('cliente', 'empresa')->where('estado', true)->orderBy('nombre')->get();

        return view('parametros.procesos.create', compact('proceso', 'empresas', 'sedes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'sede_id' => 'required|exists:sedes,id',
            'proceso' => 'required|string|max:150',
            'estado' => 'nullable|boolean',
            'subprocesos' => 'required|array|min:1',
            'subprocesos.*.nombre' => 'required|string|max:150',
            'subprocesos.*.ruta' => 'required|string|max:255',
            'subprocesos.*.estado' => 'nullable|boolean',
        ]);

        $sedeEmpresaId = Sede::where('id', $validated['sede_id'])->value('empresa_id');
        if ($sedeEmpresaId && (int) $sedeEmpresaId !== (int) $validated['empresa_id']) {
            return back()
                ->withErrors(['sede_id' => 'La sede seleccionada no pertenece a la empresa.'])
                ->withInput();
        }

        $validated['estado'] = $request->has('estado');

        $proceso = Proceso::create($validated);

        $subprocesosPayload = collect($validated['subprocesos'])
            ->map(fn($item) => [
                'nombre' => $item['nombre'],
                'ruta' => $item['ruta'],
                'estado' => array_key_exists('estado', $item) ? (bool) $item['estado'] : true,
            ])
            ->all();

        $proceso->subprocesos()->createMany($subprocesosPayload);

        return redirect()->route('parametros.procesos.index')
            ->with('success', 'Proceso creado correctamente.');
    }

    public function show(Proceso $proceso): View
    {
        $proceso->load(['empresa', 'sede.cliente', 'sede.empresa', 'subprocesos']);

        return view('parametros.procesos.show', compact('proceso'));
    }

    public function edit(Proceso $proceso): View
    {
        $proceso->load(['empresa', 'sede', 'subprocesos']);
        $empresas = Empresa::where('estado', true)->orderBy('nombre')->get();
        $sedes = Sede::with('cliente', 'empresa')->where('estado', true)->orderBy('nombre')->get();

        return view('parametros.procesos.create', compact('proceso', 'empresas', 'sedes'));
    }

    public function update(Request $request, Proceso $proceso): RedirectResponse
    {
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'sede_id' => 'required|exists:sedes,id',
            'proceso' => 'required|string|max:150',
            'estado' => 'nullable|boolean',
            'subprocesos' => 'required|array|min:1',
            'subprocesos.*.nombre' => 'required|string|max:150',
            'subprocesos.*.ruta' => 'required|string|max:255',
            'subprocesos.*.estado' => 'nullable|boolean',
        ]);

        $sedeEmpresaId = Sede::where('id', $validated['sede_id'])->value('empresa_id');
        if ($sedeEmpresaId && (int) $sedeEmpresaId !== (int) $validated['empresa_id']) {
            return back()
                ->withErrors(['sede_id' => 'La sede seleccionada no pertenece a la empresa.'])
                ->withInput();
        }

        $validated['estado'] = $request->has('estado');

        $proceso->update($validated);

        $subprocesosPayload = collect($validated['subprocesos'])
            ->map(fn($item) => [
                'nombre' => $item['nombre'],
                'ruta' => $item['ruta'],
                'estado' => array_key_exists('estado', $item) ? (bool) $item['estado'] : true,
            ])
            ->all();

        $proceso->subprocesos()->delete();
        $proceso->subprocesos()->createMany($subprocesosPayload);

        return redirect()->route('parametros.procesos.index')
            ->with('success', 'Proceso actualizado correctamente.');
    }

    public function destroy(Proceso $proceso): RedirectResponse
    {
        $proceso->delete();

        return redirect()->route('parametros.procesos.index')
            ->with('success', 'Proceso eliminado correctamente.');
    }
}
