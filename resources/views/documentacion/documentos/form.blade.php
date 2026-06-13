@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', $documento ? 'Editar Documento' : 'Nuevo Documento')
@section('page-description', $documento ? 'Actualizar documento' : 'Crear nuevo documento')
@section('content')
@php
    $isEdit = isset($documento) && $documento;
    $formRoute = $isEdit
        ? route('documentacion.documentos.update', ['documento' => $documento->id])
        : route('documentacion.documentos.store');
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
                        <option value="{{ $empresa->id }}" {{ old('empresa_id', $documento->empresa_id ?? '') == $empresa->id ? 'selected' : '' }}>
                            {{ $empresa->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('empresa_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sede *</label>
                <select name="sede_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('sede_id') border-red-500 @enderror" required>
                    <option value="">-- Seleccione una sede --</option>
                    @foreach($sedes as $sede)
                        <option value="{{ $sede->id }}" {{ old('sede_id', $documento->sede_id ?? '') == $sede->id ? 'selected' : '' }}>
                            {{ $sede->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('sede_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Proceso *</label>
                <select name="proceso_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('proceso_id') border-red-500 @enderror" required>
                    <option value="">-- Seleccione un proceso --</option>
                    @foreach($procesos as $proceso)
                        <option value="{{ $proceso->id }}" {{ old('proceso_id', $documento->proceso_id ?? '') == $proceso->id ? 'selected' : '' }}>
                            {{ $proceso->proceso }}
                        </option>
                    @endforeach
                </select>
                @error('proceso_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Subproceso *</label>
                <select name="subproceso_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('subproceso_id') border-red-500 @enderror" required>
                    <option value="">-- Seleccione un subproceso --</option>
                    @foreach($subprocesos as $subproceso)
                        <option value="{{ $subproceso->id }}" {{ old('subproceso_id', $documento->subproceso_id ?? '') == $subproceso->id ? 'selected' : '' }}>
                            {{ $subproceso->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('subproceso_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Codigo *</label>
                <input type="text" name="codigo" value="{{ old('codigo', $documento->codigo ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('codigo') border-red-500 @enderror" required>
                @error('codigo')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $documento->nombre ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('nombre') border-red-500 @enderror" required>
                @error('nombre')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Descripcion</label>
                <textarea name="descripcion" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $documento->descripcion ?? '') }}</textarea>
                @error('descripcion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Version</label>
                <input type="text" name="version" value="{{ old('version', $documento->version ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('version') border-red-500 @enderror">
                @error('version')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Estado *</label>
                <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('estado') border-red-500 @enderror" required>
                    @foreach(['VIGENTE' => 'Vigente', 'INACTIVO' => 'Inactivo'] as $value => $label)
                        <option value="{{ $value }}" {{ old('estado', $documento->estado ?? 'VIGENTE') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('estado')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('documentacion.documentos.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $isEdit ? 'Actualizar' : 'Crear' }} Documento
            </button>
        </div>
    </form>
</div>
@endsection
