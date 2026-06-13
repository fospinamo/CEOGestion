@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', 'Detalle de Documento')
@section('page-description', 'Informacion completa del documento')
@section('content')
<div class="max-w-3xl space-y-4">
    <div class="bg-white rounded-lg shadow p-6 space-y-3">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $documento->nombre }}</h2>
                <p class="text-sm text-gray-500">Codigo: {{ $documento->codigo }}</p>
            </div>
            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $documento->estado === 'VIGENTE' ? '#dcfce7' : '#fee2e2' }}; color: {{ $documento->estado === 'VIGENTE' ? '#166534' : '#991b1b' }};">
                {{ $documento->estado === 'VIGENTE' ? 'Vigente' : 'Inactivo' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Empresa</p>
                <p class="font-semibold text-gray-900">{{ $documento->empresa?->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Sede</p>
                <p class="font-semibold text-gray-900">{{ $documento->sede?->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Proceso</p>
                <p class="font-semibold text-gray-900">{{ $documento->proceso?->proceso ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Subproceso</p>
                <p class="font-semibold text-gray-900">{{ $documento->subproceso?->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Version</p>
                <p class="font-semibold text-gray-900">{{ $documento->version ?? 'N/A' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-gray-500">Descripcion</p>
                <p class="font-semibold text-gray-900">{{ $documento->descripcion ?? 'Sin descripcion' }}</p>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('documentacion.documentos.edit', ['documento' => $documento->id]) }}" class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
        <a href="{{ route('documentacion.documentos.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Volver</a>
    </div>
</div>
@endsection
