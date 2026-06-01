<?php

namespace App\Http\Controllers\Parametros;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\MantenimientoProgramado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * MantenimientoController
 * 
 * Gestiona la planificación y ejecución de mantenimientos y calibraciones
 * de equipos, incluyendo asignación a técnicos y generación de servicios.
 */
class MantenimientoController extends Controller
{
    /**
     * Listar todos los mantenimientos programados
     */
    public function index(Request $request): View
    {
        $query = MantenimientoProgramado::with(['equipo.area.sede.cliente', 'tecnico'])
            ->orderByDesc('fecha_programada');

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('tecnico_id')) {
            $query->where('tecnico_id', $request->tecnico_id);
        }

        // Filtro de fechas
        if ($request->filled('desde') && $request->filled('hasta')) {
            $desde = Carbon::parse($request->desde)->startOfDay();
            $hasta = Carbon::parse($request->hasta)->endOfDay();
            $query->whereBetween('fecha_programada', [$desde, $hasta]);
        }

        $mantenimientos = $query->paginate(50);
        $tecnicos = User::where('estado', true)->whereHas('roles', function ($q) {
            $q->whereIn('name', ['tecnico', 'operario']);
        })->orderBy('name')->get();

        // Estadísticas
        $estadisticas = [
            'pendientes' => MantenimientoProgramado::where('estado', 'PENDIENTE')->count(),
            'realizados' => MantenimientoProgramado::where('estado', 'REALIZADO')->count(),
            'cancelados' => MantenimientoProgramado::where('estado', 'CANCELADO')->count(),
            'vencidos' => MantenimientoProgramado::vencidos()->count(),
        ];

        return view('parametros.mantenimientos.index', compact(
            'mantenimientos',
            'tecnicos',
            'estadisticas'
        ));
    }

    /**
     * Mostrar formulario para crear mantenimiento
     */
    public function create(Request $request): View
    {
        $equipoId = $request->get('equipo_id');
        $equipo = $equipoId ? Equipo::findOrFail($equipoId) : null;
        $equipos = Equipo::with('area.sede.cliente')->orderBy('codigo_interno')->get();
        $tecnicos = User::where('estado', true)->orderBy('name')->get();
        $tipos = ['MANTENIMIENTO', 'CALIBRACION'];

        return view('parametros.mantenimientos.create', compact(
            'equipo',
            'equipos',
            'tecnicos',
            'tipos'
        ));
    }

    /**
     * Guardar nuevo mantenimiento programado
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'tipo' => 'required|in:MANTENIMIENTO,CALIBRACION',
            'fecha_programada' => 'required|date|after_or_equal:today',
            'tecnico_id' => 'nullable|exists:users,id',
            'notas' => 'nullable|string|max:500',
        ]);

        $mantenimiento = MantenimientoProgramado::create($validated);

        // Si se asigna a técnico, crear servicio automáticamente
        if ($validated['tecnico_id'] ?? null) {
            $mantenimiento->asignarATecnico(
                $validated['tecnico_id'],
                "Mantenimiento programado: {$mantenimiento->tipo_etiqueta}"
            );
        }

        return redirect()
            ->route('parametros.mantenimientos.show', $mantenimiento)
            ->with('success', '✅ Mantenimiento programado creado exitosamente');
    }

    /**
     * Mostrar detalles del mantenimiento
     */
    public function show(MantenimientoProgramado $mantenimiento): View
    {
        $mantenimiento->load([
            'equipo.area.sede.cliente.empresa',
            'equipo.tipoEquipo',
            'tecnico',
            'servicio',
        ]);

        return view('parametros.mantenimientos.show', compact('mantenimiento'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(MantenimientoProgramado $mantenimiento): View
    {
        $equipos = Equipo::with('area.sede.cliente')->orderBy('codigo_interno')->get();
        $tecnicos = User::where('estado', true)->orderBy('name')->get();
        $tipos = ['MANTENIMIENTO', 'CALIBRACION'];

        return view('parametros.mantenimientos.edit', compact(
            'mantenimiento',
            'equipos',
            'tecnicos',
            'tipos'
        ));
    }

    /**
     * Actualizar mantenimiento
     */
    public function update(Request $request, MantenimientoProgramado $mantenimiento)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:MANTENIMIENTO,CALIBRACION',
            'fecha_programada' => 'required|date',
            'tecnico_id' => 'nullable|exists:users,id',
            'notas' => 'nullable|string|max:500',
        ]);

        $mantenimiento->update($validated);

        return redirect()
            ->route('parametros.mantenimientos.show', $mantenimiento)
            ->with('success', '✅ Mantenimiento actualizado correctamente');
    }

    /**
     * Marcar como realizado
     */
    public function realizarMantenimiento(Request $request, MantenimientoProgramado $mantenimiento)
    {
        $validated = $request->validate([
            'resultado' => 'required|string|max:1000',
            'fecha_realizacion' => 'required|date',
        ]);

        $mantenimiento->update([
            'estado' => 'REALIZADO',
            'fecha_realizacion' => $validated['fecha_realizacion'],
            'resultado' => $validated['resultado'],
        ]);

        // Actualizar equipo con última fecha de mantenimiento
        if ($mantenimiento->tipo === 'MANTENIMIENTO') {
            $mantenimiento->equipo->update([
                'ultimo_mantenimiento_at' => now(),
                'proximo_mantenimiento_at' => $this->calcularProximoMantenimiento(
                    $mantenimiento->equipo,
                    $validated['fecha_realizacion']
                ),
            ]);
        } else {
            $mantenimiento->equipo->update([
                'ultimo_calibracion_at' => now(),
            ]);
        }

        // Si tiene servicio asociado, marcar como resuelto
        if ($mantenimiento->servicio) {
            $mantenimiento->servicio->update([
                'estado' => 'RESUELTO',
                'fecha_resolucion' => now(),
                'descripcion_solucion' => $validated['resultado'],
            ]);
        }

        return redirect()
            ->route('parametros.mantenimientos.show', $mantenimiento)
            ->with('success', '✅ Mantenimiento marcado como realizado');
    }

    /**
     * Asignar a técnico y generar servicio
     */
    public function asignarTecnico(Request $request, MantenimientoProgramado $mantenimiento)
    {
        $validated = $request->validate([
            'tecnico_id' => 'required|exists:users,id',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $servicio = $mantenimiento->asignarATecnico(
            $validated['tecnico_id'],
            $validated['descripcion'] ?? null
        );

        return redirect()
            ->route('parametros.mantenimientos.show', $mantenimiento)
            ->with('success', '✅ Mantenimiento asignado a técnico. Servicio generado: #' . $servicio->id);
    }

    /**
     * Cancelar mantenimiento
     */
    public function cancelar(Request $request, MantenimientoProgramado $mantenimiento)
    {
        $validated = $request->validate([
            'motivo' => 'nullable|string|max:500',
        ]);

        $mantenimiento->cancelar($validated['motivo'] ?? null);

        return redirect()
            ->route('parametros.mantenimientos.show', $mantenimiento)
            ->with('success', '✅ Mantenimiento cancelado');
    }

    /**
     * Eliminar mantenimiento
     */
    public function destroy(MantenimientoProgramado $mantenimiento)
    {
        // No permitir eliminar si tiene servicio asociado
        if ($mantenimiento->servicio_id) {
            return redirect()
                ->route('parametros.mantenimientos.show', $mantenimiento)
                ->with('error', '❌ No se puede eliminar: tiene un servicio asociado');
        }

        $mantenimiento->forceDelete();

        return redirect()
            ->route('parametros.mantenimientos.index')
            ->with('success', '✅ Mantenimiento eliminado');
    }

    // ========== REPORTES ==========

    /**
     * Reporte de mantenimientos programados
     */
    public function reporteProgramados(Request $request)
    {
        $query = MantenimientoProgramado::with(['equipo.area.sede.cliente', 'tecnico'])
            ->where('estado', 'PENDIENTE');

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('fecha_programada', [
                Carbon::parse($request->desde),
                Carbon::parse($request->hasta),
            ]);
        }

        $mantenimientos = $query->orderBy('fecha_programada')->get();

        return view('parametros.mantenimientos.reportes.programados', compact('mantenimientos'));
    }

    /**
     * Reporte de mantenimientos realizados
     */
    public function reporteRealizados(Request $request)
    {
        $query = MantenimientoProgramado::with(['equipo.area.sede.cliente', 'tecnico'])
            ->where('estado', 'REALIZADO');

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('fecha_realizacion', [
                Carbon::parse($request->desde)->startOfDay(),
                Carbon::parse($request->hasta)->endOfDay(),
            ]);
        }

        $mantenimientos = $query->orderByDesc('fecha_realizacion')->get();

        return view('parametros.mantenimientos.reportes.realizados', compact('mantenimientos'));
    }

    /**
     * Reporte de mantenimientos por equipo
     */
    public function reportePorEquipo(Request $request, Equipo $equipo)
    {
        $mantenimientos = $equipo->mantenimientosProgramados()
            ->with('tecnico')
            ->orderByDesc('fecha_programada')
            ->get();

        return view('parametros.mantenimientos.reportes.por-equipo', compact(
            'equipo',
            'mantenimientos'
        ));
    }

    /**
     * Reporte de mantenimientos por técnico
     */
    public function reportePorTecnico(Request $request, User $tecnico)
    {
        $mantenimientos = MantenimientoProgramado::where('tecnico_id', $tecnico->id)
            ->with('equipo.area.sede.cliente')
            ->orderByDesc('fecha_programada')
            ->get();

        return view('parametros.mantenimientos.reportes.por-tecnico', compact(
            'tecnico',
            'mantenimientos'
        ));
    }

    // ========== HELPERS ==========

    /**
     * Calcular próxima fecha de mantenimiento
     */
    private function calcularProximoMantenimiento(Equipo $equipo, $ultimaFecha)
    {
        if (!$equipo->mantenimientos_por_ano || $equipo->mantenimientos_por_ano === 0) {
            return null;
        }

        $diasEntre = 365 / $equipo->mantenimientos_por_ano;
        return Carbon::parse($ultimaFecha)->addDays($diasEntre);
    }
}
