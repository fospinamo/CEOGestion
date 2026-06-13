@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', 'Detalle de Radicacion')
@section('page-description', 'Informacion completa de la radicacion')
@section('content')
<div class="max-w-3xl space-y-4">
    <div class="bg-white rounded-lg shadow p-6 space-y-3">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $radicacion->numero }}</h2>
                <p class="text-sm text-gray-500">{{ $radicacion->tipo }}</p>
            </div>
            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $radicacion->estado === 'ABIERTA' ? '#dcfce7' : '#fee2e2' }}; color: {{ $radicacion->estado === 'ABIERTA' ? '#166534' : '#991b1b' }};">
                {{ $radicacion->estado === 'ABIERTA' ? 'Abierta' : 'Cerrada' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Empresa</p>
                <p class="font-semibold text-gray-900">{{ $radicacion->empresa?->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Sede</p>
                <p class="font-semibold text-gray-900">{{ $radicacion->sede?->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Documento</p>
                <p class="font-semibold text-gray-900">{{ $radicacion->documento?->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Fecha radicacion</p>
                <p class="font-semibold text-gray-900">{{ $radicacion->fecha_radicacion?->format('Y-m-d') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Remitente</p>
                <p class="font-semibold text-gray-900">{{ $radicacion->remitente ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Asunto</p>
                <p class="font-semibold text-gray-900">{{ $radicacion->asunto ?? 'N/A' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-gray-500">Descripcion</p>
                <p class="font-semibold text-gray-900">{{ $radicacion->descripcion ?? 'Sin descripcion' }}</p>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('documentacion.radicaciones.edit', ['radicacion' => $radicacion->id]) }}" class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
        <a href="{{ route('documentacion.radicaciones.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Volver</a>
    </div>
</div>
@endsection
