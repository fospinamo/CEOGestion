@extends('layouts.app')

@section('title', 'Marcas')
@section('page-title', 'Gestión de Marcas')
@section('page-description', 'Gestiona las marcas/fabricantes de equipos TI')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Marcas</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $marcas->count() }} marcas</p>
        </div>
        <a href="{{ route('parametros.marcas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nueva Marca
        </a>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaMarcas">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Descripción</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Equipos</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($marcas as $marca)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $marca->nombre }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600">{{ $marca->descripcion ?? 'Sin descripción' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $marca->equipos_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($marca->estado)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Activo
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('parametros.marcas.show', $marca) }}" class="px-3 py-1 text-sm bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('parametros.marcas.edit', $marca) }}" class="px-3 py-1 text-sm bg-yellow-50 hover:bg-yellow-100 text-yellow-600 rounded-lg transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($marca->equipos_count === 0)
                                    <form action="{{ route('parametros.marcas.destroy', $marca) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar esta marca?')" class="px-3 py-1 text-sm bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                            <p>No hay marcas registradas</p>
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
    $('#tablaMarcas').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": 4 }
        ],
        "order": [[0, "asc"]],
        "pageLength": 10
    });
});
</script>
@endsection
