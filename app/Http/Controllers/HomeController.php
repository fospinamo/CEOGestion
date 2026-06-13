<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Equipo;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * HomeController - Dashboard Principal
 * 
 * Maneja la pantalla principal del sistema con estadísticas
 * filtradas por empresa seleccionada.
 */
class HomeController extends Controller
{
    /**
     * Mostrar dashboard principal o dashboard de técnico
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        // Si el usuario es técnico, mostrar su dashboard específico
        if ($user && $user->hasRole('tecnico')) {
            return $this->dashboardTecnico();
        }
        
        // Para otros roles, mostrar dashboard normal
        // Obtener empresas para selector
        $empresas = Empresa::orderBy('nombre')->get();
        
        // Obtener empresa seleccionada (por defecto la primera)
        $empresaId = $request->get('empresa_id', $empresas->first()?->id);
        $empresa = Empresa::find($empresaId);
        
        // Inicializar datos
        $dashboard = [
            'empresa' => $empresa,
            'clientes' => 0,
            'contratos_vigentes' => 0,
            'valor_total_contratos' => 0,
            'pago_mensual' => 0,
            'equipos_totales' => 0,
            'incidencias_totales' => 0,
            'incidencias_por_mes' => [],
            'incidencias_por_estado' => [],
            'incidencias_por_año' => [],
        ];
        
        if ($empresa) {
            // 1. Cantidad de clientes
            $dashboard['clientes'] = Cliente::where('empresa_id', $empresa->id)->count();
            
            // 2. Contratos vigentes y valor - OPTIMIZADO: usar count() y sum() directamente en queries
            $dashboard['contratos_vigentes'] = Contrato::whereHas('cliente', function ($query) use ($empresa) {
                $query->where('empresa_id', $empresa->id);
            })
            ->where('estado', 'ACTIVO')
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->count();  // ← Usar count() en lugar de get()->count()
            
            $dashboard['valor_total_contratos'] = Contrato::whereHas('cliente', function ($query) use ($empresa) {
                $query->where('empresa_id', $empresa->id);
            })
            ->where('estado', 'ACTIVO')
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->sum('valor_contrato');  // ← Usar sum() en lugar de get()->sum()
            
            $dashboard['pago_mensual'] = $dashboard['valor_total_contratos'] > 0 ? $dashboard['valor_total_contratos'] / 12 : 0;
            
            // 3. Cantidad de equipos
            $dashboard['equipos_totales'] = Equipo::whereHas('cliente', function ($query) use ($empresa) {
                $query->where('empresa_id', $empresa->id);
            })->count();
            
            // 4. Incidencias (Servicios) totales
            $dashboard['incidencias_totales'] = Servicio::whereHas('equipo.cliente', function ($query) use ($empresa) {
                $query->where('empresa_id', $empresa->id);
            })->count();  // ← Usar count() directamente sin get()
            
            // Solo traer servicios si hay incidencias para hacer estadísticas
            if ($dashboard['incidencias_totales'] > 0) {
                $servicios = Servicio::whereHas('equipo.cliente', function ($query) use ($empresa) {
                    $query->where('empresa_id', $empresa->id);
                })->get();
                
                // 5. Incidencias por mes (últimos 12 meses)
                $dashboard['incidencias_por_mes'] = $this->getIncidenciasPorMes($servicios);
                
                // 6. Incidencias por estado
                $dashboard['incidencias_por_estado'] = $servicios->groupBy('estado')
                    ->map(function ($items) {
                        return $items->count();
                    })
                    ->toArray();
                
                // 7. Incidencias por año
                $dashboard['incidencias_por_año'] = $servicios->groupBy(function ($item) {
                    return $item->created_at->year;
                })
                ->map(function ($items) {
                    return $items->count();
                })
                ->toArray();
            }
        }
        
        return view('home', compact('dashboard', 'empresas', 'empresa'));
    }
    
    /**
     * Dashboard específico para técnicos
     * Muestra solo los servicios asignados al técnico actual - OPTIMIZADO
     */
    private function dashboardTecnico(): View
    {
        $tecnico = auth()->user();
        
        // Estadísticas - primero contar sin traer todos los registros
        $dashboard = [
            'tecnico' => $tecnico,
            'servicios_totales' => Servicio::where('tecnico_id', $tecnico->id)->count(),
            'servicios_pendientes' => Servicio::where('tecnico_id', $tecnico->id)->where('estado', 'PENDIENTE')->count(),
            'servicios_en_proceso' => Servicio::where('tecnico_id', $tecnico->id)->where('estado', 'EN_PROCESO')->count(),
            'servicios_resueltos' => Servicio::where('tecnico_id', $tecnico->id)->where('estado', 'RESUELTO')->count(),
            'servicios_cerrados' => Servicio::where('tecnico_id', $tecnico->id)->where('estado', 'CERRADO')->count(),
            'servicios_completados' => Servicio::where('tecnico_id', $tecnico->id)->whereIn('estado', ['RESUELTO', 'CERRADO'])->count(),
            'servicios_pendientes_repuesto' => Servicio::where('tecnico_id', $tecnico->id)->where('estado', 'PENDIENTE_REPUESTO')->count(),
        ];
        
        // Solo traer servicios si hay datos (para gráficos y listados)
        $servicios = Servicio::where('tecnico_id', $tecnico->id)
            ->with(['equipo.area.sede.cliente', 'estadoServicio'])
            ->get();
        
        if ($servicios->count() > 0) {
            $dashboard['servicios_por_mes'] = $this->getIncidenciasPorMes($servicios);
            $dashboard['servicios_por_prioridad'] = $servicios->groupBy('prioridad')
                ->map(function ($items) {
                    return $items->count();
                })
                ->toArray();
        } else {
            $dashboard['servicios_por_mes'] = [];
            $dashboard['servicios_por_prioridad'] = [];
        }
        
        return view('home.tecnico-dashboard', compact('dashboard', 'servicios', 'tecnico'));
    }
    
    /**
     * Obtener incidencias por mes (últimos 12 meses)
     */
    private function getIncidenciasPorMes($servicios)
    {
        $meses = [];
        
        // Crear array de últimos 12 meses
        for ($i = 11; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $key = $fecha->format('Y-m');
            $meses[$key] = 0;
        }
        
        // Contar incidencias por mes
        foreach ($servicios as $servicio) {
            $key = $servicio->created_at->format('Y-m');
            if (isset($meses[$key])) {
                $meses[$key]++;
            }
        }
        
        return $meses;
    }
}
