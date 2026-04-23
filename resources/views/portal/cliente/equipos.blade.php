@extends('portal.cliente.layout')

@section('title', 'Equipos - Portal del Cliente')

@section('content')
    <h1 style="margin-bottom: 30px; font-size: 28px; font-weight: 700;">
        <i class="fas fa-server" style="margin-right: 10px; color: #3b82f6;"></i>
        Equipos
    </h1>

    @if($equipos->isEmpty())
        <div class="card" style="text-align: center; padding: 40px;">
            <i class="fas fa-inbox" style="font-size: 48px; color: #d1d5db; margin-bottom: 20px;"></i>
            <p style="color: #6b7280; font-size: 16px;">No tienes equipos registrados.</p>
        </div>
    @else
        <div class="card">
            <table id="tablaEquipos" class="responsive" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Código Interno</th>
                        <th>Tipo de Equipo</th>
                        <th>Ubicación</th>
                        <th>Serial/Modelo</th>
                        <th>Sede</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipos as $equipo)
                        <tr>
                            <td>
                                <strong style="color: #3b82f6;">{{ $equipo->codigo_interno }}</strong>
                            </td>
                            <td>
                                {{ $equipo->tipo->nombre ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $equipo->ubicacion ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $equipo->numero_serie ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $equipo->area->sede->nombre ?? 'N/A' }}
                            </td>
                            <td>
                                @php
                                    $estadoColor = $equipo->estado_operativo ? '#d1fae5' : '#fee2e2';
                                    $estadoTextColor = $equipo->estado_operativo ? '#065f46' : '#991b1b';
                                    $estado = $equipo->estado_operativo ? 'Operativo' : 'Inoperativo';
                                @endphp
                                <span class="badge" style="background: {{ $estadoColor }}; color: {{ $estadoTextColor }};">
                                    {{ $estado }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

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
