@extends('layouts.app')

@section('title', 'Nuevo País')
@section('page-title', 'Crear Nuevo País')
@section('page-description', 'Agrega un nuevo país al sistema')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('administrativo.paises.store') }}" method="POST">
            @csrf

            <!-- Código DANE -->
            <div class="mb-4">
                <label for="codigo_dane" class="block text-sm font-semibold text-gray-700 mb-2">Código DANE</label>
                <input type="text" name="codigo_dane" id="codigo_dane" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('codigo_dane') border-red-500 @enderror"
                    value="{{ old('codigo_dane') }}" placeholder="Ej: 170001" required>
                @error('codigo_dane')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nombre -->
            <div class="mb-6">
                <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">Nombre del País</label>
                <input type="text" name="nombre" id="nombre" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nombre') border-red-500 @enderror"
                    value="{{ old('nombre') }}" placeholder="Ej: Colombia" required>
                @error('nombre')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                    ✓ Guardar País
                </button>
                <a href="{{ route('administrativo.paises.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition">
                    ✕ Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
