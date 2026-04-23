@extends('portal.cliente.layout')

@section('title', 'Detalle del Servicio #' . $servicio->id . ' - Portal del Cliente')

@section('content')
    <a href="{{ route('portal.servicios') }}" style="color: #3b82f6; text-decoration: none; margin-bottom: 20px; display: inline-block;">
        <i class="fas fa-arrow-left"></i> Volver a Servicios
    </a>

    <h1 style="margin-bottom: 30px; font-size: 28px; font-weight: 700;">
        <i class="fas fa-tools" style="margin-right: 10px; color: #3b82f6;"></i>
        Servicio #{{ $servicio->id }}
    </h1>

    <!-- Información General -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-title">Información General</div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <div>
                <strong style="color: #6b7280; font-size: 12px;">Estado:</strong>
                @php
                    $estadoConfig = [
                        'REPORTADO' => ['bg' => '#cffafe', 'text' => '#164e63'],
                        'EN_ESPERA_ASIGNACION' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                        'EN_PROCESO' => ['bg' => '#fed7aa', 'text' => '#92400b'],
                        'RESUELTO' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                        'CERRADO' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                    ];
                    $config = $estadoConfig[$servicio->estado] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                @endphp
                <div style="margin-top: 8px;">
                    <span class="badge" style="background: {{ $config['bg'] }}; color: {{ $config['text'] }}; padding: 8px 12px;">
                        {{ str_replace('_', ' ', $servicio->estado) }}
                    </span>
                </div>
            </div>

            <div>
                <strong style="color: #6b7280; font-size: 12px;">Tipo de Servicio:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">{{ $servicio->tipo_servicio }}</div>
            </div>

            <div>
                <strong style="color: #6b7280; font-size: 12px;">Prioridad:</strong>
                @php
                    $prioridadConfig = [
                        'BAJA' => ['bg' => '#dcfce7', 'text' => '#166534'],
                        'MEDIA' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                        'ALTA' => ['bg' => '#fed7aa', 'text' => '#92400b'],
                        'CRITICA' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                    ];
                    $config = $prioridadConfig[$servicio->prioridad] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                @endphp
                <div style="margin-top: 8px;">
                    <span class="badge" style="background: {{ $config['bg'] }}; color: {{ $config['text'] }}; padding: 8px 12px;">
                        {{ $servicio->prioridad }}
                    </span>
                </div>
            </div>

            <div>
                <strong style="color: #6b7280; font-size: 12px;">Fecha de Reporte:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">{{ $servicio->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Equipo Afectado -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-title">Equipo Afectado</div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <strong style="color: #6b7280; font-size: 12px;">Código Interno:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">{{ $servicio->equipo->codigo_interno }}</div>
            </div>

            <div>
                <strong style="color: #6b7280; font-size: 12px;">Tipo de Equipo:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">{{ $servicio->equipo->tipo->nombre ?? 'N/A' }}</div>
            </div>

            <div>
                <strong style="color: #6b7280; font-size: 12px;">Ubicación:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">{{ $servicio->equipo->ubicacion ?? 'N/A' }}</div>
            </div>

            <div>
                <strong style="color: #6b7280; font-size: 12px;">Serial/Modelo:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">{{ $servicio->equipo->numero_serie ?? 'N/A' }}</div>
            </div>

            <div>
                <strong style="color: #6b7280; font-size: 12px;">Área:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">{{ $servicio->equipo->area->nombre ?? 'N/A' }}</div>
            </div>

            <div>
                <strong style="color: #6b7280; font-size: 12px;">Sede:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">{{ $servicio->equipo->area->sede->nombre ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Descripción del Problema -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-title">Descripción del Problema</div>
        
        <div style="background: #f9fafb; padding: 15px; border-radius: 6px; border-left: 3px solid #3b82f6;">
            {{ $servicio->descripcion_problema }}
        </div>
    </div>

    <!-- Información de Contacto -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-title">Información de Contacto</div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <strong style="color: #6b7280; font-size: 12px;">Reportado por:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">{{ $servicio->reportado_por }}</div>
            </div>

            <div>
                <strong style="color: #6b7280; font-size: 12px;">Teléfono:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">
                    <a href="tel:{{ $servicio->telefono_contacto }}" style="color: #3b82f6; text-decoration: none;">
                        {{ $servicio->telefono_contacto }}
                    </a>
                </div>
            </div>

            <div>
                <strong style="color: #6b7280; font-size: 12px;">Email:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">
                    <a href="mailto:{{ $servicio->email_contacto }}" style="color: #3b82f6; text-decoration: none;">
                        {{ $servicio->email_contacto }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SLA y Técnico -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-title">SLA y Asignación</div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <strong style="color: #6b7280; font-size: 12px;">SLA Respuesta:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">{{ $servicio->sla_horas_respuesta }} horas</div>
                @if($servicio->fecha_limite_respuesta)
                    <small style="color: #6b7280; display: block; margin-top: 5px;">
                        Límite: {{ $servicio->fecha_limite_respuesta->format('d/m/Y H:i') }}
                    </small>
                @endif
            </div>

            <div>
                <strong style="color: #6b7280; font-size: 12px;">SLA Solución:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">{{ $servicio->sla_horas_solucion }} horas</div>
                @if($servicio->fecha_limite_solucion)
                    <small style="color: #6b7280; display: block; margin-top: 5px;">
                        Límite: {{ $servicio->fecha_limite_solucion->format('d/m/Y H:i') }}
                    </small>
                @endif
            </div>

            <div>
                <strong style="color: #6b7280; font-size: 12px;">Técnico Asignado:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 8px;">
                    {{ $servicio->tecnicoAsignado?->name ?? 'Sin asignar' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Seguimiento -->
    @if($servicio->seguimientos->isNotEmpty())
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-title">Historial de Seguimiento</div>
            
            <div style="position: relative; padding: 20px 0;">
                @foreach($servicio->seguimientos as $seguimiento)
                    <div style="display: flex; margin-bottom: 20px;">
                        <div style="width: 30px; height: 30px; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; margin-right: 15px;">
                            <i class="fas fa-check" style="font-size: 14px;"></i>
                        </div>
                        
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #111827;">
                                {{ $seguimiento->accion }}
                                @if($seguimiento->usuario)
                                    <span style="color: #6b7280; font-weight: 400; font-size: 12px;">por {{ $seguimiento->usuario->name }}</span>
                                @endif
                            </div>
                            
                            @if($seguimiento->observacion)
                                <div style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                                    {{ $seguimiento->observacion }}
                                </div>
                            @endif
                            
                            @if($seguimiento->estado_anterior && $seguimiento->estado_nuevo)
                                <div style="color: #6b7280; font-size: 12px; margin-top: 5px;">
                                    {{ $seguimiento->estado_anterior }} → {{ $seguimiento->estado_nuevo }}
                                </div>
                            @endif
                            
                            <div style="color: #9ca3af; font-size: 12px; margin-top: 8px;">
                                {{ $seguimiento->created_at->format('d/m/Y H:i:s') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Botones de Acción -->
    <div style="display: flex; gap: 10px; margin-top: 30px;">
        <a href="{{ route('portal.servicios') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        
        @if($servicio->estado === 'CERRADO' || $servicio->estado === 'RESUELTO')
            <a href="{{ route('portal.servicios.descargar', $servicio->id) }}" class="btn btn-primary" style="background: #10b981;">
                <i class="fas fa-download"></i> Descargar PDF
            </a>
        @endif
    </div>
@endsection
