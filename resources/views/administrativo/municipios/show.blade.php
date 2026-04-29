@extends('layouts.app')

@section('title', $municipio->nombre)
@section('page-title', $municipio->nombre)
@section('page-description', 'Información detallada del municipio')

@section('content')
<div class="space-y-6">
    <!-- Basic Info -->
    <div class="grid grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-600 mb-2">Código DANE</h3>
            <p class="text-xl font-bold text-gray-900 font-mono">{{ $municipio->codigo_dane }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-600 mb-2">Departamento</h3>
            <a href="{{ route('administrativo.departamentos.show', $municipio->departamento) }}" class="text-xl font-bold text-blue-600 hover:underline">
                {{ $municipio->departamento->nombre }}
            </a>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-600 mb-2">Barrios</h3>
            <p class="text-2xl font-bold text-green-600">{{ $municipio->barrios->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-600 mb-2">Sedes</h3>
            <p class="text-2xl font-bold text-blue-600">{{ $municipio->sedes->count() }}</p>
        </div>
    </div>

    <!-- Barrios List -->
    @if($municipio->barrios->count() > 0)
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-home text-green-600"></i> Barrios ({{ $municipio->barrios->count() }})
                </h3>
            </div>
            
            <div class="divide-y divide-gray-200">
                @foreach($municipio->barrios as $barrio)
                    <div class="p-4 hover:bg-gray-50 transition">
                        <p class="font-semibold text-gray-900">{{ $barrio->nombre }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Sedes List -->
    @if($municipio->sedes->count() > 0)
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-blue-600"></i> Sedes ({{ $municipio->sedes->count() }})
                </h3>
            </div>
            
            <div class="divide-y divide-gray-200">
                @foreach($municipio->sedes as $sede)
                    <div class="p-4 hover:bg-gray-50 transition flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $sede->nombre }}</p>
                            <p class="text-sm text-gray-600">{{ $sede->cliente?->razon_social ?? ($sede->empresa?->nombre ?? 'N/A') }}</p>
                        </div>
                        <a href="{{ route('parametros.sedes.show', $sede) }}" class="text-blue-600 hover:text-blue-900 transition">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Back Button -->
    <div>
        <a href="{{ route('administrativo.municipios.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>
@endsection
