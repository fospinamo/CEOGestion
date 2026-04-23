@extends('layouts.app')

@section('title', 'Servicios TI')
@section('page-title', 'Gestión de Servicios')
@section('page-description', 'Registro de atenciones y soporte TI')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Servicios TI</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $servicios->count() }} servicios</p>
        </div>
        <a href="{{ route('servicios.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Servicio
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaServicios">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Equipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Prioridad</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Técnico</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($servicios as $servicio)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <p class="font-semibold text-gray-900">{{ $servicio->equipo->codigo_interno }}</p>
                            <p class="text-xs text-gray-500">{{ $servicio->equipo->area->nombre }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $servicio->tipo_servicio }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            @php
                                $priorityMap = [
                                    'BAJA' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                    'MEDIA' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'ALTA' => ['bg' => '#fed7aa', 'text' => '#92400e'],
                                    'CRITICA' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                ];
                                $colors = $priorityMap[$servicio->prioridad] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                            @endphp
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                {{ $servicio->prioridad }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-900">{{ $servicio->tecnico_asignado }}</p>
                        </td>
                        <td class="px-6 py-3">
                            @php
                                $statusMap = [
                                    'CERRADO' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                    'RESUELTO' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                    'EN_PROCESO' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'PENDIENTE' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    'CANCELADO' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                ];
                                $colors = $statusMap[$servicio->estado] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                            @endphp
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                {{ $servicio->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('servicios.show', $servicio) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('servicios.edit', $servicio) }}" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-tools text-3xl mb-2 opacity-50"></i>
                            <p>No hay servicios registrados</p>
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
    $('#tablaServicios').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": 5 } // Deshabilitar ordenamiento en columna Acciones
        ],
        "order": [[0, "asc"]],
        "pageLength": 10
    });
});
</script>
@endsection
