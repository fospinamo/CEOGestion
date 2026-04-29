@extends('layouts.app')

@section('title', $departamento->nombre)
@section('page-title', $departamento->nombre)
@section('page-description', 'Información detallada del departamento')

@section('content')
<div class="space-y-6">
    <!-- Basic Info -->
    <div class="grid grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-600 mb-2">Código DANE</h3>
            <p class="text-2xl font-bold text-gray-900 font-mono">{{ $departamento->codigo_dane }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-600 mb-2">País</h3>
            <p class="text-2xl font-bold text-gray-900">{{ $departamento->pais->nombre }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-600 mb-2">Municipios</h3>
            <p class="text-2xl font-bold text-blue-600">{{ $departamento->municipios->count() }}</p>
        </div>
    </div>

    <!-- Municipios List -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-city text-blue-600"></i> Municipios ({{ $departamento->municipios->count() }})
            </h3>
        </div>
        
        @if($departamento->municipios->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($departamento->municipios as $municipio)
                    <div class="p-4 hover:bg-gray-50 transition flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $municipio->nombre }}</p>
                            <p class="text-sm text-gray-600 font-mono">{{ $municipio->codigo_dane }}</p>
                        </div>
                        <a href="{{ route('administrativo.municipios.show', $municipio) }}" class="text-blue-600 hover:text-blue-900 transition">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center text-gray-500">
                <p>No hay municipios registrados en este departamento</p>
            </div>
        @endif
    </div>

    <!-- Back Button -->
    <div>
        <a href="{{ route('administrativo.departamentos.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>
@endsection
