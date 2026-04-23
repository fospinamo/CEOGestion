@extends('layouts.app')
@section('title', $area ? 'Editar Área' : 'Nueva Área')
@section('page-title', $area ? 'Editar Área' : 'Nueva Área')
@section('page-description', $area ? 'Actualizar área' : 'Crear nueva área')
@section('content')
<div class="max-w-3xl">
    <form action="{{ $area ? route('areas.update', $area) : route('areas.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @if($area) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sede *</label>
                <select name="sede_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('sede_id') border-red-500 @enderror" required>
                    <option value="">Seleccione sede</option>
                    @foreach($sedes as $sede)
                        <option value="{{ $sede->id }}" {{ old('sede_id', $area->sede_id ?? '') == $sede->id ? 'selected' : '' }}>
                            {{ $sede->nombre }} - {{ $sede->cliente?->empresa?->nombre ?? 'Sin empresa' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $area->nombre ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nombre') border-red-500 @enderror" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nivel de Riesgo *</label>
                <select name="nivel_riesgo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nivel_riesgo') border-red-500 @enderror" required>
                    <option value="BAJO" {{ old('nivel_riesgo', $area->nivel_riesgo ?? 'BAJO') == 'BAJO' ? 'selected' : '' }}>Bajo</option>
                    <option value="MEDIO" {{ old('nivel_riesgo', $area->nivel_riesgo ?? '') == 'MEDIO' ? 'selected' : '' }}>Medio</option>
                    <option value="ALTO" {{ old('nivel_riesgo', $area->nivel_riesgo ?? '') == 'ALTO' ? 'selected' : '' }}>Alto</option>
                    <option value="CRITICO" {{ old('nivel_riesgo', $area->nivel_riesgo ?? '') == 'CRITICO' ? 'selected' : '' }}>Crítico</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Responsable</label>
                <input type="text" name="responsable_nombre" value="{{ old('responsable_nombre', $area->responsable_nombre ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Contacto Responsable</label>
                <input type="text" name="responsable_contacto" value="{{ old('responsable_contacto', $area->responsable_contacto ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <input type="checkbox" name="estado" value="1" {{ old('estado', $area->estado ?? true) ? 'checked' : '' }} class="rounded">
                    Activo
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
            <textarea name="descripcion" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('descripcion', $area->descripcion ?? '') }}</textarea>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('areas.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $area ? 'Actualizar' : 'Crear' }} Área
            </button>
        </div>
    </form>
</div>
@endsection
