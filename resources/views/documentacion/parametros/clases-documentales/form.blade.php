@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', $claseDocumental ? 'Editar Clase Documental' : 'Nueva Clase Documental')
@section('page-description', $claseDocumental ? 'Actualizar clase documental' : 'Crear nueva clase documental')
@section('content')
@php
    $isEdit = isset($claseDocumental) && $claseDocumental;
    $formRoute = $isEdit
        ? route('documentacion.parametros.clases_documentales.update', ['clase_documental' => $claseDocumental->id])
        : route('documentacion.parametros.clases_documentales.store');
@endphp
<div class="max-w-3xl">
    <form action="{{ $formRoute }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción *</label>
                <textarea name="descripcion" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('descripcion') border-red-500 @enderror" required>{{ old('descripcion', $claseDocumental->descripcion ?? '') }}</textarea>
                @error('descripcion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Estado *</label>
                <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('estado') border-red-500 @enderror" required>
                    <option value="1" {{ old('estado', $claseDocumental->estado ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('estado', $claseDocumental->estado ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estado')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Versión *</label>
                <input type="text" name="version" value="{{ old('version', $claseDocumental->version ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('version') border-red-500 @enderror" required>
                @error('version')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Realizado por *</label>
                <select name="realizado_por_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('realizado_por_id') border-red-500 @enderror" required>
                    <option value="">-- Seleccione un usuario --</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('realizado_por_id', $claseDocumental->realizado_por_id ?? '') == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->name }}
                        </option>
                    @endforeach
                </select>
                @error('realizado_por_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Registrado por *</label>
                <select name="registrado_por_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('registrado_por_id') border-red-500 @enderror" required>
                    <option value="">-- Seleccione un usuario --</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('registrado_por_id', $claseDocumental->registrado_por_id ?? '') == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->name }}
                        </option>
                    @endforeach
                </select>
                @error('registrado_por_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Revisado por *</label>
                <select name="revisado_por_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('revisado_por_id') border-red-500 @enderror" required>
                    <option value="">-- Seleccione un usuario --</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('revisado_por_id', $claseDocumental->revisado_por_id ?? '') == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->name }}
                        </option>
                    @endforeach
                </select>
                @error('revisado_por_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Observación</label>
                <textarea name="observacion" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('observacion') border-red-500 @enderror">{{ old('observacion', $claseDocumental->observacion ?? '') }}</textarea>
                @error('observacion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Imagen / Archivo (JPG, PNG, GIF, WebP, PDF - máx. 10MB)</label>
                <input type="file" name="imagen" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('imagen') border-red-500 @enderror">
                @error('imagen')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                @if($isEdit && $claseDocumental->imagen)
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 mb-1">Archivo actual:</p>
                        @if(in_array(pathinfo($claseDocumental->imagen, PATHINFO_EXTENSION), ['jpg','jpeg','png','gif','webp']))
                            <img src="{{ Storage::disk('private')->url($claseDocumental->imagen) }}" alt="Imagen actual" class="max-w-xs max-h-32 rounded border">
                        @else
                            <a href="{{ Storage::disk('private')->url($claseDocumental->imagen) }}" target="_blank" class="text-blue-600 underline text-sm">Ver archivo actual</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('documentacion.parametros.clases_documentales.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $isEdit ? 'Actualizar' : 'Crear' }} Clase Documental
            </button>
        </div>
    </form>
</div>
@endsection
