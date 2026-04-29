@extends('layouts.app')
@section('title', 'Tipo - ' . $tipoEquipo->nombre)
@section('page-title', $tipoEquipo->nombre)
@section('page-description', $tipoEquipo->categoriaObj ? $tipoEquipo->categoriaObj->nombre : 'Sin categoría')
@section('content')
<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 space-y-6">
        <!-- Información Básica -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Información del Tipo</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Nombre</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $tipoEquipo->nombre }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Categoría</p>
                    @if($tipoEquipo->categoriaObj)
                        <div class="flex items-center gap-2 mt-1">
                            @if($tipoEquipo->categoriaObj->icono)
                                <i class="fas {{ $tipoEquipo->categoriaObj->icono }} text-lg" style="color: {{ $tipoEquipo->categoriaObj->color }};"></i>
                            @endif
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold" 
                                style="background-color: {{ $tipoEquipo->categoriaObj->color }}20; color: {{ $tipoEquipo->categoriaObj->color }};">
                                {{ $tipoEquipo->categoriaObj->nombre }}
                            </span>
                        </div>
                    @else
                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">Sin categoría</span>
                    @endif
                </div>
                @if($tipoEquipo->icono)
                    <div>
                        <p class="text-sm text-gray-600">Ícono</p>
                        <p class="mt-1"><i class="fas {{ $tipoEquipo->icono }} text-lg mr-2"></i><code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $tipoEquipo->icono }}</code></p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Descripción -->
        @if($tipoEquipo->descripcion)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Descripción</h3>
                <p class="text-gray-700 leading-relaxed">{{ $tipoEquipo->descripcion }}</p>
            </div>
        @endif

        <!-- Equipos Asociados -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                Equipos Asociados
                <span class="ml-2 inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full font-semibold">
                    {{ $tipoEquipo->equipos()->count() }}
                </span>
            </h3>
            @if($tipoEquipo->equipos->count() > 0)
                <div class="space-y-2">
                    @foreach($tipoEquipo->equipos->take(5) as $equipo)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $equipo->codigo_interno }}</p>
                                <p class="text-xs text-gray-500">Estado: <span class="font-semibold">{{ $equipo->estado }}</span></p>
                            </div>
                            <a href="{{ route('parametros.equipos.show', $equipo) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    @endforeach
                    @if($tipoEquipo->equipos()->count() > 5)
                        <p class="text-center text-sm text-gray-500 pt-2">
                            + {{ $tipoEquipo->equipos()->count() - 5 }} más...
                        </p>
                    @endif
                </div>
            @else
                <p class="text-center text-gray-500 py-4">
                    <i class="fas fa-inbox text-2xl opacity-50"></i>
                    <p>No hay equipos con este tipo</p>
                </p>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Acciones -->
        <div class="bg-white rounded-lg shadow p-6 space-y-2">
            <a href="{{ route('parametros.tipos-equipos.edit', $tipoEquipo) }}" class="w-full block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                <i class="fas fa-edit"></i> Editar
            </a>
            @if($tipoEquipo->equipos()->count() === 0)
                <form action="{{ route('parametros.tipos-equipos.destroy', $tipoEquipo) }}" method="POST" onsubmit="return confirm('¿Eliminar este tipo de equipo?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-semibold">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </form>
            @endif
        </div>

        <!-- Información del Sistema -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-gray-900 mb-4">Información del Sistema</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-600">Creado</p>
                    <p class="font-semibold text-gray-900">{{ $tipoEquipo->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Última actualización</p>
                    <p class="font-semibold text-gray-900">{{ $tipoEquipo->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        @if($tipoEquipo->equipos()->count() > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-700">
                    <i class="fas fa-info-circle"></i>
                    Este tipo de equipo tiene {{ $tipoEquipo->equipos()->count() }} equipo(s) asociado(s) y no se puede eliminar.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
