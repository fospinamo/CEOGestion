@extends('layouts.app')

@section('page-title', 'Editar Rol')
@section('page-description', 'Modificar datos del rol')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Editar Rol</h1>

        <form action="{{ route('seguridad.roles.update', $role) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nombre del Rol *</label>
                <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                    value="{{ old('name', $role->name) }}" required>
                @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <!-- Slug -->
            <div>
                <label for="slug" class="block text-sm font-semibold text-gray-700 mb-1">Slug (identificador) *</label>
                <input type="text" name="slug" id="slug" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                    value="{{ old('slug', $role->slug) }}" required>
                @error('slug')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <!-- Descripción -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
                <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('description', $role->description) }}</textarea>
                @error('description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <!-- Botones -->
            <div class="flex gap-2 pt-6">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center gap-2">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('seguridad.roles.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
        <p class="text-sm text-blue-800">
            <i class="fas fa-info-circle"></i>
            <strong>Para asignar permisos a este rol,</strong> ve a la página de detalles del rol.
        </p>
    </div>
</div>
@endsection
