@extends('layouts.app')
@section('title', $tipoEquipo ? 'Editar Tipo' : 'Nuevo Tipo')
@section('page-title', $tipoEquipo ? 'Editar Tipo' : 'Nuevo Tipo')
@section('page-description', 'Gestionar tipo de equipo')
@section('content')
<div class="max-w-2xl">
    <form action="{{ $tipoEquipo ? route('tipos-equipos.update', $tipoEquipo) : route('tipos-equipos.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @if($tipoEquipo) @method('PUT') @endif

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre *</label>
            <input type="text" name="nombre" value="{{ old('nombre', $tipoEquipo->nombre ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nombre') border-red-500 @enderror" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Categoría *</label>
            <select name="categoria" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('categoria') border-red-500 @enderror" required>
                <option value="HARDWARE" {{ old('categoria', $tipoEquipo->categoria ?? 'HARDWARE') == 'HARDWARE' ? 'selected' : '' }}>Hardware</option>
                <option value="SOFTWARE" {{ old('categoria', $tipoEquipo->categoria ?? '') == 'SOFTWARE' ? 'selected' : '' }}>Software</option>
                <option value="RED" {{ old('categoria', $tipoEquipo->categoria ?? '') == 'RED' ? 'selected' : '' }}>Red</option>
                <option value="PERIFERICO" {{ old('categoria', $tipoEquipo->categoria ?? '') == 'PERIFERICO' ? 'selected' : '' }}>Periférico</option>
                <option value="OTRO" {{ old('categoria', $tipoEquipo->categoria ?? '') == 'OTRO' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
            <textarea name="descripcion" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('descripcion', $tipoEquipo->descripcion ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Ícono (Font Awesome)</label>
            <input type="text" name="icono" value="{{ old('icono', $tipoEquipo->icono ?? '') }}" placeholder="fa-desktop" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('tipos-equipos.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $tipoEquipo ? 'Actualizar' : 'Crear' }} Tipo
            </button>
        </div>
    </form>
</div>
@endsection
