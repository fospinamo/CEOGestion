<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listado de Equipos TI</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #1f2937;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #6b7280;
            font-size: 14px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 5px;
            font-size: 12px;
            color: #6b7280;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }
        
        thead {
            background-color: #f3f4f6;
        }
        
        th {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #1f2937;
            background-color: #e5e7eb;
        }
        
        td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            color: #374151;
        }
        
        tbody tr:nth-child(odd) {
            background-color: #f9fafb;
        }
        
        tbody tr:hover {
            background-color: #f3f4f6;
        }
        
        .status-operativo {
            background-color: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .status-mantenimiento {
            background-color: #fef3c7;
            color: #92400e;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .status-reparacion {
            background-color: #fed7aa;
            color: #92400e;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .status-baja {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .status-obsoleto {
            background-color: #f3f4f6;
            color: #374151;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            border-top: 1px solid #d1d5db;
            padding-top: 20px;
            font-size: 12px;
            color: #6b7280;
        }
        
        .summary {
            background-color: #f0f9ff;
            border: 1px solid #bfdbfe;
            padding: 10px;
            margin-top: 20px;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📋 Listado de Equipos TI</h1>
            <p>CEOGestion - Sistema de Gestión de Equipos</p>
        </div>
        
        <!-- Info -->
        <div class="info-row">
            <span><strong>Fecha de Generación:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</span>
            <span><strong>Total de Equipos:</strong> {{ $equipos->count() }}</span>
        </div>
        
        <!-- Tabla -->
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Código</th>
                    <th style="width: 8%;">Tipo</th>
                    <th style="width: 10%;">Marca/Modelo</th>
                    <th style="width: 10%;">Serial</th>
                    <th style="width: 10%;">Ubicación</th>
                    <th style="width: 12%;">Empresa/Cliente</th>
                    <th style="width: 8%;">Estado</th>
                    <th style="width: 10%;">Fecha Compra</th>
                    <th style="width: 12%;">Usuario Asignado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipos as $equipo)
                    <tr>
                        <td>{{ $equipo->codigo_interno }}</td>
                        <td>{{ $equipo->tipoEquipo?->nombre ?? 'N/A' }}</td>
                        <td>{{ $equipo->marca }} {{ $equipo->modelo }}</td>
                        <td>{{ $equipo->serial }}</td>
                        <td>
                            {{ $equipo->area?->nombre ?? 'N/A' }}<br>
                            <small>{{ $equipo->area?->sede?->nombre ?? 'N/A' }}</small>
                        </td>
                        <td>
                            @if($equipo->area?->sede?->cliente)
                                {{ $equipo->area->sede->cliente->razon_social }}
                            @elseif($equipo->area?->sede?->empresa)
                                {{ $equipo->area->sede->empresa->nombre }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = 'status-' . strtolower(str_replace(' ', '-', $equipo->estado_operativo));
                            @endphp
                            <span class="{{ $statusClass }}">{{ $equipo->estado_operativo }}</span>
                        </td>
                        <td>{{ $equipo->fecha_compra?->format('d/m/Y') ?? 'N/A' }}</td>
                        <td>{{ $equipo->usuario_asignado ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Resumen -->
        <div class="summary">
            <strong>📊 Resumen:</strong><br>
            Total de Equipos: {{ $equipos->count() }}<br>
            Operativos: {{ $equipos->where('estado_operativo', 'OPERATIVO')->count() }} |
            En Mantenimiento: {{ $equipos->where('estado_operativo', 'MANTENIMIENTO')->count() }} |
            En Reparación: {{ $equipos->where('estado_operativo', 'REPARACION')->count() }} |
            Dados de Baja: {{ $equipos->where('estado_operativo', 'BAJA')->count() }} |
            Obsoletos: {{ $equipos->where('estado_operativo', 'OBSOLETO')->count() }}
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Este documento fue generado automáticamente por CEOGestion</p>
            <p>© {{ now()->year }} - Todos los derechos reservados</p>
            <p style="margin-top: 10px; font-size: 10px;">⚠️ Este documento es confidencial y está destinado únicamente para uso interno</p>
        </div>
    </div>

    <script>
        // Auto-imprimir el PDF cuando se carga
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
