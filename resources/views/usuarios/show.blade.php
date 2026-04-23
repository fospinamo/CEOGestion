@extends('layouts.app')

@section('title', $usuario->name)
@section('page-title', $usuario->name)
@section('page-description', 'Información detallada del usuario')

@section('content')
<div class="grid grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="col-span-2 space-y-6">
        <!-- Avatar and Basic Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-start gap-6">
                <img src="https://ui-avatars.com/api/?name={{ $usuario->name }}&background=0D8ABC&color=fff&size=128" alt="{{ $usuario->name }}" class="w-24 h-24 rounded-full">
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $usuario->name }}</h3>
                    <p class="text-gray-600 mt-1">{{ $usuario->email }}</p>
                    <div class="mt-4 flex gap-2">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $usuario->rol === 'admin' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $usuario->rol === 'gerente' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $usuario->rol === 'coordinador' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $usuario->rol === 'empleado' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        ">
                            {{ ucfirst($usuario->rol) }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $usuario->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $usuario->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Organization Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-building text-blue-600"></i> Información Organizacional
            </h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Empresa</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $usuario->empresa->nombre }}</p>
                </div>
                @if($usuario->sede)
                    <div>
                        <p class="text-sm text-gray-600">Sede</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $usuario->sede->nombre }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Contact Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-phone text-blue-600"></i> Información de Contacto
            </h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <i class="fas fa-envelope text-gray-400 w-5"></i>
                    <span class="text-gray-700">{{ $usuario->email }}</span>
                </div>
                @if($usuario->phone)
                    <div class="flex items-center gap-3">
                        <i class="fas fa-phone text-gray-400 w-5"></i>
                        <span class="text-gray-700">{{ $usuario->phone }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Actions Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Acciones</h3>
            <div class="space-y-2">
                <a href="{{ route('usuarios.edit', $usuario) }}" class="block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash mr-2"></i> Eliminar
                    </button>
                </form>
                <a href="{{ route('usuarios.index') }}" class="block px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-center">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>

        <!-- Metadata -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h4 class="font-bold text-gray-800 mb-3">Información del Registro</h4>
            <div class="space-y-2 text-sm text-gray-600">
                <p><strong>Creado:</strong> {{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Actualizado:</strong> {{ $usuario->updated_at->format('d/m/Y H:i') }}</p>
                <p><strong>ID:</strong> {{ $usuario->id }}</p>
            </div>
        </div>

        <!-- Role Info -->
        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
            <h4 class="font-bold text-blue-900 mb-3">Permisos del Rol</h4>
            <div class="space-y-2 text-sm text-blue-800">
                @if($usuario->rol === 'admin')
                    <p>✓ Acceso total al sistema</p>
                    <p>✓ Gestionar empresas, sedes y usuarios</p>
                    <p>✓ Configuración del sistema</p>
                @elseif($usuario->rol === 'gerente')
                    <p>✓ Gestionar sedes de la empresa</p>
                    <p>✓ Crear usuarios subordinados</p>
                    <p>✓ Ver reportes de empresa</p>
                @elseif($usuario->rol === 'coordinador')
                    <p>✓ Gestionar actividades de la sede</p>
                    <p>✓ Supervisar empleados</p>
                    <p>✓ Generar reportes operacionales</p>
                @else
                    <p>✓ Acceso a tareas asignadas</p>
                    <p>✓ Registrar actividades</p>
                    <p>✓ Ver información de la sede</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
