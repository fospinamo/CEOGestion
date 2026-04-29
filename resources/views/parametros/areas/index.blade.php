@extends('layouts.app')
@section('title', 'Áreas')
@section('page-title', 'Gestión de Áreas')
@section('page-description', 'Áreas dentro de las sedes')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Áreas</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $areas->count() }} áreas</p>
        </div>
        <a href="{{ route('parametros.areas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nueva Área
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
        <form method="GET" action="{{ route('parametros.areas.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-building text-blue-600"></i> Filtrar por Empresa
                </label>
                <select name="empresa_id" id="empresaFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Todas las empresas --</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}" {{ request('empresa_id') == $empresa->id ? 'selected' : '' }}>
                            {{ $empresa->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-user text-green-600"></i> Filtrar por Cliente
                </label>
                <select name="cliente_id" id="clienteFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Todos los clientes --</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->razon_social }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                <a href="{{ route('parametros.areas.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            </div>
        </form>

        <!-- Indicador de filtro activo -->
        @if(request('empresa_id') || request('cliente_id'))
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle"></i>
                    Filtro activo:
                    @if(request('empresa_id'))
                        <span class="font-semibold">Empresa: {{ $empresas->find(request('empresa_id'))->nombre ?? 'N/A' }}</span>
                    @endif
                    @if(request('cliente_id'))
                        <span class="font-semibold">Cliente: {{ $clientes->find(request('cliente_id'))->razon_social ?? 'N/A' }}</span>
                    @endif
                </p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaAreas">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Sede</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Responsable</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Riesgo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($areas as $area)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 font-semibold text-gray-900">{{ $area->nombre }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $area->sede->nombre }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $area->responsable_nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3">
                            @php
                                $colorMap = [
                                    'CRITICO' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    'ALTO' => ['bg' => '#fed7aa', 'text' => '#92400e'],
                                    'MEDIO' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'BAJO' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                ];
                                $colors = $colorMap[$area->nivel_riesgo] ?? $colorMap['BAJO'];
                            @endphp
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                {{ $area->nivel_riesgo }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            @php
                                $statusStyle = $area->estado 
                                    ? ['bg' => '#dcfce7', 'text' => '#166534'] 
                                    : ['bg' => '#fee2e2', 'text' => '#991b1b'];
                            @endphp
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['text'] }};">
                                {{ $area->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('parametros.areas.show', $area) }}" class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('parametros.areas.edit', $area) }}" class="text-yellow-600 hover:text-yellow-900"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('parametros.areas.destroy', $area) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <p>No hay áreas registradas</p>
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
    // Limpiar filtro opuesto cuando se selecciona uno
    $('#empresaFilter').change(function() {
        if ($(this).val()) {
            $('#clienteFilter').val('');
        }
    });

    $('#clienteFilter').change(function() {
        if ($(this).val()) {
            $('#empresaFilter').val('');
        }
    });

    // DataTable
    $('#tablaAreas').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": 5 }
        ],
        "order": [[0, "asc"]],
        "pageLength": 10
    });
});
</script>
@endsection
