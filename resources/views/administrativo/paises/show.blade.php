@extends('layouts.app')

@section('title', $paise->nombre)
@section('page-title', $paise->nombre)
@section('page-description', 'Información detallada del país')

@section('content')
<div class="space-y-6">
    <!-- Basic Info -->
    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-600 mb-2">Código DANE</h3>
            <p class="text-3xl font-bold text-gray-900 font-mono">{{ $paise->codigo_dane }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm text-gray-600 mb-2">Departamentos</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $paise->departamentos->count() }}</p>
        </div>
    </div>

    <!-- Departamentos List -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-map text-blue-600"></i> Departamentos ({{ $paise->departamentos->count() }})
            </h3>
        </div>
        
        @if($paise->departamentos->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($paise->departamentos as $departamento)
                    <div class="p-4 hover:bg-gray-50 transition flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $departamento->nombre }}</p>
                            <p class="text-sm text-gray-600">{{ $departamento->municipios->count() }} municipios</p>
                        </div>
                        <a href="{{ route('administrativo.departamentos.show', $departamento) }}" class="text-blue-600 hover:text-blue-900 transition">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center text-gray-500">
                <p>No hay departamentos registrados en este país</p>
            </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3">
        <a href="{{ route('administrativo.paises.edit', $paise) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            ✏️ Editar
        </a>
        <form action="{{ route('administrativo.paises.destroy', $paise) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este país?')">
            @csrf @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                🗑️ Eliminar
            </button>
        </form>
        <a href="{{ route('administrativo.paises.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            ← Volver
        </a>
    </div>
</div>
@endsection
