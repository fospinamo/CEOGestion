@extends('layouts.app')

@section('page-title', 'Nuevo Rol')
@section('page-description', 'Crear nuevo rol del sistema')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Crear Nuevo Rol</h1>

        <form action="{{ route('seguridad.roles.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nombre del Rol *</label>
                <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                    value="{{ old('name') }}" placeholder="ej: Gerente" required>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Slug -->
            <div>
                <label for="slug" class="block text-sm font-semibold text-gray-700 mb-1">Slug (identificador) *</label>
                <input type="text" name="slug" id="slug" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                    value="{{ old('slug') }}" placeholder="ej: gerente" required>
                <p class="text-xs text-gray-500 mt-1">Identificador único en minúsculas, sin espacios</p>
                @error('slug')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Descripción -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
                <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                    placeholder="Describe el propósito de este rol y sus responsabilidades...">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex gap-2 pt-6">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center gap-2">
                    <i class="fas fa-save"></i> Crear Rol
                </button>
                <a href="{{ route('seguridad.roles.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold">
                    Cancelar
                </a>
            </div>

            <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle"></i>
                    <strong>Nota:</strong> Después de crear el rol, podrás asignarle permisos específicos.
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
