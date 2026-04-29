@extends('layouts.app')

@section('title', 'Usuarios')
@section('page-title', 'Gestión de Usuarios')
@section('page-description', 'Administra todos los usuarios del sistema')

@section('content')
<div class="space-y-6">
    <!-- Actions Bar -->
    <div class="flex justify-between items-center">
        <div class="flex gap-2">
            <input type="text" placeholder="Buscar usuario..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <a href="{{ route('usuarios.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaUsuarios">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Rol</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Empresa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ $usuario->name }}&background=0D8ABC&color=fff" alt="{{ $usuario->name }}" class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $usuario->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $usuario->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $usuario->tipo_rol === 'admin' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $usuario->tipo_rol === 'tecnico' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $usuario->tipo_rol === 'coordinador' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $usuario->tipo_rol === 'operario' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $usuario->tipo_rol === 'cliente' ? 'bg-purple-100 text-purple-800' : '' }}
                            ">
                                @php
                                    $rolesMap = [
                                        'admin' => '👨‍💼 Administrador',
                                        'tecnico' => '🔧 Técnico',
                                        'coordinador' => '📋 Coordinador',
                                        'operario' => '👤 Operario',
                                        'cliente' => '🏢 Cliente',
                                    ];
                                @endphp
                                {{ $rolesMap[$usuario->tipo_rol] ?? ucfirst($usuario->tipo_rol) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $usuario->empresa?->nombre ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $usuario->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $usuario->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('usuarios.show', $usuario) }}" class="text-blue-600 hover:text-blue-900 transition" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="text-green-600 hover:text-green-900 transition" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-inbox text-4xl opacity-30"></i>
                                <p>No hay usuarios registrados</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#tablaUsuarios').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": 5 }
        ],
        "order": [[0, "asc"]],
        "pageLength": 10,
        "paging": true
    });
});
</script>
@endsection
