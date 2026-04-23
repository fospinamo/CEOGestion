@extends('portal.cliente.layout')

@section('title', 'Contratos - Portal del Cliente')

@section('content')
    <h1 style="margin-bottom: 30px; font-size: 28px; font-weight: 700;">
        <i class="fas fa-file-contract" style="margin-right: 10px; color: #3b82f6;"></i>
        Contratos Activos
    </h1>

    @if($contratos->isEmpty())
        <div class="card" style="text-align: center; padding: 40px;">
            <i class="fas fa-inbox" style="font-size: 48px; color: #d1d5db; margin-bottom: 20px;"></i>
            <p style="color: #6b7280; font-size: 16px;">No tienes contratos activos en este momento.</p>
        </div>
    @else
        @foreach($contratos as $contrato)
            <div class="card" style="margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: auto 1fr auto; gap: 20px; align-items: start;">
                    <!-- Información Principal -->
                    <div style="grid-column: 1 / 2;">
                        <div style="font-size: 28px; font-weight: 700; color: #3b82f6;">
                            #{{ $contrato->numero_contrato }}
                        </div>
                    </div>

                    <!-- Detalles -->
                    <div>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 50%; padding: 8px 0; border: none;">
                                    <strong style="color: #6b7280; font-size: 12px;">Tipo de Contrato:</strong>
                                    <div style="color: #111827; margin-top: 3px;">{{ $contrato->tipo_contrato }}</div>
                                </td>
                                <td style="width: 50%; padding: 8px 0; border: none; padding-left: 20px;">
                                    <strong style="color: #6b7280; font-size: 12px;">Período:</strong>
                                    <div style="color: #111827; margin-top: 3px;">
                                        {{ $contrato->fecha_inicio->format('d/m/Y') }} - {{ $contrato->fecha_vencimiento->format('d/m/Y') }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%; padding: 8px 0; border: none;">
                                    <strong style="color: #6b7280; font-size: 12px;">Estado:</strong>
                                    <div style="margin-top: 3px;">
                                        @php
                                            $estadoColor = $contrato->estado === 'ACTIVO' ? '#d1fae5' : '#fee2e2';
                                            $estadoTextColor = $contrato->estado === 'ACTIVO' ? '#065f46' : '#991b1b';
                                        @endphp
                                        <span class="badge" style="background: {{ $estadoColor }}; color: {{ $estadoTextColor }};">
                                            {{ $contrato->estado }}
                                        </span>
                                    </div>
                                </td>
                                <td style="width: 50%; padding: 8px 0; border: none; padding-left: 20px;">
                                    <strong style="color: #6b7280; font-size: 12px;">Valor Contrato:</strong>
                                    <div style="color: #111827; margin-top: 3px; font-weight: 600;">
                                        ${{ number_format($contrato->valor_total, 0, ',', '.') }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- SLA Info -->
                    <div style="grid-column: 3 / 4; background: #f0f9ff; padding: 15px; border-radius: 6px; border-left: 3px solid #3b82f6;">
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">
                            <strong>Cobertura de Servicios:</strong>
                        </div>
                        <div style="font-size: 13px; color: #111827; line-height: 1.6;">
                            @if($contrato->servicios)
                                @php
                                    $serviciosIncluidos = $contrato->servicios->where('incluido', true)->pluck('tipo_servicio');
                                @endphp
                                @foreach($serviciosIncluidos as $servicio)
                                    <div><i class="fas fa-check" style="color: #10b981;"></i> {{ $servicio }}</div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- SLA Times -->
                @if($contrato->servicios)
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 10px;"><strong>SLA por Tipo de Servicio:</strong></div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px;">
                            @foreach($contrato->servicios->where('incluido', true) as $servicio)
                                <div style="background: #f9fafb; padding: 10px; border-radius: 6px; border: 1px solid #e5e7eb; font-size: 12px;">
                                    <div style="font-weight: 600; color: #111827; margin-bottom: 5px;">{{ $servicio->tipo_servicio }}</div>
                                    <div style="color: #6b7280;">
                                        Respuesta: <strong>{{ $servicio->sla_horas_respuesta }}h</strong> | Solución: <strong>{{ $servicio->sla_horas_solucion }}h</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
@endsection
