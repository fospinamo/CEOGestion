@extends('layouts.app')

@section('title', 'Panel del Técnico')
@section('page-title', 'Mis Servicios Asignados')
@section('page-description', 'Servicios pendientes de atender')

@section('content')
<!-- 
    Vista Responsive: Mis Servicios para Técnico
    - Optimizada para móvil (320px+)
    - Responsive en tablet (768px)
    - Layout completo en desktop (1024px+)
-->
<div class="w-full max-w-7xl mx-auto px-3 sm:px-4 md:px-6 py-4 sm:py-6 md:py-8">
    <!-- Encabezado Responsive -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">👨‍🔧 Mi Panel de Servicios</h1>
        <p class="text-sm sm:text-base text-gray-600">Servicios asignados a ti</p>
    </div>

    <!-- Resumen de Servicios - Responsive Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 md:gap-4 mb-8">
        <!-- Pendientes -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-3 sm:p-4 md:p-5 rounded">
            <p class="text-xs sm:text-sm text-gray-600 font-medium">Pendientes</p>
            <p class="text-xl sm:text-2xl md:text-3xl font-bold text-blue-600 mt-1 sm:mt-2">{{ $serviciosPendientes->count() }}</p>
        </div>
        
        <!-- En Proceso -->
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 sm:p-4 md:p-5 rounded">
            <p class="text-xs sm:text-sm text-gray-600 font-medium">En Proceso</p>
            <p class="text-xl sm:text-2xl md:text-3xl font-bold text-yellow-600 mt-1 sm:mt-2">{{ $serviciosEnProceso->count() }}</p>
        </div>
        
        <!-- Pendiente Repuesto -->
        <div class="bg-purple-50 border-l-4 border-purple-500 p-3 sm:p-4 md:p-5 rounded">
            <p class="text-xs sm:text-sm text-gray-600 font-medium">Repuestos</p>
            <p class="text-xl sm:text-2xl md:text-3xl font-bold text-purple-600 mt-1 sm:mt-2">{{ $serviciosPendienteRepuesto->count() }}</p>
        </div>
        
        <!-- Completados -->
        <div class="bg-green-50 border-l-4 border-green-500 p-3 sm:p-4 md:p-5 rounded">
            <p class="text-xs sm:text-sm text-gray-600 font-medium">Completados</p>
            <p class="text-xl sm:text-2xl md:text-3xl font-bold text-green-600 mt-1 sm:mt-2">{{ $serviciosCompletados->count() }}</p>
        </div>
    </div>

    @if($serviciosPendientes->count() > 0 || $serviciosEnProceso->count() > 0 || $serviciosPendienteRepuesto->count() > 0)
        <!-- Servicios Pendientes de Atender -->
        @if($serviciosPendientes->count() > 0)
        <div class="mb-8 sm:mb-12">
            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4">⏳ Pendientes de Atender</h2>
            <div class="space-y-3 sm:space-y-4">
                @foreach($serviciosPendientes as $servicio)
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition p-4 sm:p-5 md:p-6">
                        <!-- Encabezado Tarjeta -->
                        <div class="flex justify-between items-start gap-2 mb-3 sm:mb-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">
                                    #{{ $servicio->id }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-600 truncate">
                                    {{ $servicio->equipo->codigo_interno }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ $servicio->equipo->area->nombre }}
                                </p>
                            </div>
                            <span class="px-2 sm:px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap">
                                {{ $servicio->prioridad }}
                            </span>
                        </div>

                        <!-- Info Grid Responsive -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3 mb-4">
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500">Tipo</p>
                                <p class="text-xs sm:text-sm font-semibold truncate">{{ $servicio->tipo_servicio }}</p>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500">Problema</p>
                                <p class="text-xs sm:text-sm font-semibold truncate">{{ substr($servicio->descripcion_problema, 0, 20) }}{{ strlen($servicio->descripcion_problema) > 20 ? '...' : '' }}</p>
                            </div>
                            <div class="min-w-0 sm:col-span-1 md:col-span-1">
                                <p class="text-xs text-gray-500">Solicitante</p>
                                <p class="text-xs sm:text-sm font-semibold truncate">{{ substr($servicio->solicitado_por, 0, 15) }}</p>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500">Asignación</p>
                                <p class="text-xs sm:text-sm font-semibold">{{ $servicio->fecha_asignacion->format('d/m') }}</p>
                            </div>
                        </div>

                        <!-- Botones Responsive -->
                        <div class="flex gap-2 flex-col sm:flex-row">
                            <a href="{{ route('servicios.show', $servicio) }}" 
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-3 sm:px-4 rounded-lg transition text-center text-sm sm:text-base">
                                👁️ Detalles
                            </a>
                            <a href="{{ route('servicios.report-technician', $servicio) }}" 
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-3 sm:px-4 rounded-lg transition text-center text-sm sm:text-base">
                                ✍️ Informe
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Servicios en Proceso -->
        @if($serviciosEnProceso->count() > 0)
        <div class="mb-8 sm:mb-12">
            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4">🔧 En Proceso</h2>
            <div class="space-y-3 sm:space-y-4">
                @foreach($serviciosEnProceso as $servicio)
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition p-4 sm:p-5 md:p-6 border-l-4 border-yellow-400">
                        <div class="flex justify-between items-start gap-2 mb-3 sm:mb-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">
                                    #{{ $servicio->id }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-600 truncate">
                                    {{ $servicio->equipo->codigo_interno }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ $servicio->equipo->area->nombre }}
                                </p>
                            </div>
                            <span class="px-2 sm:px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap">
                                En Proceso
                            </span>
                        </div>
                        <div class="flex gap-2 flex-col sm:flex-row">
                            <a href="{{ route('servicios.show', $servicio) }}" 
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-3 sm:px-4 rounded-lg transition text-center text-sm sm:text-base">
                                👁️ Detalles
                            </a>
                            <a href="{{ route('servicios.report-technician', $servicio) }}" 
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-3 sm:px-4 rounded-lg transition text-center text-sm sm:text-base">
                                ✍️ Actualizar
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Servicios Pendiente de Repuesto -->
        @if($serviciosPendienteRepuesto->count() > 0)
        <div class="mb-8 sm:mb-12">
            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4">📦 Pendientes de Repuesto</h2>
            <div class="space-y-3 sm:space-y-4">
                @foreach($serviciosPendienteRepuesto as $servicio)
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition p-4 sm:p-5 md:p-6 border-l-4 border-purple-400">
                        <div class="flex justify-between items-start gap-2 mb-3 sm:mb-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">
                                    #{{ $servicio->id }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-600 truncate">
                                    {{ $servicio->equipo->codigo_interno }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ $servicio->equipo->area->nombre }}
                                </p>
                            </div>
                            <span class="px-2 sm:px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap">
                                Repuesto
                            </span>
                        </div>
                        <a href="{{ route('servicios.show', $servicio) }}" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-3 sm:px-4 rounded-lg transition text-center text-sm sm:text-base">
                            👁️ Ver Detalles
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    @else
        <!-- Sin Servicios -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 sm:p-8 md:p-12 text-center">
            <p class="text-base sm:text-lg md:text-xl text-gray-600">✅ ¡No tienes servicios pendientes!</p>
            <p class="text-sm sm:text-base text-gray-500 mt-2">Todos tus servicios han sido completados</p>
        </div>
    @endif

    <!-- Histórico de Servicios Completados - Responsive -->
    @if($serviciosCompletados->count() > 0)
    <div class="mt-8 sm:mt-12 pt-6 sm:pt-8 border-t border-gray-200">
        <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4">✅ Servicios Completados</h2>
        <div class="space-y-3 sm:space-y-4">
            @foreach($serviciosCompletados->take(5) as $servicio)
                <div class="bg-green-50 rounded-lg p-4 sm:p-5 border-l-4 border-green-400">
                    <div class="flex justify-between items-start gap-3 mb-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm sm:text-base font-semibold text-gray-900 truncate">#{{ $servicio->id }} - {{ $servicio->equipo->codigo_interno }}</p>
                            <p class="text-xs text-gray-600">Completado: {{ $servicio->fecha_firma?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2 flex-col sm:flex-row">
                        <a href="{{ route('servicios.show', $servicio) }}" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3 rounded-lg transition text-center text-sm">
                            👁️ Detalles
                        </a>
                        @if($servicio->persona_receptora_nombre)
                            <a href="{{ route('servicios.download-informe-pdf', $servicio) }}" 
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-3 rounded-lg transition text-center text-sm">
                                📄 Descargar PDF
                            </a>
                            <a href="{{ route('servicios.view-informe-pdf', $servicio) }}" target="_blank"
                                class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-3 rounded-lg transition text-center text-sm">
                                🖨️ Ver PDF
                            </a>
                        @else
                            <span class="flex-1 bg-gray-300 text-gray-700 font-semibold py-2 px-3 rounded-lg text-center text-sm">
                                ⏳ Sin Informe
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
