@extends('layouts.app')

@section('title', 'Programar Mantenimiento')
@section('page-title', 'Programar Mantenimiento/Calibración')
@section('page-description', 'Agendar un nuevo mantenimiento o calibración')

@section('content')

<div class="max-w-2xl mx-auto">
    <form action="{{ route('parametros.equipos.mantenimientos.store', $equipo->id) }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf

        <!-- Información del Equipo -->
        <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
            <p class="font-semibold text-gray-900">{{ $equipo->marca?->nombre }} {{ $equipo->modelo }}</p>
            <p class="text-sm text-gray-600">{{ $equipo->codigo_activo_cliente }}</p>
        </div>

        <!-- Tipo de Actividad -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Actividad *</label>
            <select name="tipo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('tipo') border-red-500 @enderror" required>
                <option value="">-- Selecciona --</option>
                <option value="mantenimiento" {{ old('tipo') === 'mantenimiento' ? 'selected' : '' }}>🔧 Mantenimiento</option>
                <option value="calibracion" {{ old('tipo') === 'calibracion' ? 'selected' : '' }}>⚙️ Calibración</option>
            </select>
            @error('tipo')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <!-- Fecha Programada -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Programada *</label>
            <input type="date" name="fecha_programada" value="{{ old('fecha_programada') }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('fecha_programada') border-red-500 @enderror" required>
            @error('fecha_programada')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <!-- Descripción -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción del Trabajo (Opcional)</label>
            <textarea name="descripcion_trabajo" rows="3" placeholder="Describe qué se debe hacer..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('descripcion_trabajo') }}</textarea>
            @error('descripcion_trabajo')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <!-- Técnico Responsable -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Técnico Responsable (Opcional)</label>
            <input type="text" name="tecnico_responsable" value="{{ old('tecnico_responsable') }}" 
                   placeholder="Nombre del técnico" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            @error('tecnico_responsable')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <!-- Empresa Tercero -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Empresa Tercero (Opcional)</label>
            <input type="text" name="empresa_tercero" value="{{ old('empresa_tercero') }}" 
                   placeholder="Nombre de la empresa proveedora" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            @error('empresa_tercero')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('parametros.equipos.mantenimientos.index', $equipo->id) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                Programar
            </button>
        </div>
    </form>
</div>

@endsection
