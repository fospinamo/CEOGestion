@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', $digitalizacion ? 'Editar Digitalizacion' : 'Nueva Digitalizacion')
@section('page-description', $digitalizacion ? 'Actualizar digitalizacion' : 'Crear nueva digitalizacion')
@section('content')
@php
    $isEdit = isset($digitalizacion) && $digitalizacion;
    $formRoute = $isEdit
        ? route('documentacion.digitalizaciones.update', ['digitalizacion' => $digitalizacion->id])
        : route('documentacion.digitalizaciones.store');
@endphp
<div class="max-w-3xl">
    <form action="{{ $formRoute }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6" enctype="multipart/form-data">
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
                        <option value="{{ $empresa->id }}" {{ old('empresa_id', $digitalizacion->empresa_id ?? '') == $empresa->id ? 'selected' : '' }}>
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
                        <option value="{{ $sede->id }}" {{ old('sede_id', $digitalizacion->sede_id ?? '') == $sede->id ? 'selected' : '' }}>
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
                        <option value="{{ $proceso->id }}" {{ old('proceso_id', $digitalizacion->proceso_id ?? '') == $proceso->id ? 'selected' : '' }}>
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
                        <option value="{{ $subproceso->id }}" {{ old('subproceso_id', $digitalizacion->subproceso_id ?? '') == $subproceso->id ? 'selected' : '' }}>
                            {{ $subproceso->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('subproceso_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Documento *</label>
                <select name="documento_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('documento_id') border-red-500 @enderror" required>
                    <option value="">-- Seleccione un documento --</option>
                    @foreach($documentos as $documento)
                        <option value="{{ $documento->id }}" {{ old('documento_id', $digitalizacion->documento_id ?? '') == $documento->id ? 'selected' : '' }}>
                            {{ $documento->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('documento_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Radicacion</label>
                <select name="radicacion_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('radicacion_id') border-red-500 @enderror">
                    <option value="">-- Sin radicacion --</option>
                    @foreach($radicaciones as $radicacion)
                        <option value="{{ $radicacion->id }}" {{ old('radicacion_id', $digitalizacion->radicacion_id ?? '') == $radicacion->id ? 'selected' : '' }}>
                            {{ $radicacion->numero }}
                        </option>
                    @endforeach
                </select>
                @error('radicacion_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Titulo</label>
                <input type="text" name="titulo" value="{{ old('titulo', $digitalizacion->titulo ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('titulo') border-red-500 @enderror">
                @error('titulo')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha documento</label>
                <input type="date" name="fecha_documento" value="{{ old('fecha_documento', isset($digitalizacion->fecha_documento) ? $digitalizacion->fecha_documento->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('fecha_documento') border-red-500 @enderror">
                @error('fecha_documento')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Estado *</label>
                <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('estado') border-red-500 @enderror" required>
                    @foreach(['ACTIVO' => 'Activo', 'INACTIVO' => 'Inactivo'] as $value => $label)
                        <option value="{{ $value }}" {{ old('estado', $digitalizacion->estado ?? 'ACTIVO') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('estado')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Archivo</label>
                <input type="file" name="archivo" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('archivo') border-red-500 @enderror">
                @error('archivo')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('documentacion.digitalizaciones.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $isEdit ? 'Actualizar' : 'Crear' }} Digitalizacion
            </button>
        </div>
    </form>
</div>
@endsection
