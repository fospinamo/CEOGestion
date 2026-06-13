@extends('layouts.app')

@section('title', 'Equipos TI')
@section('page-title', 'Gestión de Equipos')
@section('page-description', 'Registro y control de equipos TI')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Equipos TI</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $equipos->count() }} equipos</p>
        </div>
        <a href="{{ route('parametros.equipos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Equipo
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <form method="GET" action="{{ route('parametros.equipos.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Filtro por Empresa -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-building text-blue-600"></i> Empresa
                </label>
                <select name="empresa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Todas --</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}" {{ request('empresa_id') == $empresa->id ? 'selected' : '' }}>
                            {{ $empresa->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Cliente -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-user text-green-600"></i> Cliente
                </label>
                <select name="cliente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Todas --</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->razon_social }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Tipo -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-microchip text-purple-600"></i> Tipo
                </label>
                <select name="tipo_equipo_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Todos --</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" {{ request('tipo_equipo_id') == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Estado -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-circle text-red-600"></i> Estado
                </label>
                <select name="estado_operativo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Todos --</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado }}" {{ request('estado_operativo') == $estado ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Botones -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                <a href="{{ route('parametros.equipos.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Botones de Descarga -->
    <div class="flex gap-2">
        <a href="{{ route('parametros.equipos.exportar.excel', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-file-excel"></i> Descargar Excel
        </a>
        <a href="{{ route('parametros.equipos.exportar.pdf', request()->query()) }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-file-pdf"></i> Descargar PDF
        </a>
        <button id="btnPrintTable" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaEquipos">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Código</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Marca / Modelo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Ubicación</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Contrato</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Empresa/Cliente</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($equipos as $equipo)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <p class="font-semibold text-gray-900">{{ $equipo->codigo_interno }}</p>
                            <p class="text-xs text-gray-500">SN: {{ $equipo->serial }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $equipo->tipoEquipo?->nombre ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-900">{{ $equipo->marca }} {{ $equipo->modelo }}</p>
                            @if($equipo->descripcion)
                                <p class="text-xs text-gray-600 mt-1 italic truncate" title="{{ $equipo->descripcion }}">
                                    {{ substr($equipo->descripcion, 0, 60) }}{{ strlen($equipo->descripcion) > 60 ? '...' : '' }}
                                </p>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-700">{{ $equipo->area?->nombre ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $equipo->area?->sede?->nombre ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-3">
                            @if($equipo->contrato)
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $equipo->contrato->numero_contrato }}
                                </span>
                                <p class="text-xs text-gray-600 mt-1">{{ substr($equipo->contrato->tipo_contrato, 0, 15) }}</p>
                            @else
                                <span class="text-gray-400 text-xs italic">Sin contrato</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-900 font-semibold">
                                @if($equipo->area?->sede?->cliente)
                                    <i class="fas fa-user text-green-600"></i> {{ $equipo->area->sede->cliente->razon_social }}
                                @elseif($equipo->area?->sede?->empresa)
                                    <i class="fas fa-building text-blue-600"></i> {{ $equipo->area->sede->empresa->nombre }}
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </p>
                        </td>
                        <td class="px-6 py-3">
                            @php
                                $colorMap = [
                                    'OPERATIVO' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                    'MANTENIMIENTO' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'REPARACION' => ['bg' => '#fed7aa', 'text' => '#92400e'],
                                    'BAJA' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    'OBSOLETO' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                ];
                                $colors = $colorMap[$equipo->estado_operativo] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                            @endphp
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                {{ $equipo->estado_operativo }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('parametros.equipos.show', $equipo) }}" class="text-blue-600 hover:text-blue-900" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('parametros.equipos.edit', $equipo) }}" class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('parametros.equipos.destroy', $equipo) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
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
                            <i class="fas fa-laptop text-3xl mb-2 opacity-50"></i>
                            <p>No hay equipos registrados</p>
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
    const table = $('#tablaEquipos').DataTable({
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

    // Botón de impresión
    $('#btnPrintTable').on('click', function() {
        const printWindow = window.open('', '', 'height=600,width=800');
        const tableHTML = document.querySelector('#tablaEquipos').outerHTML;
        const html = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Equipos TI</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f0f0f0; font-weight: bold; }
                    h1 { text-align: center; color: #333; }
                    .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <h1>Listado de Equipos TI</h1>
                <p>Fecha: ${new Date().toLocaleString()}</p>
                ${tableHTML}
                <div class="footer">
                    <p>Generado por CEOGestion - ${new Date().getFullYear()}</p>
                </div>
            </body>
            </html>
        `;
        printWindow.document.write(html);
        printWindow.document.close();
        printWindow.print();
    });
});
</script>
@endsection
