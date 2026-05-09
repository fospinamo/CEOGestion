@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Estadísticas y métricas de gestión')

@section('content')
<div class="space-y-6">
    <!-- Panel rápido del técnico -->
    @if(auth()->check() && auth()->user()->tipo_rol === 'tecnico')
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-8 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold mb-2">👨‍🔧 Mis Servicios Asignados</h2>
                <p class="text-green-100">Accede rápidamente a tus servicios pendientes de atender</p>
            </div>
            <a href="{{ route('incidencias.servicios.technician-panel') }}" 
                class="bg-white text-green-600 font-bold py-3 px-8 rounded-lg hover:bg-green-50 transition transform hover:scale-105">
                Ir a Mi Panel →
            </a>
        </div>
    </div>
    @endif

    <!-- Selector de Empresa -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 mr-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Seleccionar Empresa</label>
                <form method="GET" action="{{ route('home') }}" class="flex gap-3">
                    <select name="empresa_id" onchange="this.form.submit()" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Todas las empresas --</option>
                        @foreach($empresas as $emp)
                            <option value="{{ $emp->id }}" {{ $emp->id === $dashboard['empresa']?->id ? 'selected' : '' }}>
                                {{ $emp->nombre }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            @if($dashboard['empresa'])
            <div class="text-right">
                <p class="text-sm text-gray-600">Empresa Seleccionada</p>
                <p class="text-lg font-bold text-blue-600">{{ $dashboard['empresa']->nombre }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Stats Cards Principales -->
    @if($dashboard['empresa'])
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Clientes -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow p-6 border-l-4 border-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Clientes</p>
                    <p class="text-4xl font-bold text-blue-600">{{ $dashboard['clientes'] }}</p>
                </div>
                <div class="text-5xl text-blue-600 opacity-20">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Contratos Vigentes -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow p-6 border-l-4 border-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Contratos Vigentes</p>
                    <p class="text-4xl font-bold text-green-600">{{ $dashboard['contratos_vigentes'] }}</p>
                </div>
                <div class="text-5xl text-green-600 opacity-20">
                    <i class="fas fa-file-contract"></i>
                </div>
            </div>
        </div>

        <!-- Equipos Totales -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg shadow p-6 border-l-4 border-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Equipos Totales</p>
                    <p class="text-4xl font-bold text-purple-600">{{ $dashboard['equipos_totales'] }}</p>
                </div>
                <div class="text-5xl text-purple-600 opacity-20">
                    <i class="fas fa-laptop"></i>
                </div>
            </div>
        </div>

        <!-- Incidencias Totales -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg shadow p-6 border-l-4 border-orange-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Incidencias Totales</p>
                    <p class="text-4xl font-bold text-orange-600">{{ $dashboard['incidencias_totales'] }}</p>
                </div>
                <div class="text-5xl text-orange-600 opacity-20">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Valor de Contratos -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Valor Total -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
            <h3 class="text-lg font-bold text-gray-800 mb-4">💰 Valor Total de Contratos</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-gray-600 text-sm">Valor Total</p>
                    <p class="text-3xl font-bold text-blue-600">
                        ${{ number_format($dashboard['valor_total_contratos'], 2, ',', '.') }}
                    </p>
                </div>
                <div class="border-t pt-4">
                    <p class="text-gray-600 text-sm">Pago Mensual (÷ 12)</p>
                    <p class="text-2xl font-bold text-green-600">
                        ${{ number_format($dashboard['pago_mensual'], 2, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Desglose por Moneda (si hay contratos) -->
        @if($dashboard['contratos_vigentes'] > 0)
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-green-500">
            <h3 class="text-lg font-bold text-gray-800 mb-4">📊 Resumen de Contratos</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <span class="text-gray-700 font-semibold">Total de Contratos</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $dashboard['contratos_vigentes'] }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                    <span class="text-gray-700 font-semibold">Promedio por Contrato</span>
                    <span class="text-xl font-bold text-purple-600">
                        ${{ number_format($dashboard['valor_total_contratos'] / $dashboard['contratos_vigentes'], 2, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Incidencias por Estado -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Por Estado -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">📈 Incidencias por Estado</h3>
            @if(count($dashboard['incidencias_por_estado']) > 0)
            <div class="space-y-3">
                @php
                    $colores = [
                        'PENDIENTE' => 'bg-yellow-50 text-yellow-700 border-yellow-300',
                        'EN_PROCESO' => 'bg-blue-50 text-blue-700 border-blue-300',
                        'RESUELTO' => 'bg-green-50 text-green-700 border-green-300',
                        'CERRADO' => 'bg-gray-50 text-gray-700 border-gray-300',
                        'CANCELADO' => 'bg-red-50 text-red-700 border-red-300',
                    ];
                    $iconos = [
                        'PENDIENTE' => 'fa-clock',
                        'EN_PROCESO' => 'fa-spinner',
                        'RESUELTO' => 'fa-check-circle',
                        'CERRADO' => 'fa-times-circle',
                        'CANCELADO' => 'fa-ban',
                    ];
                @endphp
                @foreach($dashboard['incidencias_por_estado'] as $estado => $cantidad)
                <div class="flex items-center justify-between p-3 border rounded-lg {{ $colores[$estado] ?? 'bg-gray-50' }}">
                    <div class="flex items-center gap-2">
                        <i class="fas {{ $iconos[$estado] ?? 'fa-info' }}"></i>
                        <span class="font-semibold">{{ $estado }}</span>
                    </div>
                    <span class="text-2xl font-bold">{{ $cantidad }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-4">No hay incidencias registradas</p>
            @endif
        </div>

        <!-- Por Año -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">📅 Incidencias por Año</h3>
            @if(count($dashboard['incidencias_por_año']) > 0)
            <div class="space-y-3">
                @php
                    $años = collect($dashboard['incidencias_por_año'])->sortKeysDesc();
                @endphp
                @foreach($años as $año => $cantidad)
                <div class="flex items-center justify-between p-3 bg-indigo-50 border border-indigo-300 rounded-lg">
                    <span class="font-semibold text-gray-700">Año {{ $año }}</span>
                    <span class="text-2xl font-bold text-indigo-600">{{ $cantidad }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-4">No hay incidencias registradas</p>
            @endif
        </div>
    </div>

    <!-- Incidencias por Mes (Gráfico) -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6">📊 Incidencias por Mes (Últimos 12 Meses)</h3>
        @if(count($dashboard['incidencias_por_mes']) > 0)
        <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-12 gap-2">
            @php
                $maxIncidencias = max($dashboard['incidencias_por_mes']);
                $mesesNombres = [
                    '01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr',
                    '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago',
                    '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'
                ];
            @endphp
            @foreach($dashboard['incidencias_por_mes'] as $mes => $cantidad)
            @php
                $nombreMes = $mesesNombres[substr($mes, 5, 2)];
                $porcentaje = $maxIncidencias > 0 ? ($cantidad / $maxIncidencias) * 100 : 0;
            @endphp
            <div class="flex flex-col items-center">
                <div class="w-full bg-gray-200 rounded-t-lg overflow-hidden" style="height: 150px;">
                    <div class="bg-blue-500 w-full rounded-t-lg transition-all hover:bg-blue-600" 
                        style="height: {{ $porcentaje }}%; position: relative;">
                        @if($cantidad > 0)
                        <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 text-xs font-bold text-blue-600 bg-white px-2 py-1 rounded">
                            {{ $cantidad }}
                        </span>
                        @endif
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-700 mt-2">{{ $nombreMes }}</p>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-8">No hay incidencias en los últimos 12 meses</p>
        @endif
    </div>

    @else
    <!-- Cuando no hay empresa seleccionada -->
    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-8 text-center">
        <i class="fas fa-info-circle text-blue-600 text-4xl mb-4"></i>
        <h3 class="text-xl font-bold text-blue-900 mb-2">Selecciona una Empresa</h3>
        <p class="text-blue-800">Para ver las estadísticas detalladas, selecciona una empresa del menú desplegable arriba.</p>
    </div>
    @endif

    <!-- Quick Links -->
    @if($dashboard['empresa'])
    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Gestión Rápida</h3>
            <div class="space-y-2">
                <a href="{{ route('parametros.contratos.index') }}" class="block px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition flex items-center gap-2">
                    <i class="fas fa-file-contract"></i> Ver Contratos
                </a>
                <a href="{{ route('parametros.clientes.index') }}" class="block px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition flex items-center gap-2">
                    <i class="fas fa-users"></i> Ver Clientes
                </a>
                <a href="{{ route('parametros.equipos.index') }}" class="block px-4 py-2 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition flex items-center gap-2">
                    <i class="fas fa-laptop"></i> Ver Equipos
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Accesos Directos</h3>
            <div class="space-y-2">
                <a href="{{ route('incidencias.servicios.index') }}" class="block px-4 py-2 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition flex items-center gap-2 justify-between">
                    <span>Ver Incidencias</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="{{ route('parametros.empresas.index') }}" class="block px-4 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition flex items-center gap-2 justify-between">
                    <span>Gestionar Empresas</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
