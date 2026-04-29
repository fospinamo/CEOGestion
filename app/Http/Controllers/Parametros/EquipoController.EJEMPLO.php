<?php

namespace App\Http\Controllers\Parametros;

use App\Models\Equipo;
use App\Models\Sede;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * EquipoController - Módulo Parámetros
 * 
 * Gestión de equipos informáticos del sistema
 * Ruta base: /parametros/equipos
 * Nombre de ruta: parametros.equipos
 */
class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * GET /parametros/equipos
     */
    public function index()
    {
        $equipos = Equipo::with(['area', 'sede'])->paginate(15);
        return view('parametros.equipos.index', compact('equipos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * GET /parametros/equipos/create
     */
    public function create()
    {
        $sedes = Sede::where('estado', 'ACTIVA')->get();
        return view('parametros.equipos.create', compact('sedes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * POST /parametros/equipos
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo_interno' => 'required|unique:equipos',
            'descripcion' => 'required',
            'sede_id' => 'required|exists:sedes,id',
            // ... más validaciones
        ]);

        Equipo::create($validated);

        return redirect()
            ->route('parametros.equipos.index')
            ->with('success', 'Equipo creado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * GET /parametros/equipos/{equipo}
     */
    public function show(Equipo $equipo)
    {
        $equipo->load(['area', 'sede', 'servicios']);
        return view('parametros.equipos.show', compact('equipo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * GET /parametros/equipos/{equipo}/edit
     */
    public function edit(Equipo $equipo)
    {
        $sedes = Sede::where('estado', 'ACTIVA')->get();
        return view('parametros.equipos.edit', compact('equipo', 'sedes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * PUT /parametros/equipos/{equipo}
     */
    public function update(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'codigo_interno' => 'required|unique:equipos,codigo_interno,' . $equipo->id,
            'descripcion' => 'required',
            // ... más validaciones
        ]);

        $equipo->update($validated);

        return redirect()
            ->route('parametros.equipos.show', $equipo)
            ->with('success', 'Equipo actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * DELETE /parametros/equipos/{equipo}
     */
    public function destroy(Equipo $equipo)
    {
        $equipo->delete();

        return redirect()
            ->route('parametros.equipos.index')
            ->with('success', 'Equipo eliminado exitosamente');
    }

    /**
     * ACCIONES PERSONALIZADAS DEL MÓDULO
     */

    /**
     * Exportar equipos a Excel
     * GET /parametros/equipos/exportar/excel
     */
    public function exportarExcel()
    {
        // Implementar exportación a Excel
    }

    /**
     * Exportar equipos a PDF
     * GET /parametros/equipos/exportar/pdf
     */
    public function exportarPDF()
    {
        // Implementar exportación a PDF
    }
}
