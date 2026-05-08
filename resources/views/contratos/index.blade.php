@extends('layouts.app')

@section('title', 'Contratos')
@section('page-title', 'Gestión de Contratos')
@section('page-description', 'Contratos de servicios TI con clientes')

@section('content')
<div class="space-y-6">
    <!-- Header con botón crear -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Contratos</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $contratos->count() }} contratos</p>
        </div>
        <a href="{{ route('parametros.contratos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-plus"></i> Nuevo Contrato
        </a>
    </div>

    <!-- Tabla de Contratos -->
    <div class="bg-white rounded-lg shadow overflow-hidden overflow-x-auto">
        <table class="w-full" id="tablaContratos">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Número</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Cliente</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Valor</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Inicio</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Fin</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($contratos as $contrato)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <p class="font-semibold text-gray-900">{{ $contrato->numero_contrato }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <p class="font-semibold text-gray-900">{{ $contrato->cliente->razon_social }}</p>
                            <p class="text-xs text-gray-500">{{ $contrato->cliente->empresa->nombre ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ str_replace('_', ' ', $contrato->tipo_contrato) }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <p class="font-semibold text-gray-900">
                                {{ $contrato->moneda === 'COP' ? '$' : ($contrato->moneda === 'USD' ? 'US$' : '€') }} {{ number_format($contrato->valor_contrato, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $contrato->modalidad }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-700">{{ $contrato->fecha_inicio->format('d/m/Y') }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-700">{{ $contrato->fecha_fin->format('d/m/Y') }}</p>
                        </td>
                        <td class="px-6 py-3">
                            @php
                                $estatusColors = [
                                    'BORRADOR' => 'gray',
                                    'ACTIVO' => 'green',
                                    'VENCIDO' => 'red',
                                    'TERMINADO' => 'yellow',
                                    'RENOVADO' => 'blue'
                                ];
                                $color = $estatusColors[$contrato->estado] ?? 'gray';
                            @endphp
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                {{ $contrato->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2 flex-wrap">
                                <a href="{{ route('parametros.contratos.show', $contrato) }}" class="text-blue-600 hover:text-blue-900 transition" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('parametros.contratos.edit', $contrato) }}" class="text-yellow-600 hover:text-yellow-900 transition" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('parametros.contratos.destroy', $contrato) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar contrato?')">
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
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                            <p>No hay contratos registrados</p>
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
    // Inicializar DataTable si aún no está inicializado
    if (!$.fn.DataTable.isDataTable('#tablaContratos')) {
        $('#tablaContratos').DataTable({
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron registros",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primera",
                    "last": "Última",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "responsive": true,
            "pageLength": 10,
            "order": [[0, "desc"]]
        });
    }
});
</script>
@endsection
