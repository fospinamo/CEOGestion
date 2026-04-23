@extends('portal.cliente.layout')

@section('title', 'Dashboard - Portal del Cliente')

@section('content')
    <h1 style="margin-bottom: 30px; font-size: 28px; font-weight: 700;">
        <i class="fas fa-chart-line" style="margin-right: 10px; color: #3b82f6;"></i>
        Dashboard
    </h1>

    <!-- Información del Cliente -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-title">Información del Cliente</div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <strong style="color: #6b7280; font-size: 12px;">Razón Social:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 5px;">{{ $cliente->razon_social }}</div>
            </div>
            <div>
                <strong style="color: #6b7280; font-size: 12px;">Documento:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 5px;">{{ $cliente->tipo_documento }}: {{ $cliente->documento }}</div>
            </div>
            <div>
                <strong style="color: #6b7280; font-size: 12px;">Email Principal:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 5px;">{{ $cliente->email_principal }}</div>
            </div>
            <div>
                <strong style="color: #6b7280; font-size: 12px;">Teléfono:</strong>
                <div style="font-size: 16px; color: #111827; margin-top: 5px;">{{ $cliente->telefono_movil ?? $cliente->telefono_fijo ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Contratos Activos -->
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="number"><i class="fas fa-file-contract"></i> {{ $contratos }}</div>
            <div class="label">Contratos Activos</div>
        </div>

        <!-- Equipos -->
        <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
            <div class="number"><i class="fas fa-server"></i> {{ $equipos }}</div>
            <div class="label">Equipos</div>
        </div>

        <!-- Servicios Últimos 30 días -->
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="number"><i class="fas fa-tools"></i> {{ $servicios_recientes }}</div>
            <div class="label">Servicios (últimos 30 días)</div>
        </div>

        <!-- Estado de Servicios -->
        <div class="stat-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
            <div class="number"><i class="fas fa-chart-bar"></i> {{ array_sum($servicios_por_estado->toArray()) }}</div>
            <div class="label">Total de Servicios</div>
        </div>
    </div>

    <!-- Estado de Servicios Detallado -->
    <div class="card">
        <div class="card-title">Estado de Servicios Activos</div>
        
        @if($servicios_por_estado->isEmpty())
            <p style="color: #6b7280; text-align: center; padding: 20px;">No hay servicios registrados</p>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                @php
                    $estados = [
                        'REPORTADO' => ['color' => '#cffafe', 'text' => '#164e63', 'label' => 'Reportado'],
                        'EN_ESPERA_ASIGNACION' => ['color' => '#fef3c7', 'text' => '#92400e', 'label' => 'En Espera'],
                        'EN_PROCESO' => ['color' => '#fed7aa', 'text' => '#92400b', 'label' => 'En Proceso'],
                        'RESUELTO' => ['color' => '#d1fae5', 'text' => '#065f46', 'label' => 'Resuelto'],
                        'CERRADO' => ['color' => '#f3f4f6', 'text' => '#374151', 'label' => 'Cerrado'],
                    ];
                @endphp
                
                @foreach($estados as $estado => $config)
                    @if(isset($servicios_por_estado[$estado]))
                        <div style="background: {{ $config['color'] }}; color: {{ $config['text'] }}; padding: 15px; border-radius: 6px; text-align: center;">
                            <div style="font-size: 24px; font-weight: 700;">{{ $servicios_por_estado[$estado] }}</div>
                            <div style="font-size: 12px; margin-top: 5px;">{{ $config['label'] }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <!-- Acciones Rápidas -->
    <div class="card" style="margin-top: 30px;">
        <div class="card-title">Acciones Rápidas</div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('portal.contratos') }}" class="btn btn-primary">
                <i class="fas fa-file-contract"></i> Ver Contratos
            </a>
            <a href="{{ route('portal.equipos') }}" class="btn btn-primary">
                <i class="fas fa-server"></i> Ver Equipos
            </a>
            <a href="{{ route('portal.servicios') }}" class="btn btn-primary">
                <i class="fas fa-tools"></i> Ver Servicios
            </a>
        </div>
    </div>
@endsection
