@extends('layouts.app')

@section('title', 'Clientes')
@section('page-title', 'Gestión de Clientes')
@section('page-description', 'Lista de clientes que contratan servicios TI')

@section('content')
<div class="space-y-6">
    <!-- Header con botón crear -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Clientes</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $clientes->count() }} clientes</p>
        </div>
        <a href="{{ route('parametros.clientes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Cliente
        </a>
    </div>

    <!-- Tabla de Clientes -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaClientes">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Razón Social</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Documento</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Empresa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($clientes as $cliente)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <p class="font-semibold text-gray-900">{{ $cliente->razon_social }}</p>
                            <p class="text-xs text-gray-500">{{ $cliente->nombre_comercial ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $cliente->tipo_documento }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-700">{{ $cliente->documento }}</p>
                            @if($cliente->digito_verificacion)
                                <p class="text-xs text-gray-500">DV: {{ $cliente->digito_verificacion }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-700">{{ $cliente->email_principal }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-700">{{ $cliente->empresa->nombre }}</p>
                        </td>
                        <td class="px-6 py-3">
                            @if($cliente->estado)
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('parametros.clientes.show', $cliente) }}" class="text-blue-600 hover:text-blue-900" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('parametros.clientes.edit', $cliente) }}" class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('parametros.clientes.destroy', $cliente) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar cliente?')">
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
                            <p>No hay clientes registrados</p>
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
    $('#tablaClientes').DataTable({
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
