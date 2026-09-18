@extends('layouts.app')

@section('title', 'Detalle Formato: ' . $formato->nombre)
@section('page-title', $formato->nombre)
@section('page-description', 'Detalle del formato de informe técnico')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('parametros.informe-formatos.index') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Volver a Formatos
        </a>
        <div class="flex gap-2">
            @can('informe-formatos.editar')
            <a href="{{ route('parametros.informe-formatos.edit', $formato) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm transition">
                <i class="fas fa-edit mr-1"></i> Editar
            </a>
            @endcan
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información General</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Nombre</p>
                <p class="text-sm text-gray-900 mt-1">{{ $formato->nombre }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Código</p>
                <p class="text-sm text-gray-900 mt-1 font-mono">{{ $formato->codigo }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Template Blade</p>
                <p class="text-sm text-gray-900 mt-1 font-mono bg-purple-50 text-purple-700 px-2 py-1 rounded inline-block">{{ $formato->blade_template }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Estado</p>
                <p class="mt-1">
                    @if($formato->activo)
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                    @else
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                    @endif
                </p>
            </div>
            @if($formato->descripcion)
                <div class="col-span-2">
                    <p class="text-xs font-semibold text-gray-500 uppercase">Descripción</p>
                    <p class="text-sm text-gray-900 mt-1">{{ $formato->descripcion }}</p>
                </div>
            @endif
        </div>
    </div>

    @if($formato->empresas->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Empresas Asignadas</h3>
            <div class="divide-y">
                @foreach($formato->empresas as $empresa)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $empresa->nombre }}</p>
                            <p class="text-xs text-gray-500">NIT: {{ $empresa->nit }}-{{ $empresa->digito_verificacion }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $empresa->created_at->format('d/m/Y') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
