@extends('layouts.app')
@section('title', 'Detalle Proceso')
@section('page-title', 'Detalle de Proceso')
@section('page-description', 'Información completa del proceso')
@section('content')
<div class="max-w-3xl space-y-4">
    <div class="bg-white rounded-lg shadow p-6 space-y-3">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $proceso->proceso }}</h2>
                <p class="text-sm text-gray-500">{{ $proceso->subproceso ?? 'Sin subproceso' }}</p>
            </div>
            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $proceso->estado ? '#dcfce7' : '#fee2e2' }}; color: {{ $proceso->estado ? '#166534' : '#991b1b' }};">
                {{ $proceso->estado ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Empresa</p>
                <p class="font-semibold text-gray-900">{{ $proceso->empresa->nombre }}</p>
            </div>
            <div>
                <p class="text-gray-500">Sede</p>
                <p class="font-semibold text-gray-900">{{ $proceso->sede->nombre }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-gray-500">Subprocesos</p>
                @if($proceso->subprocesos->isEmpty())
                    <p class="text-sm text-gray-500">Sin subprocesos registrados.</p>
                @else
                    <div class="space-y-2">
                        @foreach($proceso->subprocesos as $subproceso)
                            <div class="border border-gray-200 rounded-lg p-2">
                                <p class="font-semibold text-gray-900">{{ $subproceso->nombre }}</p>
                                <p class="text-xs text-blue-700 break-all">{{ $subproceso->ruta }}</p>
                                <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $subproceso->estado ? '#dcfce7' : '#fee2e2' }}; color: {{ $subproceso->estado ? '#166534' : '#991b1b' }};">
                                    {{ $subproceso->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('parametros.procesos.edit', ['proceso' => $proceso->id]) }}" class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
        <a href="{{ route('parametros.procesos.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Volver</a>
    </div>
</div>
@endsection
