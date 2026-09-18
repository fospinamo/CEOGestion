@extends('portal.cliente.layout')

@section('title', 'Detalle del Servicio #' . $servicio->id . ' - Portal del Cliente')

@section('content')
    <a href="{{ route('portal.servicios') }}" class="text-blue-500 no-underline mb-5 inline-block hover:underline">
        <i class="fas fa-arrow-left"></i> Volver a Servicios
    </a>

    <h1 class="text-2xl font-bold mb-6">
        <i class="fas fa-tools text-blue-500 mr-2"></i>
        Servicio #{{ $servicio->id }}
    </h1>

    <!-- Información General -->
    <x-card class="mb-5">
        <h2 class="text-lg font-semibold mb-4">Información General</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-5">
            <div>
                <p class="text-xs text-gray-500 font-semibold">Estado:</p>
                @php
                    $estadoMap = ['REPORTADO' => 'blue', 'EN_ESPERA_ASIGNACION' => 'yellow', 'EN_PROCESO' => 'yellow', 'RESUELTO' => 'green', 'CERRADO' => 'gray'];
                @endphp
                <div class="mt-2">
                    <x-badge :variant="$estadoMap[$servicio->estado] ?? 'gray'" size="sm">{{ str_replace('_', ' ', $servicio->estado) }}</x-badge>
                </div>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Tipo de Servicio:</p>
                <p class="text-base text-gray-900 mt-2">{{ $servicio->tipo_servicio }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Prioridad:</p>
                @php
                    $prioridadMap = ['BAJA' => 'green', 'MEDIA' => 'yellow', 'ALTA' => 'red', 'CRITICA' => 'red'];
                @endphp
                <div class="mt-2">
                    <x-badge :variant="$prioridadMap[$servicio->prioridad] ?? 'gray'" size="sm">{{ $servicio->prioridad }}</x-badge>
                </div>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Fecha de Reporte:</p>
                <p class="text-base text-gray-900 mt-2">{{ $servicio->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </x-card>

    <!-- Equipo Afectado -->
    <x-card class="mb-5">
        <h2 class="text-lg font-semibold mb-4">Equipo Afectado</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div>
                <p class="text-xs text-gray-500 font-semibold">Código Interno:</p>
                <p class="text-base text-gray-900 mt-2">{{ $servicio->equipo->codigo_activo_cliente }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Tipo de Equipo:</p>
                <p class="text-base text-gray-900 mt-2">{{ $servicio->equipo->tipo->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Ubicación:</p>
                <p class="text-base text-gray-900 mt-2">{{ $servicio->equipo->ubicacion ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Serial/Modelo:</p>
                <p class="text-base text-gray-900 mt-2">{{ $servicio->equipo->numero_serie ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Área:</p>
                <p class="text-base text-gray-900 mt-2">{{ $servicio->equipo->area->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Sede:</p>
                <p class="text-base text-gray-900 mt-2">{{ $servicio->equipo->area->sede->nombre ?? 'N/A' }}</p>
            </div>
        </div>
    </x-card>

    <!-- Descripción del Problema -->
    <x-card class="mb-5">
        <h2 class="text-lg font-semibold mb-4">Descripción del Problema</h2>
        <div class="bg-gray-50 p-4 rounded-md border-l-[3px] border-blue-500">
            {{ $servicio->descripcion_problema }}
        </div>
    </x-card>

    <!-- Información de Contacto -->
    <x-card class="mb-5">
        <h2 class="text-lg font-semibold mb-4">Información de Contacto</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div>
                <p class="text-xs text-gray-500 font-semibold">Reportado por:</p>
                <p class="text-base text-gray-900 mt-2">{{ $servicio->reportado_por }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Teléfono:</p>
                <p class="text-base mt-2">
                    <a href="tel:{{ $servicio->telefono_contacto }}" class="text-blue-500 no-underline hover:underline">{{ $servicio->telefono_contacto }}</a>
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Email:</p>
                <p class="text-base mt-2">
                    <a href="mailto:{{ $servicio->email_contacto }}" class="text-blue-500 no-underline hover:underline">{{ $servicio->email_contacto }}</a>
                </p>
            </div>
        </div>
    </x-card>

    <!-- SLA y Técnico -->
    <x-card class="mb-5">
        <h2 class="text-lg font-semibold mb-4">SLA y Asignación</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div>
                <p class="text-xs text-gray-500 font-semibold">SLA Respuesta:</p>
                <p class="text-base text-gray-900 mt-2">{{ $servicio->sla_horas_respuesta }} horas</p>
                @if($servicio->fecha_limite_respuesta)
                    <small class="text-gray-500 block mt-1">Límite: {{ $servicio->fecha_limite_respuesta->format('d/m/Y H:i') }}</small>
                @endif
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">SLA Solución:</p>
                <p class="text-base text-gray-900 mt-2">{{ $servicio->sla_horas_solucion }} horas</p>
                @if($servicio->fecha_limite_solucion)
                    <small class="text-gray-500 block mt-1">Límite: {{ $servicio->fecha_limite_solucion->format('d/m/Y H:i') }}</small>
                @endif
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold">Técnico Asignado:</p>
                <p class="text-base text-gray-900 mt-2">{{ $servicio->tecnicoAsignado?->name ?? 'Sin asignar' }}</p>
            </div>
        </div>
    </x-card>

    <!-- Historial de Seguimiento -->
    @if($servicio->seguimientos->isNotEmpty())
        <x-card class="mb-5">
            <h2 class="text-lg font-semibold mb-4">Historial de Seguimiento</h2>

            <div class="relative py-5">
                @foreach($servicio->seguimientos as $seguimiento)
                    <div class="flex mb-5">
                        <div class="w-[30px] h-[30px] bg-blue-500 rounded-full flex items-center justify-center text-white shrink-0 mr-4">
                            <i class="fas fa-check text-sm"></i>
                        </div>

                        <div class="flex-1">
                            <div class="font-semibold text-gray-900">
                                {{ $seguimiento->accion }}
                                @if($seguimiento->usuario)
                                    <span class="text-gray-500 font-normal text-xs">por {{ $seguimiento->usuario->name }}</span>
                                @endif
                            </div>

                            @if($seguimiento->observacion)
                                <div class="text-gray-500 text-sm mt-1">{{ $seguimiento->observacion }}</div>
                            @endif

                            @if($seguimiento->estado_anterior && $seguimiento->estado_nuevo)
                                <div class="text-gray-500 text-xs mt-1">{{ $seguimiento->estado_anterior }} → {{ $seguimiento->estado_nuevo }}</div>
                            @endif

                            <div class="text-gray-400 text-xs mt-2">{{ $seguimiento->created_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>
    @endif

    <!-- Botones de Acción -->
    <div class="flex gap-3 mt-6">
        <x-button href="{{ route('portal.servicios') }}">
            <i class="fas fa-arrow-left"></i> Volver
        </x-button>

        @if($servicio->estado === 'CERRADO' || $servicio->estado === 'RESUELTO')
            <x-button href="{{ route('portal.servicios.descargar', $servicio->id) }}" variant="success">
                <i class="fas fa-download"></i> Descargar PDF
            </x-button>
        @endif
    </div>
@endsection
