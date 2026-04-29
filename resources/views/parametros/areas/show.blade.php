@extends('layouts.app')
@section('title', 'Área - ' . $area->nombre)
@section('page-title', 'Detalle de Área')
@section('page-description', $area->sede->nombre)
@section('content')
<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Área</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-600">Nombre:</span> <p class="font-semibold">{{ $area->nombre }}</p></div>
                <div><span class="text-gray-600">Sede:</span> <p class="font-semibold">{{ $area->sede->nombre }}</p></div>
                <div><span class="text-gray-600">Responsable:</span> <p class="font-semibold">{{ $area->responsable_nombre ?? 'N/A' }}</p></div>
                <div><span class="text-gray-600">Nivel de Riesgo:</span> <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">{{ $area->nivel_riesgo }}</span></div>
            </div>
        </div>

        @if($area->descripcion)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Descripción</h3>
            <p class="text-gray-700 text-sm leading-relaxed">{{ $area->descripcion }}</p>
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Acciones</h3>
            <div class="space-y-2">
                <a href="{{ route('parametros.areas.edit', $area) }}" class="block px-4 py-2 bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200 transition text-center text-sm font-semibold">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <form action="{{ route('parametros.areas.destroy', $area) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
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
                <p><strong>Estado:</strong> {{ $area->estado ? 'Activo' : 'Inactivo' }}</p>
                @if($area->responsable_contacto)
                    <p><strong>Contacto:</strong> {{ $area->responsable_contacto }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
