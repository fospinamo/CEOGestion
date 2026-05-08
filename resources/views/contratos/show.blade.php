@extends('layouts.app')

@section('title', 'Ver Contrato')
@section('page-title', 'Detalles del Contrato')
@section('page-description', $contrato->numero_contrato)

@section('content')
<div class="max-w-4xl">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Resumen -->
        <div class="bg-white rounded-lg shadow p-6 md:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Información del Contrato</h3>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Número de Contrato</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $contrato->numero_contrato }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Cliente</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $contrato->cliente->razon_social }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Tipo</p>
                        <p class="text-lg font-semibold text-gray-900">{{ str_replace('_', ' ', $contrato->tipo_contrato) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Modalidad</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $contrato->modalidad }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Estado</h3>
            
            <div class="space-y-4">
                @php
                    $estatusColors = [
                        'BORRADOR' => 'gray',
                        'ACTIVO' => 'green',
                        'VENCIDO' => 'red',
                        'TERMINADO' => 'yellow',
                        'RENOVADO' => 'blue'
                    ];
                    $color = $estatusColors[$contrato->estado] ?? 'gray';
                @endphp
                <div class="text-center">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Estado</p>
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                        {{ $contrato->estado }}
                    </span>
                </div>
                
                <div class="border-t pt-4">
                    <label class="flex items-center gap-2">
                        <i class="fas {{ $contrato->documento_firmado ? 'fa-check-circle text-green-600' : 'fa-times-circle text-red-600' }}"></i>
                        <span class="text-sm font-semibold text-gray-700">Documento {{ $contrato->documento_firmado ? 'Firmado' : 'No Firmado' }}</span>
                    </label>
                </div>
                
                <div class="border-t pt-4">
                    <label class="flex items-center gap-2">
                        <i class="fas {{ $contrato->renovacion_automatica ? 'fa-check-circle text-green-600' : 'fa-times-circle text-red-600' }}"></i>
                        <span class="text-sm font-semibold text-gray-700">{{ $contrato->renovacion_automatica ? 'Renovación Automática' : 'Sin Renovación Automática' }}</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Fechas y Valores -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Fechas y Valores</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Fecha Inicio</p>
                <p class="text-lg font-semibold text-gray-900">{{ $contrato->fecha_inicio->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Fecha Fin</p>
                <p class="text-lg font-semibold text-gray-900">{{ $contrato->fecha_fin->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Valor</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $contrato->moneda === 'COP' ? '$' : ($contrato->moneda === 'USD' ? 'US$' : '€') }} 
                    {{ number_format($contrato->valor_contrato, 0, ',', '.') }}
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Moneda</p>
                <p class="text-lg font-semibold text-gray-900">{{ $contrato->moneda }}</p>
            </div>
        </div>
    </div>

    <!-- Detalles -->
    <div class="space-y-6">
        @if($contrato->alcance_servicios)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Alcance de Servicios</h3>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $contrato->alcance_servicios }}</p>
        </div>
        @endif

        @if($contrato->condiciones_pago)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Condiciones de Pago</h3>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $contrato->condiciones_pago }}</p>
        </div>
        @endif

        @if($contrato->clausulas_especiales)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Cláusulas Especiales</h3>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $contrato->clausulas_especiales }}</p>
        </div>
        @endif
    </div>

    <!-- Botones de Acción -->
    <div class="flex gap-3 mt-6 flex-wrap">
        <a href="{{ route('parametros.contratos.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <a href="{{ route('parametros.contratos.edit', $contrato) }}" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg transition flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-edit"></i> Editar
        </a>
        <form action="{{ route('parametros.contratos.destroy', $contrato) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este contrato?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </form>
    </div>
</div>
@endsection
