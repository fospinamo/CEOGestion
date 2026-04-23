<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Atención #{{ $servicio->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        
        .container {
            width: 100%;
            padding: 20px;
        }
        
        .header {
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header-logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }
        
        .header-info {
            display: table;
            width: 100%;
        }
        
        .header-info div {
            display: table-cell;
            width: 50%;
            font-size: 11px;
            color: #666;
            padding: 5px 0;
        }
        
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background: #f0f9ff;
            border-left: 3px solid #3b82f6;
            padding: 10px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 13px;
        }
        
        .section-content {
            padding: 10px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        th {
            background: #f9fafb;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        .label {
            font-weight: bold;
            color: #666;
            width: 200px;
        }
        
        .value {
            color: #333;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-info {
            background: #cffafe;
            color: #164e63;
        }
        
        .footer {
            border-top: 1px solid #e5e7eb;
            margin-top: 30px;
            padding-top: 15px;
            text-align: center;
            color: #999;
            font-size: 10px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <div class="header-logo">📋 CEOGESTION</div>
            <div class="header-info">
                <div>
                    <strong>Empresa Proveedora</strong><br>
                    {{ $servicio->contrato->creadoPor->empresa->nombre ?? 'CEOGESTION SAS' }}<br>
                    Email: {{ $servicio->contrato->creadoPor->email ?? 'info@ceogestion.com' }}<br>
                    Teléfono: {{ $servicio->contrato->creadoPor->empresa?->telefono ?? 'N/A' }}
                </div>
                <div style="text-align: right;">
                    <strong>Documento de Atención</strong><br>
                    Número: #{{ $servicio->id }}<br>
                    Fecha: {{ now()->format('d/m/Y H:i') }}<br>
                    Período: {{ $servicio->created_at->format('d/m/Y') }} - {{ $servicio->updated_at->format('d/m/Y') }}
                </div>
            </div>
        </div>

        <!-- SECCIÓN 1: Cliente y Equipo -->
        <div class="section">
            <div class="section-title">1. INFORMACIÓN DEL CLIENTE Y EQUIPO</div>
            <div class="section-content">
                <table>
                    <tr>
                        <td class="label">Cliente:</td>
                        <td class="value">{{ $servicio->equipo->area->sede->cliente->razon_social }}</td>
                        <td class="label">Documento:</td>
                        <td class="value">{{ $servicio->equipo->area->sede->cliente->documento }}</td>
                    </tr>
                    <tr>
                        <td class="label">Sede:</td>
                        <td class="value">{{ $servicio->equipo->area->sede->nombre }}</td>
                        <td class="label">Ubicación:</td>
                        <td class="value">{{ $servicio->equipo->area->sede->municipio->nombre ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Área:</td>
                        <td class="value">{{ $servicio->equipo->area->nombre }}</td>
                        <td class="label">Responsable:</td>
                        <td class="value">{{ $servicio->equipo->area->responsable ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Código Equipo:</td>
                        <td class="value">{{ $servicio->equipo->codigo_interno }}</td>
                        <td class="label">Tipo:</td>
                        <td class="value">{{ $servicio->equipo->tipo->nombre }}</td>
                    </tr>
                    <tr>
                        <td class="label">Serial/Modelo:</td>
                        <td class="value">{{ $servicio->equipo->numero_serie ?? 'N/A' }}</td>
                        <td class="label">Ubicación:</td>
                        <td class="value">{{ $servicio->equipo->ubicacion ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- SECCIÓN 2: Información del Servicio -->
        <div class="section">
            <div class="section-title">2. INFORMACIÓN DEL SERVICIO</div>
            <div class="section-content">
                <table>
                    <tr>
                        <td class="label">Tipo de Servicio:</td>
                        <td><span class="badge badge-info">{{ $servicio->tipo_servicio }}</span></td>
                        <td class="label">Prioridad:</td>
                        @php
                            $prioridadClass = match($servicio->prioridad) {
                                'CRITICA' => 'badge-danger',
                                'ALTA' => 'badge-warning',
                                default => 'badge-info'
                            };
                        @endphp
                        <td><span class="badge {{ $prioridadClass }}">{{ $servicio->prioridad }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Estado:</td>
                        @php
                            $estadoClass = match($servicio->estado) {
                                'CERRADO', 'RESUELTO' => 'badge-success',
                                'EN_PROCESO' => 'badge-warning',
                                default => 'badge-info'
                            };
                        @endphp
                        <td><span class="badge {{ $estadoClass }}">{{ str_replace('_', ' ', $servicio->estado) }}</span></td>
                        <td class="label">Contrato:</td>
                        <td class="value">#{{ $servicio->contrato->numero_contrato }}</td>
                    </tr>
                    <tr>
                        <td class="label">Reportado por:</td>
                        <td class="value">{{ $servicio->reportado_por }}</td>
                        <td class="label">Teléfono:</td>
                        <td class="value">{{ $servicio->telefono_contacto }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email:</td>
                        <td class="value">{{ $servicio->email_contacto }}</td>
                        <td class="label">Técnico Asignado:</td>
                        <td class="value">{{ $servicio->tecnicoAsignado?->name ?? 'No asignado' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- SECCIÓN 3: Descripción del Problema -->
        <div class="section">
            <div class="section-title">3. DESCRIPCIÓN DEL PROBLEMA REPORTADO</div>
            <div class="section-content">
                <table>
                    <tr>
                        <td style="background: #f9fafb; padding: 15px;">
                            {{ $servicio->descripcion_problema }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- SECCIÓN 4: SLA y Cronograma -->
        <div class="section">
            <div class="section-title">4. ACUERDO DE NIVEL DE SERVICIO (SLA)</div>
            <div class="section-content">
                <table>
                    <tr>
                        <td class="label">SLA Respuesta:</td>
                        <td class="value">{{ $servicio->sla_horas_respuesta }} horas</td>
                        <td class="label">Fecha Límite Respuesta:</td>
                        <td class="value">{{ $servicio->fecha_limite_respuesta?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">SLA Solución:</td>
                        <td class="value">{{ $servicio->sla_horas_solucion }} horas</td>
                        <td class="label">Fecha Límite Solución:</td>
                        <td class="value">{{ $servicio->fecha_limite_solucion?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- SECCIÓN 5: Cronograma de Atención -->
        @if($servicio->fecha_inicio_atencion || $servicio->fecha_resolucion || $servicio->fecha_cierre_real)
            <div class="section">
                <div class="section-title">5. CRONOGRAMA DE ATENCIÓN</div>
                <div class="section-content">
                    <table>
                        <tr>
                            <td class="label">Fecha Reporte:</td>
                            <td class="value">{{ $servicio->created_at->format('d/m/Y H:i') }}</td>
                            <td class="label">Inicio Atención:</td>
                            <td class="value">{{ $servicio->fecha_inicio_atencion?->format('d/m/Y H:i') ?? 'Pendiente' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Fecha Resolución:</td>
                            <td class="value">{{ $servicio->fecha_resolucion?->format('d/m/Y H:i') ?? 'En proceso' }}</td>
                            <td class="label">Cierre:</td>
                            <td class="value">{{ $servicio->fecha_cierre_real?->format('d/m/Y H:i') ?? 'Pendiente' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endif

        <!-- SECCIÓN 6: Historial de Seguimiento -->
        @if($servicio->seguimientos->isNotEmpty())
            <div class="section">
                <div class="section-title">6. HISTORIAL DE SEGUIMIENTO</div>
                <div class="section-content">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 20%;">Fecha/Hora</th>
                                <th style="width: 15%;">Acción</th>
                                <th style="width: 15%;">Usuario</th>
                                <th style="width: 50%;">Observación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($servicio->seguimientos as $seguimiento)
                                <tr>
                                    <td>{{ $seguimiento->created_at->format('d/m/Y H:i') }}</td>
                                    <td><strong>{{ $seguimiento->accion }}</strong></td>
                                    <td>{{ $seguimiento->usuario->name ?? 'N/A' }}</td>
                                    <td>{{ $seguimiento->observacion ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Pie de Página -->
        <div class="footer">
            <p>Este documento es una copia de la atención registrada en el sistema CEOGESTION.</p>
            <p>Generado el {{ now()->format('d/m/Y H:i:s') }} - Servicio #{{ $servicio->id }}</p>
            <p>Para más información: www.ceogestion.com</p>
        </div>
    </div>
</body>
</html>
