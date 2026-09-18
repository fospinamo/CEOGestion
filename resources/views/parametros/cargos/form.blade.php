@extends('layouts.app')
@section('title', 'Parámetros')
@section('page-title', $cargo ? 'Editar Cargo' : 'Nuevo Cargo')
@section('page-description', $cargo ? 'Actualizar cargo' : 'Crear nuevo cargo')
@section('content')
@php
    $isEdit = isset($cargo) && $cargo;
    $formRoute = $isEdit
        ? route('parametros.cargos.update', ['cargo' => $cargo->id])
        : route('parametros.cargos.store');
@endphp
<div class="max-w-3xl">
    <form action="{{ $formRoute }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Empresa *</label>
                <select name="empresa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('empresa_id') border-red-500 @enderror" required>
                    <option value="">-- Seleccione una empresa --</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}" {{ old('empresa_id', $cargo->empresa_id ?? '') == $empresa->id ? 'selected' : '' }}>
                            {{ $empresa->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('empresa_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Estado *</label>
                <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('estado') border-red-500 @enderror" required>
                    <option value="1" {{ old('estado', $cargo->estado ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('estado', $cargo->estado ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estado')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción *</label>
                <input type="text" name="descripcion" value="{{ old('descripcion', $cargo->descripcion ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('descripcion') border-red-500 @enderror" required>
                @error('descripcion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            @if($isEdit)
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Código</label>
                <input type="text" value="{{ $cargo->codigo }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500" disabled>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Consecutivo</label>
                <input type="text" value="{{ $cargo->consecutivo }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500" disabled>
            </div>
            @endif
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('parametros.cargos.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $isEdit ? 'Actualizar' : 'Crear' }} Cargo
            </button>
        </div>
    </form>
</div>
@endsection
