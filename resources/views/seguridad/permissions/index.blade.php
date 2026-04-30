@extends('layouts.app')

@section('page-title', 'Permisos del Sistema')
@section('page-description', 'Lista de permisos disponibles')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Permisos</h1>

    <!-- Filtros -->
    <div class="bg-white shadow-md rounded-lg p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Módulo</label>
                <select name="module" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Todos</option>
                    @foreach($modules as $mod)
                        <option value="{{ $mod }}" {{ $module === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Recurso</label>
                <select name="resource" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Todos</option>
                    @foreach($resources as $res)
                        <option value="{{ $res }}" {{ $resource === $res ? 'selected' : '' }}>{{ $res }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Acción</label>
                <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Todos</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>{{ $act }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    Filtrar
                </button>
                <a href="{{ route('seguridad.permissions.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de permisos -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Módulo</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Recurso</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Acción</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Roles</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permissions as $permission)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $permission->name }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                {{ $permission->module ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">
                                {{ $permission->resource ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">
                                {{ $permission->action ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-800 font-semibold text-sm">
                                {{ $permission->roles_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('seguridad.permissions.show', $permission) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">No hay permisos registrados con los filtros aplicados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="px-4 py-3 border-t bg-gray-50">
            {{ $permissions->links() }}
        </div>
    </div>
</div>
@endsection
