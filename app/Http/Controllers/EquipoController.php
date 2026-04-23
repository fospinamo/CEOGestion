<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Area;
use App\Models\TipoEquipo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * EquipoController
 * 
 * Gestiona operaciones CRUD para equipos TI.
 * Incluye información técnica, ubicación, estado operativo y asignación de usuarios.
 */
class EquipoController extends Controller
{
    /**
     * Listar todos los equipos con paginación
     */
    public function index(): View
    {
        $equipos = Equipo::with(['area.sede.cliente', 'tipoEquipo', 'servicios'])
            ->get();

        return view('equipos.index', compact('equipos'));
    }

    /**
     * Formulario para crear nuevo equipo
     */
    public function create(): View
    {
        $equipo = null;
        $areas = Area::with('sede.cliente.empresa')
            ->where('estado', true)
            ->orderBy('nombre')
            ->get();

        $tipos = TipoEquipo::orderBy('nombre')->get();

        return view('equipos.create', compact('equipo', 'areas', 'tipos'));
    }

    /**
     * Guardar nuevo equipo
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'tipo_equipo_id' => 'required|exists:tipos_equipos,id',
            'codigo_interno' => 'required|string|unique:equipos,codigo_interno',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'serial' => 'required|string|unique:equipos,serial',
            'fecha_compra' => 'nullable|date',
            'fecha_instalacion' => 'nullable|date',
            'fecha_garantia' => 'nullable|date',
            'valor_compra' => 'nullable|numeric|min:0',
            'estado_operativo' => 'required|in:OPERATIVO,MANTENIMIENTO,REPARACION,BAJA,OBSOLETO',
            'especificaciones_tecnicas' => 'nullable|string',
            'ip_asignada' => 'nullable|string|max:15',
            'mac_address' => 'nullable|string|max:17',
            'usuario_asignado' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        // Convertir especificaciones técnicas a JSON si existe
        if ($request->has('especificaciones_tecnicas') && !empty($request->especificaciones_tecnicas)) {
            $validated['especificaciones_tecnicas'] = json_decode($request->especificaciones_tecnicas, true);
        }

        Equipo::create($validated);

        return redirect()->route('equipos.index')
            ->with('success', 'Equipo registrado exitosamente');
    }

    /**
     * Mostrar detalles de un equipo
     */
    public function show(Equipo $equipo): View
    {
        $equipo->load([
            'area.sede.cliente.empresa',
            'tipoEquipo',
            'servicios.contrato'
        ]);

        return view('equipos.show', compact('equipo'));
    }

    /**
     * Formulario para editar equipo
     */
    public function edit(Equipo $equipo): View
    {
        $areas = Area::with('sede.cliente.empresa')
            ->where('estado', true)
            ->orderBy('nombre')
            ->get();

        $tipos = TipoEquipo::orderBy('nombre')->get();

        return view('equipos.edit', compact('equipo', 'areas', 'tipos'));
    }

    /**
     * Actualizar equipo
     */
    public function update(Request $request, Equipo $equipo): RedirectResponse
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'tipo_equipo_id' => 'required|exists:tipos_equipos,id',
            'codigo_interno' => 'required|string|unique:equipos,codigo_interno,' . $equipo->id,
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'serial' => 'required|string|unique:equipos,serial,' . $equipo->id,
            'fecha_compra' => 'nullable|date',
            'fecha_instalacion' => 'nullable|date',
            'fecha_garantia' => 'nullable|date',
            'valor_compra' => 'nullable|numeric|min:0',
            'estado_operativo' => 'required|in:OPERATIVO,MANTENIMIENTO,REPARACION,BAJA,OBSOLETO',
            'especificaciones_tecnicas' => 'nullable|string',
            'ip_asignada' => 'nullable|string|max:15',
            'mac_address' => 'nullable|string|max:17',
            'usuario_asignado' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        // Convertir especificaciones técnicas a JSON si existe
        if ($request->has('especificaciones_tecnicas') && !empty($request->especificaciones_tecnicas)) {
            $validated['especificaciones_tecnicas'] = json_decode($request->especificaciones_tecnicas, true);
        }

        $equipo->update($validated);

        return redirect()->route('equipos.show', $equipo)
            ->with('success', 'Equipo actualizado exitosamente');
    }

    /**
     * Eliminar equipo (soft delete)
     */
    public function destroy(Equipo $equipo): RedirectResponse
    {
        $equipo->delete();

        return redirect()->route('equipos.index')
            ->with('success', 'Equipo eliminado exitosamente');
    }
}
