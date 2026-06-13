@extends('layouts.app')
@section('title', 'Equipo - ' . $equipo->codigo_interno)
@section('page-title', 'Detalle de Equipo')
@section('page-description', $equipo->marca . ' ' . $equipo->modelo)
@section('content')
<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información General</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-600">Código:</span> <p class="font-semibold">{{ $equipo->codigo_interno }}</p></div>
                <div><span class="text-gray-600">Serial:</span> <p class="font-semibold">{{ $equipo->serial }}</p></div>
                <div><span class="text-gray-600">Marca:</span> <p class="font-semibold">{{ $equipo->marca }}</p></div>
                <div><span class="text-gray-600">Modelo:</span> <p class="font-semibold">{{ $equipo->modelo }}</p></div>
                <div><span class="text-gray-600">Tipo:</span> <p class="font-semibold">{{ $equipo->tipoEquipo->nombre }}</p></div>
                <div><span class="text-gray-600">Estado:</span> <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ $equipo->estado_operativo }}</span></div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ubicación</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-600">Área:</span> <p class="font-semibold">{{ $equipo->area->nombre }}</p></div>
                <div><span class="text-gray-600">Sede:</span> <p class="font-semibold">{{ $equipo->area->sede->nombre }}</p></div>
            </div>
        </div>

        @if($equipo->contrato)
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-file-contract text-green-600 mr-2"></i>Contrato Asociado
                </h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Número:</span>
                        <p class="font-semibold">{{ $equipo->contrato->numero_contrato }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Tipo:</span>
                        <p class="font-semibold">{{ $equipo->contrato->tipo_contrato }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Cliente:</span>
                        <p class="font-semibold">{{ $equipo->contrato->cliente->razon_social }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Estado:</span>
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $equipo->contrato->estado }}
                        </span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-600">Vigencia:</span>
                        <p class="font-semibold">{{ $equipo->contrato->fecha_inicio->format('d/m/Y') }} - {{ $equipo->contrato->fecha_fin->format('d/m/Y') }}</p>
                    </div>
                </div>
                <a href="{{ route('parametros.contratos.show', $equipo->contrato) }}" class="inline-block mt-4 text-blue-600 hover:text-blue-900 font-semibold text-sm">
                    <i class="fas fa-arrow-right mr-1"></i>Ver contrato completo
                </a>
            </div>
        @else
            <div class="bg-blue-50 rounded-lg shadow p-6 border-l-4 border-blue-500">
                <p class="text-blue-700 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Este equipo no tiene un contrato asociado. 
                    <a href="{{ route('parametros.equipos.edit', $equipo) }}" class="font-semibold hover:underline">Agregar contrato</a>
                </p>
            </div>
        @endif

        @if($equipo->descripcion)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Descripción</h3>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $equipo->descripcion }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Especificaciones Técnicas</h3>
            @if($equipo->especificaciones_tecnicas)
                <div class="bg-gray-50 p-4 rounded font-mono text-xs overflow-auto">
                    @foreach($equipo->especificaciones_tecnicas as $key => $value)
                        <div class="mb-2">{{ $key }}: {{ $value }}</div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Sin especificaciones</p>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-900">Acciones</h3>
            </div>
            <div class="space-y-2">
                <a href="{{ route('parametros.equipos.edit', $equipo) }}" class="block px-4 py-2 bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200 transition text-center text-sm font-semibold">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <form action="{{ route('parametros.equipos.destroy', $equipo) }}" method="POST" onsubmit="return confirm('¿Eliminar equipo?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-100 text-red-800 rounded hover:bg-red-200 transition text-sm font-semibold">
                        <i class="fas fa-trash mr-2"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 text-sm">
            <h3 class="font-semibold text-gray-900 mb-3">Información Adicional</h3>
            <div class="space-y-2 text-gray-600">
                @if($equipo->ip_asignada)
                    <p><strong>IP:</strong> {{ $equipo->ip_asignada }}</p>
                @endif
                @if($equipo->mac_address)
                    <p><strong>MAC:</strong> {{ $equipo->mac_address }}</p>
                @endif
                @if($equipo->usuario_asignado)
                    <p><strong>Asignado a:</strong> {{ $equipo->usuario_asignado }}</p>
                @endif
                @if($equipo->valor_compra)
                    <p><strong>Valor:</strong> $ {{ number_format($equipo->valor_compra, 2) }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
