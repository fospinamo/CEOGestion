@extends('layouts.app')

@section('title', 'Editar Categoría')
@section('page-title', 'Editar Categoría')
@section('page-description', 'Actualiza los datos de la categoría')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('categorias.update', $categoria) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nombre <span class="text-red-600">*</span>
                </label>
                <input type="text" name="nombre" value="{{ old('nombre', $categoria->nombre) }}" required
                    placeholder="ej: Hardware, Software, Red"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nombre') border-red-500 @enderror">
                @error('nombre')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug (readonly) -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Slug
                </label>
                <input type="text" value="{{ $categoria->slug }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 font-mono text-sm"
                    readonly>
                <p class="text-xs text-gray-500 mt-1">Se genera automáticamente del nombre</p>
            </div>

            <!-- Descripción -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Descripción
                </label>
                <textarea name="descripcion" rows="3"
                    placeholder="Descripción opcional de la categoría"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Icono y Color -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Icono (Font Awesome)
                    </label>
                    <div class="flex gap-2">
                        <input type="text" name="icono" value="{{ old('icono', $categoria->icono) }}"
                            placeholder="ej: fa-desktop, fa-code"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <div class="px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 flex items-center">
                            @if(old('icono', $categoria->icono))
                                <i class="fas {{ old('icono', $categoria->icono) }} text-lg" style="color: {{ old('color', $categoria->color) }};"></i>
                            @else
                                <i class="fas fa-question text-lg text-gray-400"></i>
                            @endif
                        </div>
                    </div>
                    @error('icono')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-info-circle"></i> 
                        Ver iconos en <a href="https://fontawesome.com/icons" target="_blank" class="text-blue-600 hover:underline">fontawesome.com</a>
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Color
                    </label>
                    <div class="flex gap-2">
                        <input type="color" name="color" value="{{ old('color', $categoria->color) }}"
                            class="h-10 px-2 border border-gray-300 rounded-lg cursor-pointer">
                        <input type="text" value="{{ old('color', $categoria->color) }}"
                            placeholder="#3b82f6"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                            readonly>
                    </div>
                    @error('color')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Estado -->
            <div class="flex items-center gap-3">
                <input type="checkbox" name="estado" value="1" {{ old('estado', $categoria->estado) ? 'checked' : '' }}
                    class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                <label class="text-sm font-semibold text-gray-700">
                    Categoría activa
                </label>
            </div>

            <!-- Información de tipos -->
            @if($categoria->cantidadTipos() > 0)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle"></i>
                        Esta categoría tiene <strong>{{ $categoria->cantidadTipos() }}</strong> tipo(s) de equipo asociado(s)
                    </p>
                </div>
            @endif

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t">
                <a href="{{ route('categorias.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                    Actualizar Categoría
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
