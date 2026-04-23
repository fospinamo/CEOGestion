@extends('layouts.app')

@section('title', $empresa->nombre)
@section('page-title', $empresa->nombre)
@section('page-description', 'Información detallada de la empresa')

@section('content')
<div class="grid grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="col-span-2 space-y-6">
        <!-- Basic Info Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-600"></i> Información Básica
            </h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Nombre</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $empresa->nombre }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">NIT</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $empresa->nit }}-{{ $empresa->digito_verificacion }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tipo de Contribuyente</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                        {{ $empresa->tipo_contribuyente === 'persona_natural' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $empresa->tipo_contribuyente === 'persona_juridica' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $empresa->tipo_contribuyente === 'gran_contribuyente' ? 'bg-purple-100 text-purple-800' : '' }}
                    ">
                        {{ str_replace('_', ' ', ucfirst($empresa->tipo_contribuyente)) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Estado</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $empresa->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $empresa->estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-phone text-blue-600"></i> Información de Contacto
            </h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Teléfono</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $empresa->telefono ?? 'No especificado' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $empresa->email ?? 'No especificado' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-600">Página Web</p>
                    <p class="text-lg font-semibold text-gray-900">
                        @if($empresa->pagina_web)
                            <a href="{{ $empresa->pagina_web }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $empresa->pagina_web }}
                            </a>
                        @else
                            No especificada
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Address Info -->
        @if($empresa->direccion)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-blue-600"></i> Dirección
                </h3>
                <p class="text-gray-700">{{ $empresa->direccion }}</p>
            </div>
        @endif

        <!-- Sedes -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-blue-600"></i> Sedes ({{ $empresa->sedes->count() }})
            </h3>
            @if($empresa->sedes->count() > 0)
                <div class="space-y-2">
                    @foreach($empresa->sedes as $sede)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="font-semibold text-gray-900">{{ $sede->nombre }}</p>
                            <p class="text-sm text-gray-600">{{ $sede->municipio->nombre }}, {{ $sede->municipio->departamento->nombre }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No hay sedes registradas</p>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Actions Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Acciones</h3>
            <div class="space-y-2">
                <a href="{{ route('empresas.edit', $empresa) }}" class="block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <form action="{{ route('empresas.destroy', $empresa) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash mr-2"></i> Eliminar
                    </button>
                </form>
                <a href="{{ route('empresas.index') }}" class="block px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-center">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>

        <!-- Metadata -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h4 class="font-bold text-gray-800 mb-3">Información del Registro</h4>
            <div class="space-y-2 text-sm text-gray-600">
                <p><strong>Creado:</strong> {{ $empresa->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Actualizado:</strong> {{ $empresa->updated_at->format('d/m/Y H:i') }}</p>
                <p><strong>ID:</strong> {{ $empresa->id }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
