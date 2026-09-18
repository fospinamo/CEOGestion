@extends('portal.cliente.layout')

@section('title', 'Equipos - Portal del Cliente')

@section('content')
    <h1 class="text-2xl font-bold mb-6">
        <i class="fas fa-server text-blue-500 mr-2"></i>
        Equipos
    </h1>

    @if($equipos->isEmpty())
        <x-card class="text-center py-10">
            <x-empty-state icon="fa-server" message="No tienes equipos registrados." />
        </x-card>
    @else
        <x-card :padding="false">
            <table id="tablaEquipos" class="w-full responsive">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Código Interno</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tipo de Equipo</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Ubicación</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Serial/Modelo</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Sede</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($equipos as $equipo)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <strong class="text-blue-500">{{ $equipo->codigo_activo_cliente }}</strong>
                            </td>
                            <td class="px-4 py-3">{{ $equipo->tipo->nombre ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $equipo->ubicacion ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $equipo->numero_serie ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $equipo->area->sede->nombre ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <x-badge :variant="$equipo->estado_operativo ? 'green' : 'red'">
                                    {{ $equipo->estado_operativo ? 'Operativo' : 'Inoperativo' }}
                                </x-badge>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-card>

        @section('scripts')
            <script>
                $(document).ready(function() {
                    $('#tablaEquipos').DataTable({
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
    @endif
@endsection
