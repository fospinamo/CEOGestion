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
            <p class="text-gray-700 text-sm leading-relaxed">{{ $servicio->diagnostico ?? 'Pendiente' }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Solución Aplicada</h3>
            <p class="text-gray-700 text-sm leading-relaxed">{{ $servicio->solucion_aplicada ?? 'Pendiente' }}</p>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Acciones</h3>
            <div class="space-y-2">
                <a href="{{ route('servicios.edit', $servicio) }}" class="block px-4 py-2 bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200 transition text-center text-sm font-semibold">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-100 text-red-800 rounded hover:bg-red-200 transition text-sm font-semibold">
                        <i class="fas fa-trash mr-2"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 text-sm">
            <h3 class="font-semibold text-gray-900 mb-3">Detalles</h3>
            <div class="space-y-2 text-gray-600">
                <p><strong>Horas:</strong> {{ $servicio->horas_trabajadas ?? 'N/A' }}</p>
                @if($servicio->calificacion_cliente)
                    <p><strong>Calificación:</strong> {{ str_repeat('⭐', $servicio->calificacion_cliente) }}</p>
                @endif
                <p><strong>Cédula Técnico:</strong> {{ $servicio->cedula_tecnico ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
