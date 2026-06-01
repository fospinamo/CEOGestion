<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #1a1a1a;
        }

        .header p {
            font-size: 9px;
            color: #666;
        }

        .metadata {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 9px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            background-color: #f0f0f0;
            border: 1px solid #999;
        }

        thead th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #999;
            font-size: 9px;
        }

        tbody td {
            padding: 7px;
            border: 1px solid #ddd;
            font-size: 9px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f0f0f0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #999;
            font-size: 8px;
            color: #999;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .summary {
            background-color: #f0f0f0;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 10px;
        }

        .summary p {
            margin: 3px 0;
        }

        page-break-after {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <p>CEOGestion - Sistema de Gestión</p>
    </div>

    <!-- Metadatos del reporte -->
    <div class="metadata">
        <div>
            <strong>Fecha de Reporte:</strong> {{ $fecha_reporte }}
        </div>
        <div>
            <strong>Total de Equipos:</strong> {{ $total_equipos }}
        </div>
    </div>

    <!-- Resumen -->
    <div class="summary">
        <p><strong>Resumen:</strong> Este reporte contiene el listado completo de {{ $total_equipos }} equipos registrados en el sistema.</p>
        <p><strong>Columnas:</strong> ID | Código | Marca | Modelo | Serie | Estado | Tipo | Área | Sede | Contrato | Cliente/Empresa</p>
    </div>

    <!-- Tabla de equipos -->
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">ID</th>
                <th style="width: 7%;">Código</th>
                <th style="width: 9%;">Marca</th>
                <th style="width: 9%;">Modelo</th>
                <th style="width: 11%;">Serie</th>
                <th style="width: 7%;">Estado</th>
                <th style="width: 9%;">Tipo Equipo</th>
                <th style="width: 9%;">Área</th>
                <th style="width: 9%;">Sede</th>
                <th style="width: 8%;">Contrato</th>
                <th style="width: 12%;">Cliente/Empresa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($equipos as $equipo)
                <tr>
                    <td class="text-center">{{ $equipo->id }}</td>
                    <td>{{ $equipo->codigo_activo_cliente }}</td>
                    <td>{{ $equipo->marca?->nombre ?? 'N/A' }}</td>
                    <td>{{ $equipo->modelo }}</td>
                    <td>{{ $equipo->serial }}</td>
                    <td class="text-center">
                        @if($equipo->estado_operativo === 'OPERATIVO')
                            <span class="badge badge-success">OPERATIVO</span>
                        @elseif($equipo->estado_operativo === 'EN_MANTENIMIENTO')
                            <span class="badge badge-warning">MANT.</span>
                        @else
                            <span class="badge badge-danger">FUERA</span>
                        @endif
                    </td>
                    <td>{{ $equipo->tipoEquipo->nombre ?? 'N/A' }}</td>
                    <td>{{ $equipo->area->nombre ?? 'N/A' }}</td>
                    <td>{{ $equipo->area->sede->nombre ?? 'N/A' }}</td>
                    <td>{{ $equipo->contrato?->numero_contrato ?? 'N/A' }}</td>
                    <td>
                        @if($equipo->area && $equipo->area->sede)
                            @if($equipo->area->sede->cliente_id)
                                {{ $equipo->area->sede->cliente->razon_social ?? 'N/A' }}
                            @else
                                {{ $equipo->area->sede->empresa->nombre ?? 'N/A' }}
                            @endif
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">No hay equipos registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pie de página -->
    <div class="footer">
        <p>
            Documento generado automáticamente por CEOGestion el {{ $fecha_reporte }}.
            <br>
            Para consultas o reportes adicionales, contacte al administrador del sistema.
        </p>
        <p style="margin-top: 5px;">Página <span class="page-number"></span> de <span class="page-count"></span></p>
    </div>
</body>
</html>
