<?php

namespace App\Http\Controllers\Documentacion;

use App\Http\Controllers\Controller;
use App\Traits\PermissionCheckTrait;
use App\Http\Requests\Documentacion\StoreTablaRetencionDocumentalRequest;
use App\Http\Requests\Documentacion\UpdateTablaRetencionDocumentalRequest;
use App\Models\Empresa;
use App\Models\TablaRetencionDocumental;
use App\Models\TrdDetalle;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TablaRetencionDocumentalController extends Controller
{
    use PermissionCheckTrait;

    public function index(): View
    {
        $this->checkPermission('trd.ver');
        $tablas = TablaRetencionDocumental::with(['empresa', 'usuarioCrea'])
            ->orderByDesc('created_at')
            ->get();

        return view('documentacion.tablas-retencion.index', compact('tablas'));
    }

    public function create(): View
    {
        $this->checkPermission('trd.crear');
        $tabla = null;
        $empresas = Empresa::orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();

        return view('documentacion.tablas-retencion.form', compact('tabla', 'empresas', 'usuarios'));
    }

    public function store(StoreTablaRetencionDocumentalRequest $request): RedirectResponse
    {
        $this->checkPermission('trd.crear');

        $data = $request->validated();
        $detalles = $data['detalles'];
        unset($data['detalles']);

        $maxConsecutivo = TablaRetencionDocumental::max('consecutivo') ?? 0;
        $data['consecutivo'] = $maxConsecutivo + 1;

        DB::beginTransaction();
        try {
            $tabla = TablaRetencionDocumental::create($data);

            foreach ($detalles as $index => $detalle) {
                $detalle['tabla_retencion_documental_id'] = $tabla->id;
                $detalle['orden'] = $index + 1;
                TrdDetalle::create($detalle);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error al crear la TRD: ' . $e->getMessage());
        }

        return redirect()
            ->route('documentacion.tablas_retencion.show', ['tabla_retencion' => $tabla->id])
            ->with('success', 'Tabla de Retención Documental creada correctamente.');
    }

    public function show(TablaRetencionDocumental $tabla_retencion): View
    {
        $this->checkPermission('trd.ver');
        $tabla_retencion->load(['empresa', 'usuarioCrea', 'detalles' => function ($query) {
            $query->orderBy('orden');
        }]);

        return view('documentacion.tablas-retencion.show', ['tabla' => $tabla_retencion]);
    }

    public function edit(TablaRetencionDocumental $tabla_retencion): View
    {
        $this->checkPermission('trd.editar');
        $tabla_retencion->load(['detalles' => function ($query) {
            $query->orderBy('orden');
        }]);
        $empresas = Empresa::orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();

        return view('documentacion.tablas-retencion.form', [
            'tabla' => $tabla_retencion,
            'empresas' => $empresas,
            'usuarios' => $usuarios,
        ]);
    }

    public function update(UpdateTablaRetencionDocumentalRequest $request, TablaRetencionDocumental $tabla_retencion): RedirectResponse
    {
        $this->checkPermission('trd.editar');

        $data = $request->validated();
        $detalles = $data['detalles'];
        unset($data['detalles']);

        DB::beginTransaction();
        try {
            $tabla_retencion->update($data);

            $tabla_retencion->detalles()->delete();

            foreach ($detalles as $index => $detalle) {
                $detalle['tabla_retencion_documental_id'] = $tabla_retencion->id;
                $detalle['orden'] = $index + 1;
                TrdDetalle::create($detalle);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error al actualizar la TRD: ' . $e->getMessage());
        }

        return redirect()
            ->route('documentacion.tablas_retencion.show', ['tabla_retencion' => $tabla_retencion->id])
            ->with('success', 'Tabla de Retención Documental actualizada correctamente.');
    }

    public function destroy(TablaRetencionDocumental $tabla_retencion): RedirectResponse
    {
        $this->checkPermission('trd.eliminar');

        $tabla_retencion->delete();

        return redirect()
            ->route('documentacion.tablas_retencion.index')
            ->with('success', 'Tabla de Retención Documental eliminada correctamente.');
    }
}
