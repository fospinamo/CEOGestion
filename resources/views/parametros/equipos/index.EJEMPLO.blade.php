@extends('layouts.app')

@section('title', 'Equipos - Módulo Parámetros')
@section('page-title', 'Gestión de Equipos')
@section('page-description', 'Listado y administración de equipos informáticos')

@section('content')
<div class="container-fluid">
    <!-- Encabezado con botón de crear -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">🖥️ Equipos</h1>
            <p class="text-gray-600 mt-1">Gestiona los equipos de tu infraestructura</p>
        </div>
        <a href="{{ route('parametros.equipos.create') }}" 
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            ➕ Nuevo Equipo
        </a>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-blue-600">{{ $equipos->total() }}</div>
            <p class="text-gray-600 text-sm mt-1">Total de Equipos</p>
        </div>
        <!-- Más tarjetas de métricas -->
    </div>

    <!-- Tabla de Equipos -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Código</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Descripción</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Sede</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Área</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($equipos as $equipo)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <a href="{{ route('parametros.equipos.show', $equipo) }}" 
                                class="text-blue-600 hover:text-blue-900 font-semibold">
                                {{ $equipo->codigo_interno }}
                            </a>
                        </td>
                        <td class="px-6 py-3">{{ $equipo->descripcion }}</td>
                        <td class="px-6 py-3">{{ $equipo->sede->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3">{{ $equipo->area->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('parametros.equipos.edit', $equipo) }}" 
                                    class="text-yellow-600 hover:text-yellow-900 font-bold" title="Editar">
                                    ✏️
                                </a>
                                <form action="{{ route('parametros.equipos.destroy', $equipo) }}" 
                                    method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold" title="Eliminar">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            No hay equipos registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $equipos->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Scripts específicos del módulo Parámetros
</script>
@endsection
