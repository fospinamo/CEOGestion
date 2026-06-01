@extends('layouts.app')

@section('title', $marca ? 'Editar Marca' : 'Nueva Marca')
@section('page-title', $marca ? 'Editar Marca' : 'Registrar Nueva Marca')
@section('page-description', $marca ? 'Actualizar marca de equipo' : 'Agregar marca/fabricante')

@section('content')
<div class="max-w-2xl">
    <form action="{{ $marca ? route('parametros.marcas.update', $marca) : route('parametros.marcas.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @if($marca)
            @method('PUT')
        @endif

        <!-- Información Básica -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Básica</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre de la Marca *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $marca->nombre ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nombre') border-red-500 @enderror" required>
                    @error('nombre')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('descripcion', $marca->descripcion ?? '') }}</textarea>
                    @error('descripcion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">URL del Logo</label>
                    <input type="url" name="logo_url" value="{{ old('logo_url', $marca->logo_url ?? '') }}" placeholder="https://ejemplo.com/logo.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    @error('logo_url')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="estado" id="estado" value="1" class="w-4 h-4 border border-gray-300 rounded-md" {{ old('estado', $marca->estado ?? true) ? 'checked' : '' }}>
                    <label for="estado" class="text-sm font-medium text-gray-700">Marca activa</label>
                </div>
            </div>
        </div>

        @if($marca)
            <!-- Información del Equipo -->
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                <h3 class="text-sm font-semibold text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Equipos con esta marca
                </h3>
                <p class="text-sm text-blue-700">
                    {{ $marca->equipos_count > 0 ? $marca->equipos_count . ' equipo' . ($marca->equipos_count !== 1 ? 's' : '') . ' registrado' . ($marca->equipos_count !== 1 ? 's' : '') : 'Sin equipos registrados' }}
                </p>
            </div>
        @endif

        <!-- Botones -->
        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('parametros.marcas.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $marca ? 'Actualizar' : 'Registrar' }} Marca
            </button>
        </div>
    </form>
</div>
@endsection
