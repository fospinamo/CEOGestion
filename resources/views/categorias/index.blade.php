@extends('layouts.app')

@section('title', 'Categorías de Equipos')
@section('page-title', 'Gestión de Categorías')
@section('page-description', 'Crea y gestiona las categorías parametrizables para tipos de equipos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Categorías</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $categorias->count() }} categorías</p>
        </div>
        <a href="{{ route('categorias.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nueva Categoría
        </a>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaCategorias">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Slug</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Icono</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Color</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Tipos</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($categorias as $categoria)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 font-semibold text-gray-900">{{ $categoria->nombre }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">
                            <code class="bg-gray-100 px-2 py-1 rounded">{{ $categoria->slug }}</code>
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if($categoria->icono)
                                <i class="fas {{ $categoria->icono }} text-lg" style="color: {{ $categoria->color }};"></i>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded border-2" style="background-color: {{ $categoria->color }}; border-color: {{ $categoria->color }};"></div>
                                <code class="text-sm text-gray-600">{{ $categoria->color }}</code>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                {{ $categoria->cantidadTipos() }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $categoria->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $categoria->estado ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('categorias.show', $categoria) }}" class="text-blue-600 hover:text-blue-900" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('categorias.edit', $categoria) }}" class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta categoría?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                            <p>No hay categorías registradas</p>
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
    $('#tablaCategorias').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": 6 }
        ],
        "order": [[0, "asc"]],
        "pageLength": 10
    });
});
</script>
@endsection
