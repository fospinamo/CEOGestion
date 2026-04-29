@extends('layouts.app')
@section('title', 'Documento - ' . $documento->nombre_archivo)
@section('page-title', 'Detalle del Documento')
@section('page-description', $documento->tipo_documento)
@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $documento->nombre_archivo }}</h2>
                <p class="text-gray-600 text-sm">{{ $documento->descripcion }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('documentos.download', $documento) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold flex items-center gap-2">
                    <i class="fas fa-download"></i> Descargar
                </a>
                <form action="{{ route('documentos.destroy', $documento) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-semibold flex items-center gap-2">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4 pt-6 border-t">
            <div>
                <p class="text-xs text-gray-600 uppercase">Tipo</p>
                <p class="font-semibold text-gray-900">{{ $documento->tipo_documento }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 uppercase">Tamaño</p>
                <p class="font-semibold text-gray-900">{{ round($documento->tamaño_bytes / 1024, 2) }} KB</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 uppercase">Formato</p>
                <p class="font-semibold text-gray-900">{{ strtoupper($documento->mime_type) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 uppercase">Subido por</p>
                <p class="font-semibold text-gray-900">{{ $documento->subidoPor->name ?? 'Sistema' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de la Entidad</h3>
        <div class="border-l-4 border-blue-500 pl-6 py-2">
            @if($documento->entidad_type === 'App\\Models\\Contrato')
                <p class="text-sm text-gray-600">Contrato</p>
                <a href="{{ route('contratos.show', $documento->entidad) }}" class="text-lg font-semibold text-blue-600 hover:underline">
                    {{ $documento->entidad->numero_contrato }}
                </a>
                <p class="text-sm text-gray-600 mt-2">Cliente: {{ $documento->entidad->cliente?->razon_social ?? 'N/A' }}</p>
            @elseif($documento->entidad_type === 'App\\Models\\Servicio')
                <p class="text-sm text-gray-600">Servicio</p>
                <a href="{{ route('incidencias.servicios.show', $documento->entidad) }}" class="text-lg font-semibold text-blue-600 hover:underline">
                    Servicio #{{ $documento->entidad->id }}
                </a>
                <p class="text-sm text-gray-600 mt-2">Equipo: {{ $documento->entidad->equipo->codigo_interno }}</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Metadatos</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-600">Fecha de Carga</p>
                <p class="font-semibold text-gray-900">{{ $documento->created_at->format('d/m/Y H:i:s') }}</p>
            </div>
            <div>
                <p class="text-gray-600">Ruta de Almacenamiento</p>
                <p class="font-mono text-xs text-gray-700 break-all">{{ $documento->ruta_archivo }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
