@extends('layouts.app')

@section('page-title', 'Estadísticas de Servicios')
@section('page-description', 'Panel de análisis y estadísticas de servicios técnicos')

@section('content')
<div class="space-y-6">
    <!-- Título -->
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Estadísticas de Servicios</h1>
        <p class="text-gray-600 mt-2">Análisis general del desempeño de servicios técnicos</p>
    </div>

    <!-- KPIs (Key Performance Indicators) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total de Servicios -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Total Servicios</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalServicios }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-tools text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Servicios Completados -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Completados</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $serviciosCompletados }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Tasa de Resolución -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Tasa Resolución</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $tasaResolucion }}%</p>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <i class="fas fa-chart-pie text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Técnicos Activos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Técnicos Activos</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2">{{ $serviciosPorTecnico->count() }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-4">
                    <i class="fas fa-users text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Servicios por Estado -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Servicios por Estado</h3>
            <div class="space-y-3">
                @forelse($serviciosPorEstado as $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                @if($item->estadoServicio?->nombre === 'Completado')
                                    bg-green-100 text-green-800
                                @elseif($item->estadoServicio?->nombre === 'En Progreso')
                                    bg-blue-100 text-blue-800
                                @elseif($item->estadoServicio?->nombre === 'Pendiente')
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif
                            ">
                                {{ $item->estadoServicio?->nombre ?? 'Sin estado' }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-gray-800">{{ $item->cantidad }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No hay datos disponibles</p>
                @endforelse
            </div>
        </div>

        <!-- Top Técnicos (últimos 30 días) -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Top Técnicos (últimos 30 días)</h3>
            <div class="space-y-3">
                @forelse($serviciosPorTecnico->sortByDesc('cantidad')->take(5) as $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($item->tecnicoResponsable?->name) }}&background=0D8ABC&color=fff" 
                                 alt="Avatar" class="w-8 h-8 rounded-full">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $item->tecnicoResponsable?->name ?? 'Técnico desconocido' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                {{ $item->cantidad }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No hay datos disponibles</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Clientes Principales -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Clientes con más Servicios</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">#</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Cliente</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Servicios</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($serviciosPorCliente as $index => $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 font-semibold">
                                {{ $item->equipo?->cliente?->razon_social ?? 'Cliente desconocido' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                    {{ $item->cantidad }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" 
                                         style="width: {{ $totalServicios > 0 ? round(($item->cantidad / $totalServicios) * 100) : 0 }}%">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-600 mt-1">
                                    {{ $totalServicios > 0 ? round(($item->cantidad / $totalServicios) * 100, 2) : 0 }}%
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                No hay datos disponibles
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-600 text-xl mt-1"></i>
            <div>
                <h4 class="font-semibold text-blue-900">Información de Estadísticas</h4>
                <p class="text-sm text-blue-800 mt-1">
                    Los datos mostrados incluyen todos los servicios registrados en el sistema. 
                    La tasa de resolución se calcula como el porcentaje de servicios completados respecto al total. 
                    El ranking de técnicos considera solo los últimos 30 días.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Aquí se pueden agregar gráficos con Chart.js si es necesario
    console.log('Página de estadísticas cargada');
</script>
@endsection
