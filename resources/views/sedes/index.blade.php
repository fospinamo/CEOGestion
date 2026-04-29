@extends('layouts.app')

@section('title', 'Sedes')
@section('page-title', 'Gestión de Sedes')
@section('page-description', 'Administra todas las sedes de la empresa y clientes')

@section('content')
<div class="space-y-6">
    <!-- Actions Bar -->
    <div class="flex justify-between items-center">
        <div class="flex gap-2">
            <input type="text" placeholder="Buscar sede..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <a href="{{ route('sedes.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus"></i> Nueva Sede
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaSedes">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Código</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Propietario</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Municipio</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sedes as $sede)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $sede->nombre }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $sede->codigo }}</td>
                        <td class="px-6 py-4 text-gray-700">
                            @if($sede->esDeEmpresa())
                                <span class="font-semibold text-blue-600">{{ $sede->empresa->nombre }}</span>
                            @else
                                <span class="font-semibold text-green-600">{{ $sede->cliente->razon_social }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            @if($sede->esDeEmpresa())
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-semibold">Empresa</span>
                            @else
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-semibold">Cliente</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $sede->municipio->nombre }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $sede->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $sede->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('sedes.show', $sede) }}" class="text-blue-600 hover:text-blue-900 transition" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('sedes.edit', $sede) }}" class="text-green-600 hover:text-green-900 transition" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('sedes.destroy', $sede) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro?')">
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
                                <p>No hay sedes registradas</p>
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
    $('#tablaSedes').DataTable({
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
