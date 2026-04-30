@extends('layouts.app')

@section('page-title', 'Roles del Sistema')
@section('page-description', 'Gestión de roles y permisos')

@section('content')
<div class="space-y-6">
    <!-- Botón crear rol -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Roles</h1>
        <a href="{{ route('seguridad.roles.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Rol
        </a>
    </div>

    <!-- Tabla de roles -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Slug</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Descripción</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Usuarios</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Permisos</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $role->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            <code class="bg-gray-100 px-2 py-1 rounded">{{ $role->slug }}</code>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ Str::limit($role->description, 50) }}</td>
                        <td class="px-4 py-3 text-center text-sm">
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-semibold">
                                {{ $role->users_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 font-semibold">
                                {{ $role->permissions_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm space-x-2 flex">
                            <a href="{{ route('seguridad.roles.show', $role) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('seguridad.roles.edit', $role) }}" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($role->users_count === 0)
                                <form method="POST" action="{{ route('seguridad.roles.destroy', $role) }}" style="display:inline;" onsubmit="return confirm('¿Eliminar rol?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">No hay roles registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
