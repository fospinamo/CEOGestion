@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', 'Detalle de Digitalizacion')
@section('page-description', 'Informacion completa de la digitalizacion')
@section('content')
<div class="max-w-3xl space-y-4">
    <div class="bg-white rounded-lg shadow p-6 space-y-3">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $digitalizacion->titulo ?? 'Digitalizacion' }}</h2>
                <p class="text-sm text-gray-500">Documento: {{ $digitalizacion->documento?->nombre ?? 'N/A' }}</p>
            </div>
            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $digitalizacion->estado === 'ACTIVO' ? '#dcfce7' : '#fee2e2' }}; color: {{ $digitalizacion->estado === 'ACTIVO' ? '#166534' : '#991b1b' }};">
                {{ $digitalizacion->estado === 'ACTIVO' ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Empresa</p>
                <p class="font-semibold text-gray-900">{{ $digitalizacion->empresa?->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Sede</p>
                <p class="font-semibold text-gray-900">{{ $digitalizacion->sede?->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Proceso</p>
                <p class="font-semibold text-gray-900">{{ $digitalizacion->proceso?->proceso ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Subproceso</p>
                <p class="font-semibold text-gray-900">{{ $digitalizacion->subproceso?->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Radicacion</p>
                <p class="font-semibold text-gray-900">{{ $digitalizacion->radicacion?->numero ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Fecha documento</p>
                <p class="font-semibold text-gray-900">{{ $digitalizacion->fecha_documento?->format('Y-m-d') ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Usuario</p>
                <p class="font-semibold text-gray-900">{{ $digitalizacion->user?->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('documentacion.digitalizaciones.edit', ['digitalizacion' => $digitalizacion->id]) }}" class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
        <a href="{{ route('documentacion.digitalizaciones.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Volver</a>
    </div>
</div>
@endsection
