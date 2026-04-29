@extends('layouts.app')

@section('title', 'Contratos')
@section('page-title', 'Gestión de Contratos')
@section('page-description', 'Lista de contratos de servicios TI')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Contratos</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $contratos->count() }} contratos</p>
        </div>
        <a href="{{ route('contratos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Contrato
        </a>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaContratos">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Número</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Cliente</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Período</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Valor</th>
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
                            <p class="text-sm text-gray-900">{{ $contrato->cliente?->razon_social ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $contrato->cliente?->empresa?->nombre ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ str_replace('_', ' ', $contrato->tipo_contrato) }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-700">{{ $contrato->fecha_inicio->format('d/m/Y') }} - {{ $contrato->fecha_fin->format('d/m/Y') }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ number_format($contrato->valor_contrato, 0, ',', '.') }} {{ $contrato->moneda }}
                            </p>
                        </td>
                        <td class="px-6 py-3">
                            @php
                                $colorMap = [
                                    'ACTIVO' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                    'BORRADOR' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'VENCIDO' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    'TERMINADO' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                    'RENOVADO' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                ];
                                $colors = $colorMap[$contrato->estado] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                            @endphp
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                {{ $contrato->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('contratos.show', $contrato) }}" class="text-blue-600 hover:text-blue-900" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('contratos.edit', $contrato) }}" class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('contratos.destroy', $contrato) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar contrato?')">
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
                            <i class="fas fa-file-contract text-3xl mb-2 opacity-50"></i>
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
    $('#tablaContratos').DataTable({
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
