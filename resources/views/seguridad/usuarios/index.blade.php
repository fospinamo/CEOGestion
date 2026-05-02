@extends('layouts.app')

@section('page-title', 'Usuarios del Sistema')
@section('page-description', 'Gestión de usuarios')

@section('content')
<div class="space-y-6">
    <!-- Botón crear usuario -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Usuarios</h1>
        @if(auth()->user()->hasPermission('usuarios.crear'))
            <a href="{{ route('seguridad.usuarios.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Nuevo Usuario
            </a>
        @endif
    </div>

    <!-- Tabla de usuarios -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Rol</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Empresa</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $usuario->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $usuario->email }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $usuario->role?->name ?? 'Sin rol' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $usuario->empresa?->nombre ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($usuario->estado)
                                <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle"></i> Activo
                                </span>
                            @else
                                <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle"></i> Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm space-x-2 flex">
                            <a href="{{ route('seguridad.usuarios.show', $usuario) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(auth()->user()->hasPermission('usuarios.editar'))
                                <a href="{{ route('seguridad.usuarios.edit', $usuario) }}" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif
                            @if(auth()->user()->hasPermission('usuarios.eliminar'))
                                <form method="POST" action="{{ route('seguridad.usuarios.destroy', $usuario) }}" style="display:inline;" onsubmit="return confirm('¿Eliminar usuario?');">
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
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">No hay usuarios registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="px-4 py-3 border-t bg-gray-50">
            {{ $usuarios->links() }}
        </div>
    </div>
</div>
@endsection
