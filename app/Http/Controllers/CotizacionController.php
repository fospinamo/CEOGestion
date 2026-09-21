<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Traits\PermissionCheckTrait;

class CotizacionController extends Controller
{
    use PermissionCheckTrait;

    public function index(): View
    {
        $this->checkPermission('cotizaciones.ver');

        $cotizaciones = Cotizacion::with(['cliente', 'creadoPor'])
            ->orderBy('fecha_cotizacion', 'desc')
            ->get();

        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function create(): View
    {
        $this->checkPermission('cotizaciones.crear');

        $clientes = Cliente::with('empresa')->orderBy('razon_social')->get();

        return view('cotizaciones.create', compact('clientes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkPermission('cotizaciones.crear');

        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'numero_cotizacion' => 'required|string|unique:cotizaciones,numero_cotizacion',
            'fecha_cotizacion' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_cotizacion',
            'tipo_servicio' => 'required|in:SOPORTE_TI,MANTENIMIENTO,INFRAESTRUCTURA,CONSULTORIA',
            'valor_subtotal' => 'required|numeric|min:0',
            'valor_iva' => 'nullable|numeric|min:0',
            'moneda' => 'required|in:COP,USD,EUR',
            'descripcion_servicio' => 'required|string',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:BORRADOR,ENVIADA,APROBADA,RECHAZADA,VENCIDA',
        ]);

        $validated['valor_total'] = ($validated['valor_subtotal'] ?? 0) + ($validated['valor_iva'] ?? 0);
        $validated['created_by'] = auth()->id();

        Cotizacion::create($validated);

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización creada exitosamente');
    }

    public function show(Cotizacion $cotizacion): View
    {
        $this->checkPermission('cotizaciones.ver');

        $cotizacion->load(['cliente.empresa', 'creadoPor', 'modificadoPor']);

        return view('cotizaciones.show', compact('cotizacion'));
    }

    public function edit(Cotizacion $cotizacion): View
    {
        $this->checkPermission('cotizaciones.editar');

        $clientes = Cliente::with('empresa')->orderBy('razon_social')->get();

        return view('cotizaciones.edit', compact('cotizacion', 'clientes'));
    }

    public function update(Request $request, Cotizacion $cotizacion): RedirectResponse
    {
        $this->checkPermission('cotizaciones.editar');

        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'numero_cotizacion' => 'required|string|unique:cotizaciones,numero_cotizacion,' . $cotizacion->id,
            'fecha_cotizacion' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_cotizacion',
            'tipo_servicio' => 'required|in:SOPORTE_TI,MANTENIMIENTO,INFRAESTRUCTURA,CONSULTORIA',
            'valor_subtotal' => 'required|numeric|min:0',
            'valor_iva' => 'nullable|numeric|min:0',
            'moneda' => 'required|in:COP,USD,EUR',
            'descripcion_servicio' => 'required|string',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:BORRADOR,ENVIADA,APROBADA,RECHAZADA,VENCIDA',
        ]);

        $validated['valor_total'] = ($validated['valor_subtotal'] ?? 0) + ($validated['valor_iva'] ?? 0);
        $validated['updated_by'] = auth()->id();

        $cotizacion->update($validated);

        return redirect()->route('cotizaciones.show', $cotizacion)
            ->with('success', 'Cotización actualizada exitosamente');
    }

    public function destroy(Cotizacion $cotizacion): RedirectResponse
    {
        $this->checkPermission('cotizaciones.eliminar');

        $cotizacion->delete();

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización eliminada exitosamente');
    }
}
