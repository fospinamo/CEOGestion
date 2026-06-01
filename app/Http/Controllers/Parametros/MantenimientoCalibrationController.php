<?php

namespace App\Http\Controllers\Parametros;

use App\Models\Equipo;
use App\Models\MantenimientoCalibración;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MantenimientoCalibrationController extends Controller
{
    /**
     * Listar mantenimientos y calibraciones programadas de un equipo
     */
    public function index($equipoId)
    {
        $equipo = Equipo::with('mantenimientosCalibraciónes')->findOrFail($equipoId);
        $items = $equipo->mantenimientosCalibraciónes()
                        ->orderBy('fecha_programada', 'desc')
                        ->paginate(15);

        return view('parametros.equipos.mantenimientos.index', compact('equipo', 'items'));
    }

    /**
     * Formulario crear nuevo mantenimiento/calibración
     */
    public function create($equipoId)
    {
        $equipo = Equipo::findOrFail($equipoId);
        
        return view('parametros.equipos.mantenimientos.create', compact('equipo'));
    }

    /**
     * Guardar nuevo mantenimiento/calibración programado
     */
    public function store(Request $request, $equipoId)
    {
        $equipo = Equipo::findOrFail($equipoId);

        $request->validate([
            'tipo' => 'required|in:mantenimiento,calibracion',
            'fecha_programada' => 'required|date|after_or_equal:today',
            'descripcion_trabajo' => 'nullable|string',
            'tecnico_responsable' => 'nullable|string|max:100',
            'empresa_tercero' => 'nullable|string|max:150',
        ]);

        MantenimientoCalibración::create([
            'equipo_id' => $equipo->id,
            'tipo' => $request->tipo,
            'fecha_programada' => $request->fecha_programada,
            'descripcion_trabajo' => $request->descripcion_trabajo,
            'tecnico_responsable' => $request->tecnico_responsable,
            'empresa_tercero' => $request->empresa_tercero,
            'usuario_creador' => auth()->id(),
            'estado' => 'programado',
        ]);

        return redirect()->route('parametros.equipos.mantenimientos.index', $equipo->id)
                        ->with('success', ucfirst($request->tipo) . ' programado exitosamente');
    }

    /**
     * Formulario para registrar realización
     */
    public function registrarRealizacion($equipoId, $mantenimientoId)
    {
        $equipo = Equipo::findOrFail($equipoId);
        $mantenimiento = MantenimientoCalibración::where('equipo_id', $equipo->id)
                                                  ->findOrFail($mantenimientoId);

        return view('parametros.equipos.mantenimientos.registrar', compact('equipo', 'mantenimiento'));
    }

    /**
     * Guardar realización del mantenimiento/calibración
     */
    public function guardarRealizacion(Request $request, $equipoId, $mantenimientoId)
    {
        $equipo = Equipo::findOrFail($equipoId);
        $mantenimiento = MantenimientoCalibración::where('equipo_id', $equipo->id)
                                                  ->findOrFail($mantenimientoId);

        $request->validate([
            'fecha_realizada' => 'required|date|before_or_equal:today',
            'numero_reporte' => 'nullable|string|max:50|unique:mantenimiento_calibraciones,numero_reporte,' . $mantenimiento->id,
            'descripcion_trabajo' => 'nullable|string',
            'tecnico_responsable' => 'nullable|string|max:100',
            'empresa_tercero' => 'nullable|string|max:150',
            'costo' => 'nullable|numeric|min:0',
            'archivo_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB
        ]);

        $rutaArchivo = null;
        if ($request->hasFile('archivo_pdf')) {
            $archivo = $request->file('archivo_pdf');
            $rutaArchivo = $archivo->store("equipos/{$equipo->id}/mantenimientos", 'private');
        }

        $mantenimiento->update([
            'fecha_realizada' => $request->fecha_realizada,
            'numero_reporte' => $request->numero_reporte,
            'descripcion_trabajo' => $request->descripcion_trabajo ?? $mantenimiento->descripcion_trabajo,
            'tecnico_responsable' => $request->tecnico_responsable,
            'empresa_tercero' => $request->empresa_tercero,
            'costo' => $request->costo,
            'archivo_pdf_path' => $rutaArchivo ?? $mantenimiento->archivo_pdf_path,
            'estado' => 'realizado',
            'usuario_realizador' => auth()->id(),
        ]);

        // Actualizar fechas en el equipo
        $mantenimiento->marcarRealizado(auth()->id());

        return redirect()->route('parametros.equipos.mantenimientos.index', $equipo->id)
                        ->with('success', 'Mantenimiento registrado como realizado');
    }

    /**
     * Descargar PDF del reporte
     */
    public function descargarReporte($equipoId, $mantenimientoId)
    {
        $equipo = Equipo::findOrFail($equipoId);
        $mantenimiento = MantenimientoCalibración::where('equipo_id', $equipo->id)
                                                  ->findOrFail($mantenimientoId);

        if (!$mantenimiento->archivo_pdf_path) {
            return back()->with('error', 'No hay archivo disponible para descargar');
        }

        return Storage::disk('private')->download($mantenimiento->archivo_pdf_path, 
            "Reporte_{$mantenimiento->numero_reporte}.pdf");
    }

    /**
     * Eliminar mantenimiento programado
     */
    public function destroy($equipoId, $mantenimientoId)
    {
        $equipo = Equipo::findOrFail($equipoId);
        $mantenimiento = MantenimientoCalibración::where('equipo_id', $equipo->id)
                                                  ->findOrFail($mantenimientoId);

        if ($mantenimiento->estado === 'realizado') {
            return back()->with('error', 'No se puede eliminar un mantenimiento realizado');
        }

        // Eliminar archivo si existe
        if ($mantenimiento->archivo_pdf_path && Storage::disk('private')->exists($mantenimiento->archivo_pdf_path)) {
            Storage::disk('private')->delete($mantenimiento->archivo_pdf_path);
        }

        $mantenimiento->delete();

        return redirect()->route('parametros.equipos.mantenimientos.index', $equipo->id)
                        ->with('success', 'Mantenimiento eliminado');
    }
}
