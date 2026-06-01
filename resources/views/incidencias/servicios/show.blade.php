@extends('layouts.app')
@section('title', 'Servicio - ' . $servicio->id)
@section('page-title', 'Detalle del Servicio')
@section('page-description', 'Información de atención TI')
@section('content')
<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Servicio</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-600">Equipo:</span> <p class="font-semibold">{{ $servicio->equipo->codigo_interno }}</p></div>
                <div><span class="text-gray-600">Tipo:</span> <p class="font-semibold">{{ $servicio->tipo_servicio }}</p></div>
                <div><span class="text-gray-600">Prioridad:</span> <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">{{ $servicio->prioridad }}</span></div>
                <div><span class="text-gray-600">Estado:</span> <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ $servicio->estado }}</span></div>
                <div><span class="text-gray-600">Solicitado por:</span> <p class="font-semibold">{{ $servicio->solicitado_por }}</p></div>
                <div><span class="text-gray-600">Técnico:</span> <p class="font-semibold">{{ $servicio->tecnico_asignado }}</p></div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Descripción del Problema</h3>
            <p class="text-gray-700 text-sm leading-relaxed">{{ $servicio->descripcion_problema }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Diagnóstico</h3>
            @php
                $diagnosticoTecnico = $servicio->diagnostico_validacion
                    ?: ($servicio->descripcion_atencion
                        ?: $servicio->diagnostico);
            @endphp
            <p class="text-gray-700 text-sm leading-relaxed">{{ $diagnosticoTecnico ?: 'Pendiente' }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Archivos Adjuntos</h3>

            @if($servicio->documentosAdjuntos->isEmpty())
                <p class="text-sm text-gray-500">Este servicio no tiene archivos adjuntos.</p>
            @else
                <div class="space-y-4">
                    @foreach($servicio->documentosAdjuntos as $doc)
                        @php
                            $esImagen = str_starts_with((string) ($doc->mime_type ?? ''), 'image/');
                        @endphp
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $doc->nombre_archivo }}</p>
                                    <p class="text-xs text-gray-500">{{ $doc->mime_type ?? 'Archivo' }} - {{ number_format(($doc->tamaño_bytes ?? 0) / 1024, 1) }} KB</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('incidencias.servicios.documento.ver', ['servicio' => $servicio->id, 'documento' => $doc->id]) }}" target="_blank" class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded text-xs font-semibold">Ver</a>
                                    <a href="{{ route('incidencias.servicios.documento.descargar', ['servicio' => $servicio->id, 'documento' => $doc->id]) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded text-xs font-semibold">Descargar</a>
                                </div>
                            </div>

                            @if($esImagen)
                                <div class="border rounded bg-gray-50 p-2">
                                    <img src="{{ route('incidencias.servicios.documento.ver', ['servicio' => $servicio->id, 'documento' => $doc->id]) }}" alt="{{ $doc->nombre_archivo }}" class="max-h-56 w-auto rounded mx-auto">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <div class="space-y-6">
        <!-- Panel de Estado y Acciones Principales -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-900 mb-4">🎯 Estado del Servicio</h3>
            <div class="mb-4">
                @if($servicio->estadoServicio)
                    <span class="inline-block px-4 py-2 text-lg font-bold rounded-full" 
                        style="background-color: {{ $servicio->estadoServicio->color }}40; color: {{ $servicio->estadoServicio->color }};">
                        ● {{ $servicio->estadoServicio->nombre }}
                    </span>
                @else
                    <span class="inline-block px-4 py-2 text-lg font-bold rounded-full bg-gray-200 text-gray-800;">
                        ● {{ $servicio->estado }}
                    </span>
                @endif
            </div>
            
            @if($servicio->tecnicoResponsable)
                <p class="text-sm mb-2"><strong>Técnico Asignado:</strong> <span class="text-green-600">{{ $servicio->tecnicoResponsable->name }}</span></p>
            @else
                <p class="text-sm text-red-600"><strong>⚠️ Sin técnico asignado</strong></p>
            @endif
        </div>

        <!-- Panel de Acciones -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Acciones</h3>
            <div class="space-y-2">
                {{-- Asignar Técnico - PROMINENTE --}}
                @if(!$servicio->tecnico_id)
                    <a href="{{ route('incidencias.servicios.assign', $servicio) }}" class="block w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded font-bold transition text-center">
                        <i class="fas fa-user-plus mr-2"></i> Asignar Técnico
                    </a>
                @else
                    <a href="{{ route('incidencias.servicios.assign', $servicio) }}" class="block w-full px-4 py-2 bg-green-100 hover:bg-green-200 text-green-800 rounded font-semibold transition text-center text-sm">
                        <i class="fas fa-sync-alt mr-2"></i> Reasignar Técnico
                    </a>
                @endif

                {{-- Informe Técnico --}}
                @if($servicio->tecnico_id && !$servicio->descripcion_atencion)
                    <a href="{{ route('incidencias.servicios.report', $servicio) }}" class="block w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded font-semibold transition text-center">
                        <i class="fas fa-clipboard-list mr-2"></i> Informe Técnico
                    </a>
                @elseif($servicio->descripcion_atencion || $servicio->persona_receptora_nombre)
                    <div class="px-4 py-2 bg-purple-100 text-purple-800 rounded text-sm text-center font-semibold mb-2">
                        <i class="fas fa-check-circle mr-2"></i> Informe Completado
                    </div>
                    <!-- Botones para descargar/ver PDF -->
                    <a href="{{ route('incidencias.servicios.download-informe-pdf', $servicio) }}" class="block w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded font-semibold transition text-center text-sm">
                        <i class="fas fa-file-pdf mr-2"></i> Descargar PDF
                    </a>
                    <a href="{{ route('incidencias.servicios.view-informe-pdf', $servicio) }}" target="_blank" class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-semibold transition text-center text-sm">
                        <i class="fas fa-print mr-2"></i> Ver/Imprimir
                    </a>
                @endif

                {{-- Editar --}}
                @if($servicio->estado === 'CERRADO')
                    <button type="button" class="block w-full px-4 py-2 bg-gray-100 text-gray-400 rounded font-semibold text-center cursor-not-allowed" disabled>
                        <i class="fas fa-edit mr-2"></i> Editar (Inactivo)
                    </button>
                @else
                    <a href="{{ route('incidencias.servicios.edit', $servicio) }}" class="block w-full px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded font-semibold transition text-center">
                        <i class="fas fa-edit mr-2"></i> Editar
                    </a>
                @endif

                {{-- Eliminar --}}
                @if($servicio->estado === 'CERRADO')
                    <button type="button" class="w-full px-4 py-2 bg-gray-100 text-gray-400 rounded font-semibold transition text-sm cursor-not-allowed" disabled>
                        <i class="fas fa-trash mr-2"></i> Eliminar (Inactivo)
                    </button>
                @else
                    <form action="{{ route('incidencias.servicios.destroy', $servicio) }}" method="POST" onsubmit="return confirm('¿Eliminar este servicio?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-100 hover:bg-red-200 text-red-800 rounded font-semibold transition text-sm">
                            <i class="fas fa-trash mr-2"></i> Eliminar
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Panel de Detalles -->
        <div class="bg-white rounded-lg shadow p-6 text-sm">
            <h3 class="font-semibold text-gray-900 mb-3">📊 Detalles</h3>
            <div class="space-y-2 text-gray-600">
                <p><strong>Fecha Solicitud:</strong> {{ $servicio->fecha_solicitud->format('d/m/Y H:i') }}</p>
                
                @if($servicio->fecha_asignacion)
                    <p><strong>Fecha Asignación:</strong> {{ $servicio->fecha_asignacion->format('d/m/Y H:i') }}</p>
                @endif
                
                @if($servicio->horas_trabajadas)
                    <p><strong>Horas Trabajadas:</strong> {{ $servicio->horas_trabajadas }}</p>
                @endif

                @if($servicio->calificacion_cliente)
                    <p><strong>Calificación:</strong> {{ str_repeat('⭐', $servicio->calificacion_cliente) }} ({{ $servicio->calificacion_cliente }}/5)</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@if(session('whatsapp_url'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const whatsappUrl = @json(session('whatsapp_url'));
            if (whatsappUrl) {
                window.open(whatsappUrl, '_blank', 'noopener,noreferrer');
            }
        });
    </script>
@endif
