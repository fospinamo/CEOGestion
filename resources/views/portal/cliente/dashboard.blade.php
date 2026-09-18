@extends('portal.cliente.layout')

@section('title', 'Dashboard - Portal del Cliente')

@section('content')
    <h1 class="text-2xl font-bold mb-6">
        <i class="fas fa-chart-line text-blue-500 mr-2"></i>
        Dashboard
    </h1>

    <!-- Información del Cliente -->
    <x-card class="mb-6">
        <h2 class="text-lg font-semibold mb-4">Información del Cliente</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div>
                <p class="text-xs text-gray-500 font-semibold">Razón Social:</p>
                <p class="text-base text-gray-900 mt-1">{{ $cliente->razon_social }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Documento:</p>
                <p class="text-base text-gray-900 mt-1">{{ $cliente->tipo_documento }}: {{ $cliente->documento }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Email Principal:</p>
                <p class="text-base text-gray-900 mt-1">{{ $cliente->email_principal }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Teléfono:</p>
                <p class="text-base text-gray-900 mt-1">{{ $cliente->telefono_movil ?? $cliente->telefono_fijo ?? 'N/A' }}</p>
            </div>
        </div>
    </x-card>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 text-white rounded-lg p-5 text-center">
            <div class="text-[28px] font-bold mb-1"><i class="fas fa-file-contract"></i> {{ $contratos }}</div>
            <div class="text-sm opacity-90">Contratos Activos</div>
        </div>
        <div class="bg-gradient-to-br from-sky-500 to-sky-700 text-white rounded-lg p-5 text-center">
            <div class="text-[28px] font-bold mb-1"><i class="fas fa-server"></i> {{ $equipos }}</div>
            <div class="text-sm opacity-90">Equipos</div>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-amber-700 text-white rounded-lg p-5 text-center">
            <div class="text-[28px] font-bold mb-1"><i class="fas fa-tools"></i> {{ $servicios_recientes }}</div>
            <div class="text-sm opacity-90">Servicios (últimos 30 días)</div>
        </div>
        <div class="bg-gradient-to-br from-violet-500 to-violet-700 text-white rounded-lg p-5 text-center">
            <div class="text-[28px] font-bold mb-1"><i class="fas fa-chart-bar"></i> {{ array_sum($servicios_por_estado->toArray()) }}</div>
            <div class="text-sm opacity-90">Total de Servicios</div>
        </div>
    </div>

    <!-- Estado de Servicios Detallado -->
    <x-card>
        <h2 class="text-lg font-semibold mb-4">Estado de Servicios Activos</h2>

        @if($servicios_por_estado->isEmpty())
            <p class="text-gray-500 text-center py-5">No hay servicios registrados</p>
        @else
            @php
                $estados = [
                    'REPORTADO' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-800', 'label' => 'Reportado'],
                    'EN_ESPERA_ASIGNACION' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'label' => 'En Espera'],
                    'EN_PROCESO' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'En Proceso'],
                    'RESUELTO' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Resuelto'],
                    'CERRADO' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Cerrado'],
                ];
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($estados as $estado => $config)
                    @if(isset($servicios_por_estado[$estado]))
                        <div class="{{ $config['bg'] }} {{ $config['text'] }} p-4 rounded-md text-center">
                            <div class="text-2xl font-bold">{{ $servicios_por_estado[$estado] }}</div>
                            <div class="text-xs mt-1">{{ $config['label'] }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </x-card>

    <!-- Acciones Rápidas -->
    <x-card class="mt-6">
        <h2 class="text-lg font-semibold mb-4">Acciones Rápidas</h2>
        <div class="flex gap-3 flex-wrap">
            <x-button href="{{ route('portal.contratos') }}">
                <i class="fas fa-file-contract"></i> Ver Contratos
            </x-button>
            <x-button href="{{ route('portal.equipos') }}">
                <i class="fas fa-server"></i> Ver Equipos
            </x-button>
            <x-button href="{{ route('portal.servicios') }}">
                <i class="fas fa-tools"></i> Ver Servicios
            </x-button>
        </div>
    </x-card>
@endsection
