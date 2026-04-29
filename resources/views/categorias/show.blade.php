@extends('layouts.app')

@section('title', $categoria->nombre)
@section('page-title', $categoria->nombre)
@section('page-description', 'Información detallada de la categoría')

@section('content')
<div class="grid grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="col-span-2 space-y-6">
        <!-- Basic Info Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                @if($categoria->icono)
                    <i class="fas {{ $categoria->icono }}" style="color: {{ $categoria->color }};"></i>
                @endif
                <span>Información Básica</span>
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Nombre</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $categoria->nombre }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Slug</p>
                    <code class="inline-block bg-gray-100 px-3 py-1 rounded text-sm">{{ $categoria->slug }}</code>
                </div>
                @if($categoria->descripcion)
                    <div>
                        <p class="text-sm text-gray-600">Descripción</p>
                        <p class="text-gray-700">{{ $categoria->descripcion }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Icono y Color -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Apariencia</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-2">Icono</p>
                    <div class="flex items-center justify-center w-24 h-24 border-2 border-gray-200 rounded-lg">
                        @if($categoria->icono)
                            <i class="fas {{ $categoria->icono }} text-4xl" style="color: {{ $categoria->color }};"></i>
                        @else
                            <span class="text-gray-400">Sin icono</span>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-2">Color</p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-center w-24 h-24 border-4 rounded-lg" style="background-color: {{ $categoria->color }}; border-color: {{ $categoria->color }};"></div>
                        <code class="block text-center text-sm font-mono">{{ $categoria->color }}</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tipos Asociados -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                Tipos de Equipos Asociados
                <span class="ml-2 inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full font-semibold">
                    {{ $categoria->cantidadTipos() }}
                </span>
            </h3>
            @if($categoria->tiposEquipos->count() > 0)
                <div class="space-y-2">
                    @foreach($categoria->tiposEquipos as $tipo)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $tipo->nombre }}</p>
                                <p class="text-sm text-gray-500">{{ $tipo->descripcion ?? 'Sin descripción' }}</p>
                            </div>
                            <a href="{{ route('tipos-equipos.show', $tipo) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-4 bg-gray-50 rounded-lg text-center text-gray-500">
                    <i class="fas fa-inbox text-2xl mb-2 opacity-50"></i>
                    <p>No hay tipos de equipos con esta categoría</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Estado Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Estado</h3>
            <div class="flex items-center justify-center">
                @if($categoria->estado)
                    <div class="text-center">
                        <div class="inline-block p-4 bg-green-100 rounded-full">
                            <i class="fas fa-check text-2xl text-green-600"></i>
                        </div>
                        <p class="mt-2 text-lg font-semibold text-green-600">Activa</p>
                    </div>
                @else
                    <div class="text-center">
                        <div class="inline-block p-4 bg-red-100 rounded-full">
                            <i class="fas fa-times text-2xl text-red-600"></i>
                        </div>
                        <p class="mt-2 text-lg font-semibold text-red-600">Inactiva</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Timestamps -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Información del Sistema</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-600">Creada el</p>
                    <p class="font-semibold text-gray-900">{{ $categoria->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Última actualización</p>
                    <p class="font-semibold text-gray-900">{{ $categoria->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="bg-white rounded-lg shadow p-6 space-y-2">
            <a href="{{ route('categorias.edit', $categoria) }}" class="w-full block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                <i class="fas fa-edit"></i> Editar
            </a>
            @if($categoria->cantidadTipos() === 0)
                <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" onsubmit="return confirm('¿Eliminar esta categoría?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-semibold">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </form>
            @endif
        </div>

        <!-- Info Card -->
        @if($categoria->cantidadTipos() > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-700">
                    <i class="fas fa-info-circle"></i>
                    Esta categoría no se puede eliminar porque tiene tipos de equipos asociados.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
