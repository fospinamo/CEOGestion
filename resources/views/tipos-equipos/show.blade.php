@extends('layouts.app')
@section('title', 'Tipo - ' . $tipoEquipo->nombre)
@section('page-title', 'Detalle de Tipo')
@section('page-description', $tipoEquipo->categoria)
@section('content')
<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Tipo</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-600">Nombre:</span> <p class="font-semibold">{{ $tipoEquipo->nombre }}</p></div>
                <div><span class="text-gray-600">Categoría:</span> <p class="font-semibold">{{ $tipoEquipo->categoria }}</p></div>
                @if($tipoEquipo->icono)
                <div><span class="text-gray-600">Ícono:</span> <p class="font-semibold"><i class="fas {{ $tipoEquipo->icono }}"></i> {{ $tipoEquipo->icono }}</p></div>
                @endif
            </div>
        </div>

        @if($tipoEquipo->descripcion)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Descripción</h3>
            <p class="text-gray-700 text-sm leading-relaxed">{{ $tipoEquipo->descripcion }}</p>
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Acciones</h3>
            <div class="space-y-2">
                <a href="{{ route('tipos-equipos.edit', $tipoEquipo) }}" class="block px-4 py-2 bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200 transition text-center text-sm font-semibold">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <form action="{{ route('tipos-equipos.destroy', $tipoEquipo) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-100 text-red-800 rounded hover:bg-red-200 transition text-sm font-semibold">
                        <i class="fas fa-trash mr-2"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 text-sm">
            <h3 class="font-semibold text-gray-900 mb-3">Equipos</h3>
            <div class="space-y-2 text-gray-600">
                <p><strong>Total:</strong> {{ $tipoEquipo->equipos_count }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
