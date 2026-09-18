@extends('layouts.app')
@section('title', 'Parámetros')
@section('page-title', 'Detalle de Cargo')
@section('page-description', 'Información completa del cargo')
@section('content')
<div class="max-w-3xl space-y-4">
    <div class="bg-white rounded-lg shadow p-6 space-y-3">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $cargo->descripcion }}</h2>
                <p class="text-sm text-gray-500">Código: {{ $cargo->codigo }}</p>
            </div>
            @if($cargo->estado)
                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Activo</span>
            @else
                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Inactivo</span>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Empresa</p>
                <p class="font-semibold text-gray-900">{{ $cargo->empresa?->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Consecutivo</p>
                <p class="font-semibold text-gray-900">{{ $cargo->consecutivo }}</p>
            </div>
            <div>
                <p class="text-gray-500">Fecha de creación</p>
                <p class="font-semibold text-gray-900">{{ $cargo->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Última actualización</p>
                <p class="font-semibold text-gray-900">{{ $cargo->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
        </div>

        @if($cargo->usuarios->count())
        <div class="mt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Usuarios con este cargo ({{ $cargo->usuarios->count() }})</h3>
            <div class="bg-gray-50 rounded-lg p-3">
                @foreach($cargo->usuarios as $usuario)
                    <span class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded mr-2 mb-2">{{ $usuario->name }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="flex gap-3">
        @can('cargos.editar')
        <a href="{{ route('parametros.cargos.edit', ['cargo' => $cargo->id]) }}" class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
        @endcan
        <a href="{{ route('parametros.cargos.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Volver</a>
    </div>
</div>
@endsection
