@extends('layouts.app')

@section('title', $formato ? 'Editar Formato' : 'Nuevo Formato')
@section('page-title', $formato ? 'Editar Formato de Informe' : 'Registrar Formato de Informe')
@section('page-description', $formato ? 'Actualizar formato de informe técnico' : 'Agregar un nuevo formato de informe técnico')

@section('content')
<div class="max-w-2xl">
    <form action="{{ $formato ? route('parametros.informe-formatos.update', $formato) : route('parametros.informe-formatos.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @if($formato)
            @method('PUT')
        @endif

        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Formato</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Formato *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $formato->nombre ?? '') }}" placeholder="Ej: Informe Técnico Estándar"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nombre') border-red-500 @enderror" required>
                    @error('nombre')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Código *</label>
                    <input type="text" name="codigo" value="{{ old('codigo', $formato->codigo ?? '') }}" placeholder="Ej: INF-001"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 font-mono @error('codigo') border-red-500 @enderror" required>
                    @error('codigo')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Template Blade *</label>
                    <input type="text" name="blade_template" value="{{ old('blade_template', $formato->blade_template ?? '') }}" placeholder="Ej: incidencias.servicios.pdf.informe-tecnico-new"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 font-mono text-sm @error('blade_template') border-red-500 @enderror" required>
                    <p class="text-xs text-gray-500 mt-1">Ruta del template Blade sin extensión (ej: incidencias.servicios.pdf.informe-tecnico-new)</p>
                    @error('blade_template')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="2" placeholder="Descripción breve del formato"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('descripcion', $formato->descripcion ?? '') }}</textarea>
                    @error('descripcion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="activo" id="activo" value="1" class="w-4 h-4 border border-gray-300 rounded-md" {{ old('activo', $formato->activo ?? true) ? 'checked' : '' }}>
                    <label for="activo" class="text-sm font-medium text-gray-700">Formato activo</label>
                </div>
            </div>
        </div>

        @if($formato)
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                <h3 class="text-sm font-semibold text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Empresas usando este formato
                </h3>
                <p class="text-sm text-blue-700">
                    {{ $formato->empresas_count > 0 ? $formato->empresas_count . ' empresa' . ($formato->empresas_count !== 1 ? 's' : '') . ' asignada' . ($formato->empresas_count !== 1 ? 's' : '') : 'Sin empresas asignadas' }}
                </p>
            </div>
        @endif

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('parametros.informe-formatos.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $formato ? 'Actualizar' : 'Registrar' }} Formato
            </button>
        </div>
    </form>
</div>
@endsection
