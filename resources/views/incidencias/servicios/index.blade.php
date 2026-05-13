@extends('layouts.app')

@section('title', 'Servicios TI')
@section('page-title', 'Gestión de Servicios')
@section('page-description', 'Registro de atenciones y soporte TI')

@section('content')
<div class="space-y-6">
    <!-- Encabezado y botón nuevo -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Servicios TI</h2>
            <p class="text-gray-600 text-sm mt-1">
                Total: <span class="font-bold">{{ $servicios->count() }}</span> servicios
                @if(isset($clienteFilter) && $clienteFilter || isset($fechaDesde) && $fechaDesde || isset($fechaHasta) && $fechaHasta || isset($estadoFilter) && $estadoFilter)
                    (filtrados)
                @endif
            </p>
        </div>
        <a href="{{ route('incidencias.servicios.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Servicio
        </a>
    </div>

    <!-- Panel de Filtros -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">🔍 Filtros de Búsqueda</h3>
        
        <form method="GET" action="{{ route('incidencias.servicios.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Filtro por Cliente -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Cliente</label>
                <select name="cliente_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los clientes</option>
                    @if(isset($clientes) && $clientes->count() > 0)
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ (isset($clienteFilter) && $clienteFilter == $cliente->id) ? 'selected' : '' }}>
                                {{ $cliente->razon_social }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Filtro por Fecha Desde -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Desde</label>
                <input type="date" name="fecha_desde" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    value="{{ isset($fechaDesde) ? $fechaDesde : '' }}">
            </div>

            <!-- Filtro por Fecha Hasta -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Hasta</label>
                <input type="date" name="fecha_hasta" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    value="{{ isset($fechaHasta) ? $fechaHasta : '' }}">
            </div>

            <!-- Filtro por Estado -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Estado</label>
                <select name="estado" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="SIN_ASIGNAR" {{ (isset($estadoFilter) && $estadoFilter === 'SIN_ASIGNAR') ? 'selected' : '' }}>⚠️ Sin Asignar</option>
                    <option value="PENDIENTE" {{ (isset($estadoFilter) && $estadoFilter === 'PENDIENTE') ? 'selected' : '' }}>⏳ Pendiente</option>
                    <option value="ASIGNADO" {{ (isset($estadoFilter) && $estadoFilter === 'ASIGNADO') ? 'selected' : '' }}>📋 Asignado</option>
                    @if(isset($estados) && $estados->count() > 0)
                        @foreach($estados as $estado)
                            <option value="{{ $estado->nombre }}" {{ (isset($estadoFilter) && $estadoFilter === $estado->nombre) ? 'selected' : '' }}>
                                {{ $estado->nombre }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Botones de acción -->
            <div class="flex gap-2 items-end">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    🔎 Buscar
                </button>
                <a href="{{ route('incidencias.servicios.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition text-center">
                    ↺ Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de Servicios -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaServicios">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Cliente</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Equipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Prioridad</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Técnico</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Fecha</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($servicios as $servicio)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <a href="{{ route('incidencias.servicios.show', $servicio) }}" class="text-blue-600 hover:text-blue-900 font-bold">
                                #{{ $servicio->id }}
                            </a>
                        </td>
                        <td class="px-6 py-3">
                            @if($servicio->equipo?->area?->sede?->cliente)
                                <p class="text-sm font-semibold text-gray-900">{{ $servicio->equipo->area->sede->cliente->razon_social }}</p>
                            @else
                                <p class="text-sm text-gray-500 italic">Sin equipo asociado</p>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            @if($servicio->equipo)
                                <p class="font-semibold text-gray-900">{{ $servicio->equipo->codigo_interno }}</p>
                                <p class="text-xs text-gray-500">{{ $servicio->equipo->area?->nombre ?? 'N/A' }}</p>
                            @else
                                <p class="text-sm text-gray-500 italic">Sin equipo</p>
                            @endif
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
                            @if($servicio->tecnicoResponsable)
                                <p class="text-sm font-semibold text-gray-900">{{ $servicio->tecnicoResponsable->name }}</p>
                                <p class="text-xs text-gray-500">{{ $servicio->tecnicoResponsable->telefono }}</p>
                            @else
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Sin asignar
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            @if($servicio->estadoServicio)
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $servicio->estadoServicio->color }}40; color: {{ $servicio->estadoServicio->color }};">
                                    {{ $servicio->estadoServicio->nombre }}
                                </span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $servicio->estado }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-900">{{ $servicio->fecha_solicitud->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $servicio->fecha_solicitud->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('incidencias.servicios.show', $servicio) }}" class="text-blue-600 hover:text-blue-900 font-bold" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('incidencias.servicios.edit', $servicio) }}" class="text-yellow-600 hover:text-yellow-900 font-bold" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($servicio->persona_receptora_nombre)
                                    <a href="{{ route('incidencias.servicios.download-informe-pdf', $servicio) }}" class="text-red-600 hover:text-red-900 font-bold" title="Descargar PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                @endif
                                @if(!$servicio->tecnico_id)
                                    <a href="{{ route('incidencias.servicios.assign', $servicio) }}" class="text-green-600 hover:text-green-900 font-bold" title="Asignar Técnico">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
                                @endif
                                <form action="{{ route('incidencias.servicios.destroy', $servicio) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                            <p>No hay servicios que coincidan con los filtros</p>
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
        "columnDefs": [
            { "orderable": false, "targets": 8 } // Deshabilitar ordenamiento en columna Acciones
        ],
        "order": [[0, "desc"]],
        "pageLength": 15,
        "paging": true,
        "searching": true,
        "autoWidth": false,
        "fixedHeader": false
    });
});
</script>
@endsection
