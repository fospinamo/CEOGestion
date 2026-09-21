@extends('layouts.app')

@section('title', 'Cotizaciones')
@section('page-title', 'Gestión de Cotizaciones')
@section('page-description', 'Cotizaciones de servicios TI')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Cotizaciones</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $cotizaciones->count() }} cotizaciones</p>
        </div>
        @can('cotizaciones.crear')
        <a href="{{ route('cotizaciones.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-plus"></i> Nueva Cotización
        </a>
        @endcan
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden overflow-x-auto">
        <table class="w-full" id="tablaCotizaciones">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">N° Cotización</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Cliente</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Fecha</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Vencimiento</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipo Servicio</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Valor Total</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($cotizaciones as $cotizacion)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <p class="font-semibold text-gray-900">{{ $cotizacion->numero_cotizacion }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-900">{{ $cotizacion->cliente->razon_social ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-700">{{ $cotizacion->fecha_cotizacion?->format('d/m/Y') ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-700">{{ $cotizacion->fecha_vencimiento?->format('d/m/Y') ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-700">{{ str_replace('_', ' ', $cotizacion->tipo_servicio) }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm font-semibold text-gray-900">{{ $cotizacion->valor_formateado }}</p>
                        </td>
                        <td class="px-6 py-3">
                            @php
                                $estadoColors = [
                                    'BORRADOR' => 'gray',
                                    'ENVIADA' => 'blue',
                                    'APROBADA' => 'green',
                                    'RECHAZADA' => 'red',
                                    'VENCIDA' => 'yellow',
                                ];
                                $color = $estadoColors[$cotizacion->estado] ?? 'gray';
                            @endphp
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                {{ $cotizacion->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2 flex-wrap">
                                <a href="{{ route('cotizaciones.show', $cotizacion) }}" class="text-blue-600 hover:text-blue-900 transition" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('cotizaciones.editar')
                                <a href="{{ route('cotizaciones.edit', $cotizacion) }}" class="text-yellow-600 hover:text-yellow-900 transition" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('cotizaciones.eliminar')
                                <form action="{{ route('cotizaciones.destroy', $cotizacion) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar cotización?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                            <p>No hay cotizaciones registradas</p>
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
    if (!$.fn.DataTable.isDataTable('#tablaCotizaciones')) {
        $('#tablaCotizaciones').DataTable({
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
            "pageLength": 10
        });
    }
});
</script>
@endsection
