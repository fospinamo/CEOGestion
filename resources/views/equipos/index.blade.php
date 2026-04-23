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
        <a href="{{ route('equipos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Equipo
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaEquipos">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Código</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Marca / Modelo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Ubicación</th>
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
                                {{ $equipo->tipoEquipo->nombre }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-900">{{ $equipo->marca }} {{ $equipo->modelo }}</p>
                        </td>
                        <td class="px-6 py-3">
                            <p class="text-sm text-gray-700">{{ $equipo->area->nombre }}</p>
                            <p class="text-xs text-gray-500">{{ $equipo->area->sede->nombre }}</p>
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
                                <a href="{{ route('equipos.show', $equipo) }}" class="text-blue-600 hover:text-blue-900" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('equipos.edit', $equipo) }}" class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('equipos.destroy', $equipo) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
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
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
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
    $('#tablaEquipos').DataTable({
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
