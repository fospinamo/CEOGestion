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
     * Mostrar dashboard principal
     */
    public function index(Request $request): View
    {
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
            
            // 2. Contratos vigentes y valor
            $contratos = Contrato::whereHas('cliente', function ($query) use ($empresa) {
                $query->where('empresa_id', $empresa->id);
            })
            ->where('estado', 'ACTIVO')
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->get();
            
            $dashboard['contratos_vigentes'] = $contratos->count();
            $dashboard['valor_total_contratos'] = $contratos->sum('valor_contrato');
            $dashboard['pago_mensual'] = $dashboard['valor_total_contratos'] / 12;
            
            // 3. Cantidad de equipos
            $dashboard['equipos_totales'] = Equipo::whereHas('cliente', function ($query) use ($empresa) {
                $query->where('empresa_id', $empresa->id);
            })->count();
            
            // 4. Incidencias (Servicios) totales
            $servicios = Servicio::whereHas('equipo.cliente', function ($query) use ($empresa) {
                $query->where('empresa_id', $empresa->id);
            })->get();
            
            $dashboard['incidencias_totales'] = $servicios->count();
            
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
        
        return view('home', compact('dashboard', 'empresas', 'empresa'));
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
