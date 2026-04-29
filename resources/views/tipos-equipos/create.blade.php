@extends('layouts.app')
@section('title', 'Nuevo Tipo de Equipo')
@section('page-title', 'Crear Nuevo Tipo de Equipo')
@section('page-description', 'Agrega un nuevo tipo de equipo al catálogo')
@section('content')
<div class="max-w-2xl">
    <form action="{{ route('tipos-equipos.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre <span class="text-red-600">*</span></label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" 
                placeholder="ej: Computador de Escritorio"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nombre') border-red-500 @enderror" 
                required>
            @error('nombre')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Categoría <span class="text-red-600">*</span></label>
            <select name="categoria_id" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('categoria_id') border-red-500 @enderror" 
                required>
                <option value="">-- Selecciona una categoría --</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nombre }}
                    </option>
                @endforeach
            </select>
            @error('categoria_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">
                <i class="fas fa-info-circle"></i>
                ¿No encuentras la categoría? <a href="{{ route('categorias.create') }}" class="text-blue-600 hover:underline">Crea una nueva aquí</a>
            </p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
            <textarea name="descripcion" rows="3" 
                placeholder="Describe brevemente este tipo de equipo"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Ícono (Font Awesome)</label>
            <input type="text" name="icono" value="{{ old('icono') }}" 
                placeholder="ej: fa-desktop"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('icono')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">
                <i class="fas fa-info-circle"></i>
                Ver iconos en <a href="https://fontawesome.com/icons" target="_blank" class="text-blue-600 hover:underline">fontawesome.com</a>
            </p>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t">
            <a href="{{ route('tipos-equipos.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                <i class="fas fa-plus"></i> Crear Tipo
            </button>
        </div>
    </form>
</div>
@endsection
