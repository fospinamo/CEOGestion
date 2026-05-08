@extends('layouts.app')

@section('title', 'Ver Categoría')
@section('page-title', 'Detalles de la Categoría')
@section('page-description', $categoria->nombre)

@section('content')
<div class="max-w-2xl">
    <!-- Información de la Categoría -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                @if($categoria->icono)
                    <div class="text-4xl" style="color: {{ $categoria->color }};">
                        <i class="fas {{ $categoria->icono }}"></i>
                    </div>
                @else
                    <div class="text-4xl text-gray-400">
                        <i class="fas fa-tag"></i>
                    </div>
                @endif
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $categoria->nombre }}</h3>
                    <p class="text-sm text-gray-500">{{ $categoria->slug }}</p>
                </div>
            </div>
            <div>
                @if($categoria->estado)
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Activa</span>
                @else
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Inactiva</span>
                @endif
            </div>
        </div>

        @if($categoria->descripcion)
            <div class="border-t pt-6">
                <p class="text-gray-700">{{ $categoria->descripcion }}</p>
            </div>
        @endif
    </div>

    <!-- Propiedades -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Color -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Color</h4>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded border-2 border-gray-300" style="background-color: {{ $categoria->color }};"></div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $categoria->color }}</p>
                    <p class="text-xs text-gray-500">Código hexadecimal</p>
                </div>
            </div>
        </div>

        <!-- Ícono -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Ícono</h4>
            @if($categoria->icono)
                <div class="flex items-center gap-3">
                    <div class="text-3xl" style="color: {{ $categoria->color }};">
                        <i class="fas {{ $categoria->icono }}"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $categoria->icono }}</p>
                        <p class="text-xs text-gray-500">Font Awesome</p>
                    </div>
                </div>
            @else
                <p class="text-gray-400">Sin ícono asignado</p>
            @endif
        </div>
    </div>

    <!-- Tipos de Equipos -->
    @if($categoria->tiposEquipos()->count() > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Tipos de Equipos ({{ $categoria->cantidadTipos() }})</h4>
        <div class="space-y-2">
            @foreach($categoria->tiposEquipos as $tipo)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <a href="{{ route('parametros.tipos-equipos.show', $tipo) }}" class="font-semibold text-blue-600 hover:text-blue-900">{{ $tipo->nombre }}</a>
                    <span class="text-xs text-gray-500">{{ $tipo->equipos_count }} equipos</span>
                </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <p class="text-sm text-blue-700">
            <i class="fas fa-info-circle mr-2"></i>
            No hay tipos de equipos asociados a esta categoría
        </p>
    </div>
    @endif

    <!-- Fechas -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Información de Registro</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Creado</p>
                <p class="text-gray-900">{{ $categoria->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Última actualización</p>
                <p class="text-gray-900">{{ $categoria->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="flex gap-3 flex-wrap">
        <a href="{{ route('parametros.categorias.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <a href="{{ route('parametros.categorias.edit', $categoria) }}" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg transition flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-edit"></i> Editar
        </a>
        @if($categoria->tiposEquipos()->count() == 0)
            <form action="{{ route('parametros.categorias.destroy', $categoria) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta categoría?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </form>
        @else
            <button disabled class="px-4 py-2 bg-gray-400 text-white font-semibold rounded-lg opacity-50 cursor-not-allowed flex items-center gap-2 whitespace-nowrap" title="No se puede eliminar una categoría con tipos de equipos asociados">
                <i class="fas fa-trash"></i> Eliminar (No permitido)
            </button>
        @endif
    </div>
</div>
@endsection
