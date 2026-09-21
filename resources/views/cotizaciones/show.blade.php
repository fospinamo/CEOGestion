@extends('layouts.app')

@section('title', 'Ver Cotización')
@section('page-title', 'Detalles de la Cotización')
@section('page-description', $cotizacion->numero_cotizacion)

@section('content')
<div class="max-w-4xl">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        {{-- Resumen --}}
        <div class="bg-white rounded-lg shadow p-6 md:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Información de la Cotización</h3>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">N° Cotización</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $cotizacion->numero_cotizacion }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Cliente</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $cotizacion->cliente->razon_social ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Fecha Cotización</p>
                        <p class="text-sm text-gray-900">{{ $cotizacion->fecha_cotizacion?->format('d/m/Y') ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Vencimiento</p>
                        <p class="text-sm text-gray-900">{{ $cotizacion->fecha_vencimiento?->format('d/m/Y') ?? 'Sin vencimiento' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Tipo de Servicio</p>
                        <p class="text-sm text-gray-900">{{ str_replace('_', ' ', $cotizacion->tipo_servicio) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Moneda</p>
                        <p class="text-sm text-gray-900">{{ $cotizacion->moneda }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Estado y Valores --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Estado y Valores</h3>
            <div class="space-y-4">
                @php
                    $estadoColors = [
                        'BORRADOR' => 'gray',
                        'ENVIADA' => 'blue',
                        'APROBADA' => 'green',
                        'RECHAZADA' => 'red',
                        'VENCIDA' => 'yellow',
                    ];
                    $color = $estadoColors[$cotizacion->estado] ?? 'gray';
                @endphp
                <div class="text-center">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Estado</p>
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                        {{ $cotizacion->estado }}
                    </span>
                </div>
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Subtotal:</span>
                        <span class="text-sm font-semibold">${{ number_format($cotizacion->valor_subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">IVA:</span>
                        <span class="text-sm font-semibold">${{ number_format($cotizacion->valor_iva, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-sm font-bold text-gray-900">Total:</span>
                        <span class="text-sm font-bold text-blue-600">${{ number_format($cotizacion->valor_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Descripción --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Descripción del Servicio</h3>
        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $cotizacion->descripcion_servicio ?? 'Sin descripción' }}</p>
    </div>

    {{-- Observaciones --}}
    @if($cotizacion->observaciones)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Observaciones</h3>
        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $cotizacion->observaciones }}</p>
    </div>
    @endif

    {{-- Auditoría --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Auditoría</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Creado por:</p>
                <p class="font-semibold">{{ $cotizacion->creadoPor->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Fecha creación:</p>
                <p class="font-semibold">{{ $cotizacion->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Modificado por:</p>
                <p class="font-semibold">{{ $cotizacion->modificadoPor->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Última modificación:</p>
                <p class="font-semibold">{{ $cotizacion->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    {{-- Acciones --}}
    <div class="flex justify-end gap-3">
        <a href="{{ route('cotizaciones.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        @can('cotizaciones.editar')
        <a href="{{ route('cotizaciones.edit', $cotizacion) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition">
            <i class="fas fa-edit"></i> Editar
        </a>
        @endcan
    </div>
</div>
@endsection
