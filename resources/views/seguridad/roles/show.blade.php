@extends('layouts.app')

@section('page-title', 'Detalles del Rol: ' . $role->name)
@section('page-description', 'Ver y asignar permisos al rol')

@section('content')
<div class="space-y-6">
    <!-- Información del rol -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $role->name }}</h1>
                <p class="text-gray-600 mt-2">{{ $role->description }}</p>
                <p class="text-sm text-gray-500 mt-2"><strong>Slug:</strong> <code class="bg-gray-100 px-2 py-1 rounded">{{ $role->slug }}</code></p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-blue-600">{{ $role->users_count }}</div>
                <p class="text-sm text-gray-600">Usuarios con este rol</p>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('seguridad.roles.edit', $role) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition flex items-center gap-2">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('seguridad.roles.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                Volver
            </a>
        </div>
    </div>

    <!-- Asignación de permisos -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Asignar Permisos</h2>

        <form action="{{ route('seguridad.roles.assign-permissions', $role) }}" method="POST">
            @csrf

            <!-- Permisos agrupados por módulo -->
            @foreach($permissionsByModule as $module => $resources)
                <div class="mb-8 p-4 border rounded-lg">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-cube text-blue-600"></i> {{ $module ?? 'Sin módulo' }}
                    </h3>

                    <!-- Permisos por recurso -->
                    @foreach($resources as $resource => $permissions)
                        <div class="mb-6">
                            <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-folder text-yellow-600"></i> {{ $resource }}
                            </h4>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 ml-6">
                                @foreach($permissions as $permission)
                                    <label class="flex items-start gap-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                                        <input type="checkbox" name="permission_ids[]" value="{{ $permission->id }}" 
                                            {{ $role->permissions->contains($permission) ? 'checked' : '' }} 
                                            class="rounded mt-1">
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-gray-700">{{ $permission->action }}</span>
                                            <p class="text-xs text-gray-500">{{ $permission->description }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <!-- Botones de acción -->
            <div class="flex gap-2 pt-6 border-t">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold flex items-center gap-2">
                    <i class="fas fa-save"></i> Guardar Permisos
                </button>
                <a href="{{ route('seguridad.roles.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <!-- Usuarios con este rol -->
    @if($role->users_count > 0)
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Usuarios con este rol</h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold">Nombre</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold">Email</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold">Empresa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($role->users as $user)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm">{{ $user->name }}</td>
                                <td class="px-4 py-2 text-sm">{{ $user->email }}</td>
                                <td class="px-4 py-2 text-sm">{{ $user->empresa?->nombre ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
