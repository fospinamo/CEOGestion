@extends('layouts.app')

@section('page-title', 'Detalles del Permiso')
@section('page-description', 'Información del permiso')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">{{ $permission->name }}</h1>
            <p class="text-gray-600 mt-2">{{ $permission->description }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded">
            <div>
                <p class="text-sm text-gray-600">Módulo</p>
                <p class="text-lg font-semibold">{{ $permission->module ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Recurso</p>
                <p class="text-lg font-semibold">{{ $permission->resource ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Acción</p>
                <p class="text-lg font-semibold">{{ $permission->action ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Roles</p>
                <p class="text-lg font-semibold">{{ $permission->roles_count ?? 0 }}</p>
            </div>
        </div>

        <div class="mb-6">
            <a href="{{ route('seguridad.permissions.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                Volver
            </a>
        </div>

        @if($permission->roles->count() > 0)
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-4">Roles con este permiso</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($permission->roles as $role)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <h3 class="font-semibold text-gray-800">{{ $role->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $role->description }}</p>
                            <p class="text-xs text-blue-600 mt-2">{{ $role->users_count }} usuario(s)</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded">
                <p class="text-yellow-800">
                    <i class="fas fa-info-circle"></i>
                    Este permiso no está asignado a ningún rol aún.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
