@extends('layouts.app')

@section('title', $sede->nombre)
@section('page-title', $sede->nombre)
@section('page-description', 'Información detallada de la sede')

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
                    <p class="text-lg font-semibold text-gray-900">{{ $sede->nombre }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Código</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $sede->codigo }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Propietario</p>
                    @if($sede->esDeEmpresa())
                        <p class="text-lg font-semibold text-blue-600">
                            <i class="fas fa-building"></i> {{ $sede->empresa->nombre }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Sede de la empresa</p>
                    @else
                        <p class="text-lg font-semibold text-green-600">
                            <i class="fas fa-user"></i> {{ $sede->cliente->razon_social }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Sede del cliente</p>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-600">Estado</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $sede->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $sede->estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Location Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-blue-600"></i> Ubicación
            </h3>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Municipio</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $sede->municipio->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Departamento</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $sede->municipio->departamento->nombre }}</p>
                    </div>
                </div>
                @if($sede->barrio)
                    <div>
                        <p class="text-sm text-gray-600">Barrio</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $sede->barrio->nombre }}</p>
                    </div>
                @endif
                @if($sede->codigo_postal)
                    <div>
                        <p class="text-sm text-gray-600">Código Postal</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $sede->codigo_postal }}</p>
                    </div>
                @endif
                @if($sede->direccion)
                    <div>
                        <p class="text-sm text-gray-600">Dirección</p>
                        <p class="text-gray-700">{{ $sede->direccion }}</p>
                    </div>
                @endif
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
                    <p class="text-lg font-semibold text-gray-900">{{ $sede->telefono ?? 'No especificado' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $sede->email ?? 'No especificado' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Actions Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Acciones</h3>
            <div class="space-y-2">
                <a href="{{ route('parametros.sedes.edit', $sede) }}" class="block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <form action="{{ route('parametros.sedes.destroy', $sede) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash mr-2"></i> Eliminar
                    </button>
                </form>
                <a href="{{ route('parametros.sedes.index') }}" class="block px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-center">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>

        <!-- Metadata -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h4 class="font-bold text-gray-800 mb-3">Información del Registro</h4>
            <div class="space-y-2 text-sm text-gray-600">
                <p><strong>Creado:</strong> {{ $sede->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Actualizado:</strong> {{ $sede->updated_at->format('d/m/Y H:i') }}</p>
                <p><strong>ID:</strong> {{ $sede->id }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
