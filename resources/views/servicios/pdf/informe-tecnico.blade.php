<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Informe Técnico - Servicio {{ $servicio->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .page {
            width: 100%;
            padding: 20px;
            page-break-after: always;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header-left {
            flex: 1;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }
        
        .header-right {
            text-align: right;
            flex: 1;
        }
        
        .service-number {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            background-color: #1e40af;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        
        .section-content {
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }
        
        .field {
            display: flex;
            margin-bottom: 8px;
        }
        
        .field-label {
            font-weight: bold;
            width: 30%;
            color: #1e40af;
        }
        
        .field-value {
            width: 70%;
            word-wrap: break-word;
        }
        
        .field-full {
            width: 100%;
            margin-bottom: 8px;
        }
        
        .field-full-label {
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 3px;
        }
        
        .field-full-value {
            padding-left: 10px;
            word-wrap: break-word;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th {
            background-color: #e0e7ff;
            color: #1e40af;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .image-item {
            flex: 0 1 calc(33% - 7px);
            text-align: center;
        }
        
        .image-item img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        
        .signature-box {
            display: inline-block;
            border: 1px solid #ddd;
            padding: 5px;
            margin-top: 10px;
        }
        
        .signature-box img {
            max-width: 200px;
            height: auto;
        }
        
        .signature-label {
            text-align: center;
            font-size: 10px;
            margin-top: 5px;
        }
        
        .footer {
            border-top: 2px solid #1e40af;
            padding-top: 10px;
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        
        .two-column {
            display: flex;
            gap: 20px;
        }
        
        .two-column .column {
            flex: 1;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- PÁGINA 1: INFORMACIÓN PRINCIPAL -->
    <div class="page">
        <div class="header">
            <div class="header-left">
                <div class="company-name">CEOGestión</div>
                <div style="font-size: 10px; color: #666;">Gestión de Servicios Técnicos</div>
            </div>
            <div class="header-right">
                <div class="service-number">Servicio #{{ $servicio->id }}</div>
                <div style="font-size: 10px;">Informe Técnico</div>
            </div>
        </div>

        <!-- INFORMACIÓN DEL CLIENTE Y EQUIPO -->
        <div class="section">
            <div class="section-title">📋 INFORMACIÓN DEL CLIENTE Y EQUIPO</div>
            <div class="section-content">
                <div class="two-column">
                    <div class="column">
                        <div class="field">
                            <div class="field-label">Cliente:</div>
                            <div class="field-value">{{ $servicio->equipo->area->sede->cliente->nombre ?? 'N/A' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Sede:</div>
                            <div class="field-value">{{ $servicio->equipo->area->sede->nombre ?? 'N/A' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Área:</div>
                            <div class="field-value">{{ $servicio->equipo->area->nombre ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <div class="field-label">Equipo Principal:</div>
                            <div class="field-value">{{ $servicio->equipo->descripcion ?? 'N/A' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Modelo:</div>
                            <div class="field-value">{{ $servicio->equipo->modelo ?? 'N/A' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Marca:</div>
                            <div class="field-value">{{ $servicio->equipo->marca ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="field-label">Serie:</div>
                    <div class="field-value">{{ $servicio->equipo->serie ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN DEL SERVICIO -->
        <div class="section">
            <div class="section-title">🔧 INFORMACIÓN DEL SERVICIO</div>
            <div class="section-content">
                <div class="two-column">
                    <div class="column">
                        <div class="field">
                            <div class="field-label">Tipo de Servicio:</div>
                            <div class="field-value">{{ $servicio->tipo_servicio ?? 'N/A' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Estado:</div>
                            <div class="field-value">{{ $servicio->estadoServicio->nombre ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <div class="field-label">Fecha Creación:</div>
                            <div class="field-value">{{ $servicio->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Técnico Responsable:</div>
                            <div class="field-value">{{ $servicio->tecnicoResponsable->nombre ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DESCRIPCIÓN DE LA ATENCIÓN -->
        <div class="section">
            <div class="section-title">📝 DESCRIPCIÓN DE LA ATENCIÓN TÉCNICA</div>
            <div class="section-content">
                <div class="field-full">
                    <div class="field-full-value">{{ $servicio->descripcion_atencion ?? 'Sin descripción' }}</div>
                </div>
            </div>
        </div>

        <!-- PERSONA QUE RECIBE EL SERVICIO -->
        <div class="section">
            <div class="section-title">👤 PERSONA QUE RECIBE EL SERVICIO</div>
            <div class="section-content">
                <div class="two-column">
                    <div class="column">
                        <div class="field">
                            <div class="field-label">Nombre:</div>
                            <div class="field-value">{{ $servicio->persona_receptora_nombre ?? 'N/A' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Apellido:</div>
                            <div class="field-value">{{ $servicio->persona_receptora_apellido ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <div class="field-label">Documento:</div>
                            <div class="field-value">{{ $servicio->persona_receptora_documento ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-break"></div>
    </div>

    <!-- PÁGINA 2: IMÁGENES Y FIRMA -->
    <div class="page">
        <div class="header">
            <div class="header-left">
                <div class="company-name">CEOGestión</div>
            </div>
            <div class="header-right">
                <div class="service-number">Servicio #{{ $servicio->id }} - Imágenes y Firma</div>
            </div>
        </div>

        <!-- IMÁGENES -->
        @if(!empty($imagenesBase64) && count($imagenesBase64) > 0)
        <div class="section">
            <div class="section-title">📸 IMÁGENES DEL SERVICIO</div>
            <div class="section-content">
                <div class="image-gallery">
                    @foreach($imagenesBase64 as $imagen)
                        <div class="image-item">
                            <img src="{{ $imagen }}" alt="Imagen del servicio">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- FIRMA DEL RECEPTOR -->
        <div class="section">
            <div class="section-title">✍️ FIRMA DEL RECEPTOR</div>
            <div class="section-content">
                @if($firmaBase64)
                    <div class="signature-box">
                        <img src="{{ $firmaBase64 }}" alt="Firma">
                    </div>
                    <div class="signature-label">
                        Firma de {{ $servicio->persona_receptora_nombre }} {{ $servicio->persona_receptora_apellido }}
                    </div>
                @else
                    <p>Sin firma registrada</p>
                @endif
            </div>
        </div>

        <!-- DATOS DEL TÉCNICO -->
        <div class="section">
            <div class="section-title">👨‍🔧 DATOS DEL TÉCNICO RESPONSABLE</div>
            <div class="section-content">
                <div class="two-column">
                    <div class="column">
                        <div class="field">
                            <div class="field-label">Nombre:</div>
                            <div class="field-value">{{ $servicio->tecnicoResponsable->nombre ?? 'N/A' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Email:</div>
                            <div class="field-value">{{ $servicio->tecnicoResponsable->email ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <div class="field-label">Teléfono:</div>
                            <div class="field-value">{{ $servicio->tecnicoResponsable->telefono ?? 'N/A' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Cédula:</div>
                            <div class="field-value">{{ $servicio->tecnicoResponsable->documento ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER CON FECHA Y HORA -->
        <div class="footer">
            <hr style="border: none; border-top: 1px solid #ddd; margin-bottom: 10px;">
            <p>Informe generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i:s') }}</p>
            <p>Sistema CEOGestión © 2026</p>
        </div>
    </div>
</body>
</html>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Fecha de Solicitud</div>
                <div class="info-value">{{ $servicio->fecha_solicitud->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Fecha de Atención</div>
                <div class="info-value">{{ $servicio->fecha_atencion ? $servicio->fecha_atencion->format('d/m/Y') : 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Hora de Inicio</div>
                <div class="info-value">{{ $servicio->hora_inicio_atencion ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Hora de Fin</div>
                <div class="info-value">{{ $servicio->hora_fin_atencion ?? 'N/A' }}</div>
            </div>
        </div>

        @if($servicio->hora_inicio_atencion && $servicio->hora_fin_atencion)
            <div class="info-item" style="grid-column: 1 / -1;">
                <div class="info-label">Duración del Servicio</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $servicio->hora_inicio_atencion)->diff(\Carbon\Carbon::createFromFormat('H:i:s', $servicio->hora_fin_atencion))->format('%H:%I') }}
                </div>
            </div>
        @endif
    </div>

    <!-- TIPO DE SERVICIO -->
    <div class="section">
        <div class="section-title">🔧 TIPO DE SERVICIO</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Tipo de Servicio</div>
                <div class="info-value">
                    @switch($servicio->tipo_servicio_informe)
                        @case('INSTALACION')
                            <span class="badge badge-success">Instalación</span>
                        @break
                        @case('MANTENIMIENTO_PREVENTIVO')
                            <span class="badge badge-info">Mantenimiento Preventivo</span>
                        @break
                        @case('MANTENIMIENTO_CORRECTIVO')
                            <span class="badge badge-warning">Mantenimiento Correctivo</span>
                        @break
                        @case('SOPORTE')
                            <span class="badge badge-danger">Soporte</span>
                        @break
                        @default
                            <span class="badge">{{ $servicio->tipo_servicio_informe ?? 'N/A' }}</span>
                    @endswitch
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">¿Es Facturable?</div>
                <div class="info-value">
                    {{ $servicio->puede_facturarse ? '✅ Sí' : '❌ No' }}
                </div>
            </div>
        </div>
    </div>

    <!-- EQUIPOS ATENDIDOS -->
    <div class="section">
        <div class="section-title">🖥️ EQUIPOS ATENDIDOS</div>
        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Serie</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>{{ $servicio->equipo->descripcion ?? 'N/A' }} (Principal)</strong></td>
                    <td>{{ $servicio->equipo->modelo ?? 'N/A' }}</td>
                    <td>{{ $servicio->equipo->marca ?? 'N/A' }}</td>
                    <td>{{ $servicio->equipo->serie ?? 'N/A' }}</td>
                </tr>
                @if(!empty($servicio->equipos_adicionales_atendidos) && is_array($servicio->equipos_adicionales_atendidos))
                    @foreach($servicio->equipos_adicionales_atendidos as $equipoId)
                        @php
                            $equipoAdicional = \App\Models\Equipo::find($equipoId);
                        @endphp
                        @if($equipoAdicional)
                            <tr>
                                <td>{{ $equipoAdicional->descripcion ?? 'N/A' }}</td>
                                <td>{{ $equipoAdicional->modelo ?? 'N/A' }}</td>
                                <td>{{ $equipoAdicional->marca ?? 'N/A' }}</td>
                                <td>{{ $equipoAdicional->serie ?? 'N/A' }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- DESCRIPCIÓN DE LA SOLICITUD -->
    @if($servicio->descripcion_solicitud)
        <div class="section">
            <div class="section-title">📝 DESCRIPCIÓN DE LA SOLICITUD</div>
            <div class="text-block">
                {{ $servicio->descripcion_solicitud }}
            </div>
        </div>
    @endif

    <!-- DIAGNÓSTICO -->
    @if($servicio->diagnostico_validacion)
        <div class="section">
            <div class="section-title">🔍 DIAGNÓSTICO / VALIDACIÓN DEL SERVICIO</div>
            <div class="text-block">
                {{ $servicio->diagnostico_validacion }}
            </div>
        </div>
    @endif

    <!-- PENDIENTES -->
    @if($servicio->pendientes)
        <div class="section">
            <div class="section-title">⏳ ACTIVIDADES PENDIENTES</div>
            <div class="text-block">
                {{ $servicio->pendientes }}
            </div>
        </div>
    @endif

    <!-- OBSERVACIONES -->
    @if($servicio->observaciones_informe)
        <div class="section">
            <div class="section-title">📌 OBSERVACIONES</div>
            <div class="text-block">
                {{ $servicio->observaciones_informe }}
            </div>
        </div>
    @endif

    <!-- REPUESTOS UTILIZADOS -->
    @if(!empty($servicio->repuestos_utilizados) && is_array($servicio->repuestos_utilizados))
        <div class="section">
            <div class="section-title">🔩 REPUESTOS Y ACCESORIOS INSTALADOS</div>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Serie</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicio->repuestos_utilizados as $repuesto)
                        <tr>
                            <td>{{ $repuesto['codigo'] ?? '-' }}</td>
                            <td>{{ $repuesto['descripcion'] ?? '-' }}</td>
                            <td>{{ $repuesto['marca'] ?? '-' }}</td>
                            <td>{{ $repuesto['modelo'] ?? '-' }}</td>
                            <td>{{ $repuesto['serie'] ?? '-' }}</td>
                            <td>{{ $repuesto['cantidad'] ?? 1 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- RECEPTOR -->
    <div class="section">
        <div class="section-title">✋ RECEPTOR DEL SERVICIO</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nombre</div>
                <div class="info-value">{{ $servicio->persona_receptora_nombre ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Apellido</div>
                <div class="info-value">{{ $servicio->persona_receptora_apellido ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Documento</div>
                <div class="info-value">{{ $servicio->persona_receptora_documento ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Estado del Servicio</div>
                <div class="info-value">
                    @if($servicio->estadoServicio)
                        <span class="badge badge-info">{{ $servicio->estadoServicio->nombre }}</span>
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- FIRMA -->
    @if($servicio->firma_persona_receptora)
        <div class="section">
            <div class="section-title">📋 FIRMA DEL RECEPTOR</div>
            <div style="text-align: center; padding: 20px;">
                <img src="{{ base_path('storage/app/private/' . $servicio->firma_persona_receptora) }}" 
                     alt="Firma" 
                     style="max-width: 250px; max-height: 100px; border: 1px solid #ddd; padding: 5px;">
            </div>
        </div>
    @endif

    <!-- INFORMACIÓN DEL TÉCNICO -->
    <div class="section">
        <div class="section-title">👨‍🔧 INFORMACIÓN DEL TÉCNICO</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Técnico Responsable</div>
                <div class="info-value">{{ $servicio->tecnicoResponsable?->name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Correo Electrónico</div>
                <div class="info-value">{{ $servicio->tecnicoResponsable?->email ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Fecha de Firma</div>
                <div class="info-value">{{ $servicio->fecha_firma?->format('d/m/Y H:i') ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Este documento fue generado automáticamente por el sistema CEOGestion.</p>
        <p>Generado: {{ now()->format('d/m/Y H:i:s') }} | Página 1</p>
    </div>
</body>
</html>
