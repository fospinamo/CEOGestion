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
        <a href="{{ route('areas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nueva Área
        </a>
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
                                <a href="{{ route('areas.show', $area) }}" class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('areas.edit', $area) }}" class="text-yellow-600 hover:text-yellow-900"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('areas.destroy', $area) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
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
