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
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #333;
            background-color: #fff;
        }
        
        .page {
            width: 100%;
            padding: 20px;
            page-break-after: always;
        }
        
        /* ENCABEZADO */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 3px solid #0066cc;
            padding-bottom: 15px;
        }
        
        .header-left {
            flex: 1;
        }
        
        .company-logo {
            font-size: 20px;
            font-weight: 900;
            color: #0066cc;
            letter-spacing: 1px;
        }
        
        .company-tagline {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }
        
        .header-right {
            text-align: right;
            flex: 1;
            padding-right: 20px;
        }
        
        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #0066cc;
            margin-bottom: 5px;
        }
        
        .report-number {
            font-size: 12px;
            color: #333;
            font-weight: bold;
        }
        
        /* TABLA DE INFO GENERAL */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            background-color: #f5f5f5;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-row.header {
            background-color: #0066cc;
            color: white;
        }
        
        .info-cell {
            display: table-cell;
            padding: 6px 8px;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            font-size: 9px;
        }
        
        .info-cell.label {
            font-weight: bold;
            width: 25%;
            background-color: #e8f0ff;
            color: #0066cc;
        }
        
        .info-cell.value {
            width: 25%;
        }
        
        .info-cell:last-child {
            border-right: none;
        }
        
        /* SECCIONES */
        .section {
            margin-bottom: 15px;
            border: 1px solid #0066cc;
        }
        
        .section-header {
            background-color: #0066cc;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 11px;
            display: flex;
            align-items: center;
        }
        
        .section-header::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: white;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .section-content {
            padding: 10px;
            background-color: #fafafa;
        }
        
        /* TABLA DE EQUIPOS */
        .equipment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        
        .equipment-table th {
            background-color: #0066cc;
            color: white;
            padding: 6px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        
        .equipment-table td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }
        
        .equipment-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .equipment-table tr:hover {
            background-color: #f0f5ff;
        }
        
        /* TEXTO LARGO */
        .long-text {
            background-color: white;
            border: 1px solid #e0e0e0;
            padding: 8px;
            border-radius: 3px;
            font-size: 9px;
            line-height: 1.6;
            text-align: justify;
        }
        
        /* FIRMA */
        .signature-box {
            text-align: center;
            border: 1px solid #ddd;
            padding: 20px 10px;
            background-color: #fff;
            margin: 10px 0;
        }
        
        .signature-image {
            max-height: 80px;
            margin: 10px 0;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 5px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 9px;
        }
        
        /* IMAGEN GALERÍA */
        .image-gallery {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .image-item {
            display: table-cell;
            width: 33.33%;
            padding: 5px;
            text-align: center;
            border: 1px solid #ddd;
        }
        
        .image-item img {
            max-width: 100%;
            max-height: 100px;
        }
        
        /* PIE DE PÁGINA */
        .footer {
            margin-top: 20px;
            border-top: 2px solid #0066cc;
            padding-top: 10px;
            font-size: 8px;
            text-align: center;
            color: #666;
        }
        
        .footer-info {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        
        .footer-cell {
            display: table-cell;
            width: 33.33%;
            padding: 5px;
            text-align: center;
            border-right: 1px solid #ddd;
            font-size: 8px;
        }
        
        .footer-cell:last-child {
            border-right: none;
        }
        
        /* UTILIDADES */
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .bold {
            font-weight: bold;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
            margin: 2px;
        }
        
        .badge-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* DOS COLUMNAS */
        .two-column {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        
        .column:last-child {
            padding-right: 0;
        }
        
        .field-group {
            margin-bottom: 8px;
        }
        
        .field-label {
            font-weight: bold;
            color: #0066cc;
            font-size: 9px;
        }
        
        .field-value {
            color: #333;
            font-size: 9px;
        }
        
        /* PÁGINA 2 */
        .page2 {
            border-top: 2px solid #0066cc;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <!-- PÁGINA 1 -->
    <div class="page">
        <!-- ENCABEZADO -->
        <div class="header">
            <div class="header-left">
                <div class="company-logo">CEOGestión</div>
                <div class="company-tagline">Gestión Profesional de Servicios TI</div>
            </div>
            <div class="header-right">
                <div class="report-title">INFORME SERVICIO TÉCNICO</div>
                <div class="report-number">No. {{ $servicio->id }}</div>
            </div>
        </div>

        <!-- INFO GENERAL EN 4 COLUMNAS -->
        <div class="info-grid">
            <div class="info-row header">
                <div class="info-cell" style="width: 25%; border-right: 1px solid #fff;">Fecha Solicitud</div>
                <div class="info-cell" style="width: 25%; border-right: 1px solid #fff;">Fecha Atención</div>
                <div class="info-cell" style="width: 25%; border-right: 1px solid #fff;">Contrato</div>
                <div class="info-cell" style="width: 25%;">Estado</div>
            </div>
            <div class="info-row">
                <div class="info-cell" style="width: 25%;">{{ $servicio->fecha_solicitud->format('d/m/Y') }}</div>
                <div class="info-cell" style="width: 25%;">{{ $servicio->fecha_atencion?->format('d/m/Y') ?? 'N/A' }}</div>
                <div class="info-cell" style="width: 25%;">{{ $servicio->contrato->codigo ?? 'N/A' }}</div>
                <div class="info-cell" style="width: 25%;">
                    @if($servicio->estadoServicio)
                        <span class="badge badge-info">{{ $servicio->estadoServicio->nombre }}</span>
                    @else
                        <span>{{ $servicio->estado }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN DEL CLIENTE -->
        <div class="section">
            <div class="section-header">👥 INFORMACIÓN DEL CLIENTE</div>
            <div class="section-content">
                <div class="two-column">
                    <div class="column">
                        <div class="field-group">
                            <div class="field-label">Razón Social:</div>
                            <div class="field-value">{{ $servicio->equipo->area->sede->cliente->razon_social }}</div>
                        </div>
                        <div class="field-group">
                            <div class="field-label">NIT:</div>
                            <div class="field-value">{{ $servicio->equipo->area->sede->cliente->nit }}</div>
                        </div>
                        <div class="field-group">
                            <div class="field-label">Dirección:</div>
                            <div class="field-value">{{ $servicio->equipo->area->sede->direccion }}</div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field-group">
                            <div class="field-label">Ciudad:</div>
                            <div class="field-value">{{ $servicio->equipo->area->sede->ciudad }}</div>
                        </div>
                        <div class="field-group">
                            <div class="field-label">Sede:</div>
                            <div class="field-value">{{ $servicio->equipo->area->sede->nombre }}</div>
                        </div>
                        <div class="field-group">
                            <div class="field-label">Área:</div>
                            <div class="field-value">{{ $servicio->equipo->area->nombre }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN DEL EQUIPO -->
        <div class="section">
            <div class="section-header">🖥️ EQUIPO ATENDIDO</div>
            <div class="section-content">
                <div class="two-column">
                    <div class="column">
                        <div class="field-group">
                            <div class="field-label">Código:</div>
                            <div class="field-value">{{ $servicio->equipo->codigo_interno }}</div>
                        </div>
                        <div class="field-group">
                            <div class="field-label">Descripción:</div>
                            <div class="field-value">{{ $servicio->equipo->descripcion }}</div>
                        </div>
                        <div class="field-group">
                            <div class="field-label">Modelo:</div>
                            <div class="field-value">{{ $servicio->equipo->modelo ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field-group">
                            <div class="field-label">Marca:</div>
                            <div class="field-value">{{ $servicio->equipo->marca ?? 'N/A' }}</div>
                        </div>
                        <div class="field-group">
                            <div class="field-label">Serie:</div>
                            <div class="field-value">{{ $servicio->equipo->serie ?? 'N/A' }}</div>
                        </div>
                        <div class="field-group">
                            <div class="field-label">Tipo de Servicio:</div>
                            <div class="field-value">
                                @switch($servicio->tipo_servicio_informe)
                                    @case('INSTALACION')
                                        <span class="badge badge-success">Instalación</span>
                                    @break
                                    @case('MANTENIMIENTO_PREVENTIVO')
                                        <span class="badge badge-info">Mtto. Preventivo</span>
                                    @break
                                    @case('MANTENIMIENTO_CORRECTIVO')
                                        <span class="badge badge-warning">Mtto. Correctivo</span>
                                    @break
                                    @case('SOPORTE')
                                        <span class="badge badge-danger">Soporte</span>
                                    @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DESCRIPCIÓN DEL SERVICIO SOLICITADO -->
        <div class="section">
            <div class="section-header">📋 DESCRIPCIÓN DEL SERVICIO SOLICITADO</div>
            <div class="section-content">
                <div class="long-text">
                    {{ $servicio->descripcion_problema ?? 'No especificado' }}
                </div>
            </div>
        </div>

        <!-- DIAGNÓSTICO -->
        <div class="section">
            <div class="section-header">🔍 DIAGNÓSTICO / VALIDACIÓN</div>
            <div class="section-content">
                <div class="long-text">
                    {{ $servicio->diagnostico ?? 'Pendiente' }}
                </div>
            </div>
        </div>
    </div>

    <!-- PÁGINA 2 -->
    <div class="page page2">
        <!-- DESCRIPCIÓN DEL SERVICIO PRESTADO -->
        <div class="section">
            <div class="section-header">✅ DESCRIPCIÓN DEL SERVICIO PRESTADO</div>
            <div class="section-content">
                <div class="long-text">
                    {{ $servicio->descripcion_atencion ?? 'Pendiente' }}
                </div>
            </div>
        </div>

        <!-- DURACIÓN Y HORAS -->
        @if($servicio->hora_inicio_atencion && $servicio->hora_fin_atencion)
        <div class="section">
            <div class="section-header">⏱️ TIEMPO INVERTIDO</div>
            <div class="section-content">
                <div class="two-column">
                    <div class="column">
                        <div class="field-group">
                            <div class="field-label">Hora Inicio:</div>
                            <div class="field-value">{{ \Carbon\Carbon::parse($servicio->hora_inicio_atencion)->format('H:i') }}</div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field-group">
                            <div class="field-label">Hora Fin:</div>
                            <div class="field-value">{{ \Carbon\Carbon::parse($servicio->hora_fin_atencion)->format('H:i') }}</div>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #ccc;">
                    <div class="field-group">
                        <div class="field-label">Duración Total:</div>
                        <div class="field-value bold">
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $servicio->hora_inicio_atencion)->diff(\Carbon\Carbon::createFromFormat('H:i:s', $servicio->hora_fin_atencion))->format('%H:%I horas') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- IMÁGENES -->
        @if(!empty($imagenesBase64) && count($imagenesBase64) > 0)
        <div class="section">
            <div class="section-header">📸 IMÁGENES DEL SERVICIO</div>
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
            <div class="section-header">✍️ FIRMA DEL RECEPTOR</div>
            <div class="section-content">
                <div class="two-column">
                    <div class="column">
                        <div class="signature-box">
                            @if($firmaBase64)
                                <img src="{{ $firmaBase64 }}" alt="Firma" class="signature-image">
                            @else
                                <p style="color: #999; font-style: italic;">Sin firma registrada</p>
                            @endif
                            <div class="signature-line">
                                {{ $servicio->persona_receptora_nombre }} {{ $servicio->persona_receptora_apellido }}
                            </div>
                            <div style="font-size: 8px; color: #999; margin-top: 3px;">
                                Cédula: {{ $servicio->persona_receptora_documento }}
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="signature-box">
                            @if($servicio->tecnicoResponsable)
                                <div style="height: 80px;"></div>
                            @endif
                            <div class="signature-line">
                                {{ $servicio->tecnicoResponsable->name ?? 'Técnico' }}
                            </div>
                            <div style="font-size: 8px; color: #999; margin-top: 3px;">
                                @if($servicio->tecnicoResponsable)
                                    {{ $servicio->tecnicoResponsable->email }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN DEL TÉCNICO -->
        <div class="section">
            <div class="section-header">👨‍🔧 DATOS DEL TÉCNICO RESPONSABLE</div>
            <div class="section-content">
                @if($servicio->tecnicoResponsable)
                <div class="two-column">
                    <div class="column">
                        <div class="field-group">
                            <div class="field-label">Nombre:</div>
                            <div class="field-value">{{ $servicio->tecnicoResponsable->name }}</div>
                        </div>
                        <div class="field-group">
                            <div class="field-label">Email:</div>
                            <div class="field-value">{{ $servicio->tecnicoResponsable->email }}</div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field-group">
                            <div class="field-label">Teléfono:</div>
                            <div class="field-value">{{ $servicio->tecnicoResponsable->telefono ?? 'N/A' }}</div>
                        </div>
                        <div class="field-group">
                            <div class="field-label">Cédula:</div>
                            <div class="field-value">{{ $servicio->tecnicoResponsable->cedula ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                @else
                    <p style="color: #999; font-style: italic;">Sin técnico asignado</p>
                @endif
            </div>
        </div>

        <!-- PIE DE PÁGINA -->
        <div class="footer">
            <div style="margin-bottom: 5px;">
                <strong>Informe generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i:s') }}</strong>
            </div>
            <div class="footer-info">
                <div class="footer-cell">
                    <strong>CEOGestión</strong><br>
                    Gestión Profesional de Servicios TI
                </div>
                <div class="footer-cell">
                    <strong>Servicio #{{ $servicio->id }}</strong><br>
                    {{ $servicio->estadoServicio->nombre ?? $servicio->estado }}
                </div>
                <div class="footer-cell">
                    <strong>Cliente:</strong><br>
                    {{ $servicio->equipo->area->sede->cliente->razon_social }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
