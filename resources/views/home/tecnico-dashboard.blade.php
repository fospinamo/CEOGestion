@extends('layouts.app')

@section('title', 'Mi Dashboard - ' . auth()->user()->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">👨‍💼 Mi Dashboard de Servicios</h1>
        <p class="text-gray-600">Bienvenido <strong>{{ $tecnico->name }}</strong>, aquí están tus servicios asignados</p>
    </div>

    <!-- Tarjetas de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <!-- Total de Servicios -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-semibold">Total Asignados</p>
                    <p class="text-4xl font-bold">{{ $dashboard['servicios_totales'] }}</p>
                </div>
                <i class="fas fa-briefcase text-4xl opacity-20"></i>
            </div>
        </div>

        <!-- Servicios Pendientes -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-semibold">Pendientes</p>
                    <p class="text-4xl font-bold">{{ $dashboard['servicios_pendientes'] }}</p>
                </div>
                <i class="fas fa-clock text-4xl opacity-20"></i>
            </div>
        </div>

        <!-- En Proceso -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-semibold">En Proceso</p>
                    <p class="text-4xl font-bold">{{ $dashboard['servicios_en_proceso'] }}</p>
                </div>
                <i class="fas fa-wrench text-4xl opacity-20"></i>
            </div>
        </div>

        <!-- Pendientes Repuesto -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-semibold">Pendientes Repuesto</p>
                    <p class="text-4xl font-bold">{{ $dashboard['servicios_pendientes_repuesto'] }}</p>
                </div>
                <i class="fas fa-box text-4xl opacity-20"></i>
            </div>
        </div>

        <!-- Completados -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-semibold">Completados</p>
                    <p class="text-4xl font-bold">{{ $dashboard['servicios_completados'] }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-20"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Lista de Servicios Pendientes -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="bg-yellow-500 text-white px-6 py-4">
                    <h2 class="text-xl font-bold flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Mis Servicios Pendientes
                    </h2>
                </div>

                <div class="divide-y">
                    @if($servicios->where('estado', 'PENDIENTE')->count() > 0)
                        @foreach($servicios->where('estado', 'PENDIENTE') as $servicio)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-bold text-gray-900">Servicio #{{ $servicio->id }}</p>
                                        <p class="text-sm text-gray-600">
                                            📍 {{ $servicio->equipo?->area?->sede?->cliente->razon_social ?? 'Sin cliente' }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                                        🟡 {{ $servicio->prioridad }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 mb-2">{{ substr($servicio->descripcion_problema, 0, 50) }}...</p>
                                <div class="flex justify-between items-center">
                                    <p class="text-xs text-gray-500">
                                        📅 {{ $servicio->fecha_solicitud->format('d/m/Y H:i') }}
                                    </p>
                                    <a href="{{ route('incidencias.servicios.show', $servicio) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                        Ver Detalles →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-6 text-center text-gray-500">
                            <p>✅ ¡Excelente! No hay servicios pendientes</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Servicios en Proceso -->
            @if($servicios->where('estado', 'EN_PROCESO')->count() > 0)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden mt-6">
                    <div class="bg-orange-500 text-white px-6 py-4">
                        <h2 class="text-xl font-bold flex items-center">
                            <i class="fas fa-spinner mr-2"></i>
                            En Proceso ({{ $servicios->where('estado', 'EN_PROCESO')->count() }})
                        </h2>
                    </div>

                    <div class="divide-y">
                        @foreach($servicios->where('estado', 'EN_PROCESO') as $servicio)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-gray-900">Servicio #{{ $servicio->id }}</p>
                                        <p class="text-sm text-gray-600">{{ $servicio->equipo?->area?->sede?->cliente->razon_social ?? 'Sin cliente' }}</p>
                                    </div>
                                    <a href="{{ route('incidencias.servicios.show', $servicio) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                        Continuar →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Información del Técnico -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">👤 Mi Información</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Nombre</p>
                        <p class="font-semibold text-gray-900">{{ $tecnico->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $tecnico->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Teléfono</p>
                        <p class="font-semibold text-gray-900">{{ $tecnico->telefono ?? 'No registrado' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Rol</p>
                        <p class="font-semibold text-gray-900">{{ $tecnico->role?->name ?? 'Sin rol' }}</p>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">⚡ Acciones Rápidas</h3>
                <div class="space-y-2">
                    <a href="{{ route('incidencias.servicios.mi-panel') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded text-center transition">
                        Ver Mi Panel
                    </a>
                    <a href="{{ route('incidencias.servicios.index') }}" class="block w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded text-center transition">
                        Ver Todos los Servicios
                    </a>
                </div>
            </div>

            <!-- Distribución de Prioridades -->
            @if($dashboard['servicios_por_prioridad'])
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Por Prioridad</h3>
                    <div class="space-y-2">
                        @foreach($dashboard['servicios_por_prioridad'] as $prioridad => $cantidad)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ ucfirst($prioridad) }}</span>
                                <span class="font-bold text-lg text-gray-900">{{ $cantidad }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
