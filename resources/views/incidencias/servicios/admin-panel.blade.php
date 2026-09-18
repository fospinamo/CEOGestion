@extends('layouts.app')

@section('title', 'Servicios Asignados')
@section('page-title', 'Panel de Gestión de Servicios')
@section('page-description', 'Visualización y gestión de servicios asignados a técnicos')

@section('content')
<div class="space-y-6">
    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-blue-600">{{ $totalAsignados }}</div>
            <p class="text-gray-600 text-sm mt-1">Servicios Asignados</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-yellow-600">{{ $pendientes }}</div>
            <p class="text-gray-600 text-sm mt-1">Pendientes</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-orange-600">{{ $enProceso }}</div>
            <p class="text-gray-600 text-sm mt-1">En Proceso</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-green-600">{{ $completados }}</div>
            <p class="text-gray-600 text-sm mt-1">Completados</p>
        </div>
    </div>

    <!-- Filtro de búsqueda -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" id="searchServicio" placeholder="Buscar por #ID, equipo, cliente, ubicación..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                    oninput="filtrarServicios()">
            </div>
            <div>
                <select id="filterEstado" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" onchange="filtrarServicios()">
                    <option value="">Todos los estados</option>
                    <option value="PENDIENTE">Pendientes</option>
                    <option value="EN_PROCESO">En Proceso</option>
                    <option value="RESUELTO">Resueltos</option>
                    <option value="CERRADO">Cerrados</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Lista de Técnicos con sus Servicios -->
    <div class="space-y-4" id="tecnicos-container">
        @forelse($tecnicos as $tecnico)
            <div class="bg-white rounded-lg shadow overflow-hidden tecnico-card">
                <!-- Encabezado del Técnico -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 cursor-pointer"
                    onclick="toggleTecnico(this, {{ $tecnico->id }})">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-white">
                                {{ $tecnico->name }}
                            </h3>
                            <p class="text-blue-100 text-sm">{{ $tecnico->email }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-white">{{ $tecnico->servicios->count() }}</div>
                            <p class="text-blue-100 text-sm">servicios asignados</p>
                        </div>
                    </div>
                </div>

                <!-- Contenedor de Servicios (expandido por defecto) -->
                <div id="tecnico-{{ $tecnico->id }}" class="servicios-container bg-gray-50 p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" style="display: grid;">
                    @forelse($tecnico->servicios as $servicio)
                        <div class="bg-white rounded-lg border-2 p-4 servicio-item"
                            data-search="{{ $servicio->id }} {{ $servicio->equipo->codigo_activo_cliente ?? '' }} {{ $servicio->equipo->area->sede->cliente->razon_social ?? '' }} {{ $servicio->equipo->area->sede->nombre ?? '' }} {{ $servicio->equipo->area->nombre ?? '' }} {{ $servicio->tecnico_asignado ?? '' }}"
                            data-estado="{{ $servicio->estado }}"
                            style="border-color: {{ $servicio->estadoServicio->color ?? '#e5e7eb' }};">
                            <div class="mb-2">
                                <h4 class="font-bold text-gray-900">
                                    <a href="{{ route('incidencias.servicios.show', $servicio) }}" class="text-blue-600 hover:text-blue-900">
                                        #{{ $servicio->id }}
                                    </a>
                                </h4>
                                <p class="text-xs text-gray-500">{{ $servicio->equipo->codigo_activo_cliente ?? 'N/A' }}</p>
                            </div>

                            <div class="mb-2">
                                <p class="text-sm text-gray-700">
                                    <strong>Ubicación:</strong> {{ $servicio->equipo->area->sede->nombre ?? '' }}/{{ $servicio->equipo->area->nombre ?? '' }}
                                </p>
                                <p class="text-sm text-gray-700">
                                    <strong>Cliente:</strong> {{ $servicio->equipo->area->sede->cliente->razon_social ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="mb-2">
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ $servicio->tipo_servicio }}
                                </span>

                                @php
                                    $priorityColors = [
                                        'BAJA' => 'bg-green-100 text-green-800',
                                        'MEDIA' => 'bg-yellow-100 text-yellow-800',
                                        'ALTA' => 'bg-orange-100 text-orange-800',
                                        'URGENTE' => 'bg-red-100 text-red-800',
                                        'CRITICA' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$servicio->prioridad] ?? 'bg-gray-100 text-gray-800' }} ml-1">
                                    {{ $servicio->prioridad }}
                                </span>
                            </div>

                            <div class="mb-3">
                                @php
                                    $estadoBadgeColors = [
                                        'PENDIENTE' => 'background-color: #fef3c7; color: #92400e;',
                                        'EN_PROCESO' => 'background-color: #dbeafe; color: #1e40af;',
                                        'RESUELTO' => 'background-color: #d1fae5; color: #065f46;',
                                        'CERRADO' => 'background-color: #e5e7eb; color: #374151;',
                                    ];
                                @endphp
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full"
                                    style="{{ $estadoBadgeColors[$servicio->estado] ?? 'background-color: #f3f4f6; color: #6b7280;' }}">
                                    {{ $servicio->estado }}
                                </span>
                            </div>

                            <div class="text-xs text-gray-500 mb-3">
                                {{ $servicio->fecha_asignacion ? $servicio->fecha_asignacion->format('d/m/Y H:i') : 'N/A' }}
                            </div>

                            <div class="flex gap-2 flex-wrap">
                                @can('servicios.ver')
                                <a href="{{ route('incidencias.servicios.show', $servicio) }}" class="flex-1 min-w-[80px] px-2 py-1 bg-blue-100 text-blue-800 rounded text-center text-xs font-semibold hover:bg-blue-200 transition">
                                    Ver
                                </a>
                                @endcan
                                @can('servicios.editar')
                                <a href="{{ route('incidencias.servicios.edit', $servicio) }}" class="flex-1 min-w-[80px] px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-center text-xs font-semibold hover:bg-yellow-200 transition">
                                    Editar
                                </a>
                                @endcan
                                @if($servicio->persona_receptora_nombre)
                                    @can('servicios.imprimir-pdf')
                                    <a href="{{ route('incidencias.servicios.download-informe-pdf', $servicio) }}" class="flex-1 min-w-[80px] px-2 py-1 bg-red-100 text-red-800 rounded text-center text-xs font-semibold hover:bg-red-200 transition">
                                        PDF
                                    </a>
                                    @endcan
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full px-4 py-3 text-center text-gray-500 text-sm">
                            Sin servicios asignados
                        </div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                <p class="text-gray-500">No hay técnicos con servicios asignados</p>
            </div>
        @endforelse
    </div>

    <!-- Sin resultados de búsqueda -->
    <div id="no-results" class="bg-white rounded-lg shadow p-6 text-center hidden">
        <i class="fas fa-search text-3xl text-gray-300 mb-2"></i>
        <p class="text-gray-500">No se encontraron servicios con los criterios de búsqueda</p>
    </div>
</div>

<script>
function toggleTecnico(header, tecnicoId) {
    const container = document.getElementById(`tecnico-${tecnicoId}`);
    const isVisible = container.style.display !== 'none';

    if (isVisible) {
        container.style.display = 'none';
        header.classList.remove('shadow-md');
    } else {
        container.style.display = 'grid';
        header.classList.add('shadow-md');
    }
}

function filtrarServicios() {
    const search = document.getElementById('searchServicio').value.toLowerCase();
    const estado = document.getElementById('filterEstado').value;
    const items = document.querySelectorAll('.servicio-item');
    let totalVisible = 0;

    items.forEach(item => {
        const texto = item.getAttribute('data-search').toLowerCase();
        const itemEstado = item.getAttribute('data-estado');

        const matchSearch = !search || texto.includes(search);
        const matchEstado = !estado || itemEstado === estado;

        if (matchSearch && matchEstado) {
            item.style.display = '';
            totalVisible++;
        } else {
            item.style.display = 'none';
        }
    });

    // Mostrar/ocultar técnicos sin servicios visibles
    document.querySelectorAll('.tecnico-card').forEach(card => {
        const visibleServices = card.querySelectorAll('.servicio-item:not([style*="display: none"])');
        const container = card.querySelector('.servicios-container');
        if (visibleServices.length === 0 && (search || estado)) {
            card.style.display = 'none';
        } else {
            card.style.display = '';
            if (search || estado) {
                container.style.display = 'grid';
            }
        }
    });

    // Mostrar mensaje sin resultados
    const noResults = document.getElementById('no-results');
    if (totalVisible === 0 && (search || estado)) {
        noResults.classList.remove('hidden');
    } else {
        noResults.classList.add('hidden');
    }
}
</script>
@endsection
