<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * ContratoController
 * 
 * Gestiona operaciones CRUD para contratos de servicios TI.
 * Incluye gestión de documentos, estados y renovaciones.
 */
class ContratoController extends Controller
{
    /**
     * Listar todos los contratos con paginación
     */
    public function index(): View
    {
        $contratos = Contrato::with(['cliente.empresa', 'creadoPor'])
            ->get();

        return view('contratos.index', compact('contratos'));
    }

    /**
     * Formulario para crear nuevo contrato
     */
    public function create(): View
    {
        $contrato = null;
        $clientes = Cliente::with('empresa')->orderBy('razon_social')->get();
        $usuarios = User::where('estado', true)->orderBy('name')->get();

        return view('contratos.create', compact('contrato', 'clientes', 'usuarios'));
    }

    /**
     * Guardar nuevo contrato
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'numero_contrato' => 'required|string|unique:contratos,numero_contrato',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_firma' => 'required|date',
            'fecha_terminacion' => 'nullable|date',
            'tipo_contrato' => 'required|in:SOPORTE_TI,MANTENIMIENTO,INFRAESTRUCTURA,CONSULTORIA',
            'modalidad' => 'required|in:MENSUAL,TRIMESTRAL,SEMESTRAL,ANUAL',
            'valor_contrato' => 'required|numeric|min:0',
            'moneda' => 'required|in:COP,USD,EUR',
            'condiciones_pago' => 'required|string',
            'alcance_servicios' => 'required|string',
            'clausulas_especiales' => 'nullable|string',
            'documento_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'documento_firmado' => 'boolean',
            'estado' => 'required|in:BORRADOR,ACTIVO,VENCIDO,TERMINADO,RENOVADO',
            'renovacion_automatica' => 'boolean',
        ]);

        // Manejar archivo PDF si existe
        if ($request->hasFile('documento_pdf')) {
            $path = $request->file('documento_pdf')->store('contratos', 'public');
            $validated['documento_pdf'] = $path;
        }

        $validated['created_by'] = auth()->id();

        Contrato::create($validated);

        return redirect()->route('contratos.index')
            ->with('success', 'Contrato creado exitosamente');
    }

    /**
     * Mostrar detalles de un contrato
     */
    public function show(Contrato $contrato): View
    {
        $contrato->load([
            'cliente.empresa',
            'creadoPor',
            'modificadoPor',
            'servicios.equipo.area',
            'documentosAdjuntos'
        ]);

        return view('contratos.show', compact('contrato'));
    }

    /**
     * Formulario para editar contrato
     */
    public function edit(Contrato $contrato): View
    {
        $clientes = Cliente::with('empresa')->orderBy('razon_social')->get();
        $usuarios = User::where('estado', true)->orderBy('name')->get();

        return view('contratos.edit', compact('contrato', 'clientes', 'usuarios'));
    }

    /**
     * Actualizar contrato
     */
    public function update(Request $request, Contrato $contrato): RedirectResponse
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'numero_contrato' => 'required|string|unique:contratos,numero_contrato,' . $contrato->id,
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_firma' => 'required|date',
            'fecha_terminacion' => 'nullable|date',
            'tipo_contrato' => 'required|in:SOPORTE_TI,MANTENIMIENTO,INFRAESTRUCTURA,CONSULTORIA',
            'modalidad' => 'required|in:MENSUAL,TRIMESTRAL,SEMESTRAL,ANUAL',
            'valor_contrato' => 'required|numeric|min:0',
            'moneda' => 'required|in:COP,USD,EUR',
            'condiciones_pago' => 'required|string',
            'alcance_servicios' => 'required|string',
            'clausulas_especiales' => 'nullable|string',
            'documento_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'documento_firmado' => 'boolean',
            'estado' => 'required|in:BORRADOR,ACTIVO,VENCIDO,TERMINADO,RENOVADO',
            'renovacion_automatica' => 'boolean',
        ]);

        // Manejar archivo PDF si existe
        if ($request->hasFile('documento_pdf')) {
            if ($contrato->documento_pdf && \Storage::disk('public')->exists($contrato->documento_pdf)) {
                \Storage::disk('public')->delete($contrato->documento_pdf);
            }
            $path = $request->file('documento_pdf')->store('contratos', 'public');
            $validated['documento_pdf'] = $path;
        }

        $validated['updated_by'] = auth()->id();

        $contrato->update($validated);

        return redirect()->route('contratos.show', $contrato)
            ->with('success', 'Contrato actualizado exitosamente');
    }

    /**
     * Eliminar contrato (soft delete)
     */
    public function destroy(Contrato $contrato): RedirectResponse
    {
        $contrato->delete();

        return redirect()->route('contratos.index')
            ->with('success', 'Contrato eliminado exitosamente');
    }
}
