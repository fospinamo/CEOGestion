@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', $radicacion ? 'Editar Radicacion' : 'Nueva Radicacion')
@section('page-description', $radicacion ? 'Actualizar radicacion' : 'Crear nueva radicacion')
@section('content')
@php
    $isEdit = isset($radicacion) && $radicacion;
    $formRoute = $isEdit
        ? route('documentacion.radicaciones.update', ['radicacion' => $radicacion->id])
        : route('documentacion.radicaciones.store');
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
                        <option value="{{ $empresa->id }}" {{ old('empresa_id', $radicacion->empresa_id ?? '') == $empresa->id ? 'selected' : '' }}>
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
                        <option value="{{ $sede->id }}" {{ old('sede_id', $radicacion->sede_id ?? '') == $sede->id ? 'selected' : '' }}>
                            {{ $sede->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('sede_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Documento *</label>
                <select name="documento_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('documento_id') border-red-500 @enderror" required>
                    <option value="">-- Seleccione un documento --</option>
                    @foreach($documentos as $documento)
                        <option value="{{ $documento->id }}" {{ old('documento_id', $radicacion->documento_id ?? '') == $documento->id ? 'selected' : '' }}>
                            {{ $documento->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('documento_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Numero *</label>
                <input type="text" name="numero" value="{{ old('numero', $radicacion->numero ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('numero') border-red-500 @enderror" required>
                @error('numero')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha radicacion *</label>
                <input type="date" name="fecha_radicacion" value="{{ old('fecha_radicacion', isset($radicacion->fecha_radicacion) ? $radicacion->fecha_radicacion->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('fecha_radicacion') border-red-500 @enderror" required>
                @error('fecha_radicacion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo *</label>
                <select name="tipo" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('tipo') border-red-500 @enderror" required>
                    @foreach(['ENTRADA' => 'Entrada', 'SALIDA' => 'Salida', 'INTERNA' => 'Interna'] as $value => $label)
                        <option value="{{ $value }}" {{ old('tipo', $radicacion->tipo ?? 'ENTRADA') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('tipo')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Remitente</label>
                <input type="text" name="remitente" value="{{ old('remitente', $radicacion->remitente ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('remitente') border-red-500 @enderror">
                @error('remitente')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Asunto</label>
                <input type="text" name="asunto" value="{{ old('asunto', $radicacion->asunto ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('asunto') border-red-500 @enderror">
                @error('asunto')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Descripcion</label>
                <textarea name="descripcion" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $radicacion->descripcion ?? '') }}</textarea>
                @error('descripcion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Estado *</label>
                <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('estado') border-red-500 @enderror" required>
                    @foreach(['ABIERTA' => 'Abierta', 'CERRADA' => 'Cerrada'] as $value => $label)
                        <option value="{{ $value }}" {{ old('estado', $radicacion->estado ?? 'ABIERTA') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('estado')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('documentacion.radicaciones.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $isEdit ? 'Actualizar' : 'Crear' }} Radicacion
            </button>
        </div>
    </form>
</div>
@endsection
