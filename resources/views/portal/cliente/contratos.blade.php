@extends('portal.cliente.layout')

@section('title', 'Contratos - Portal del Cliente')

@section('content')
    <h1 class="text-2xl font-bold mb-6">
        <i class="fas fa-file-contract text-blue-500 mr-2"></i>
        Contratos Activos
    </h1>

    @if($contratos->isEmpty())
        <x-card class="text-center py-10">
            <x-empty-state icon="fa-file-contract" message="No tienes contratos activos en este momento." />
        </x-card>
    @else
        @foreach($contratos as $contrato)
            <x-card class="mb-5">
                <div class="grid grid-cols-1 lg:grid-cols-[auto_1fr_auto] gap-5 items-start">
                    <!-- Número de Contrato -->
                    <div class="text-[28px] font-bold text-blue-500">
                        #{{ $contrato->numero_contrato }}
                    </div>

                    <!-- Detalles -->
                    <div>
                        <table class="w-full border-collapse">
                            <tr>
                                <td class="w-1/2 py-2 border-0">
                                    <p class="text-xs text-gray-500 font-semibold">Tipo de Contrato:</p>
                                    <div class="text-gray-900 mt-0.5">{{ $contrato->tipo_contrato }}</div>
                                </td>
                                <td class="w-1/2 py-2 border-0 pl-5">
                                    <p class="text-xs text-gray-500 font-semibold">Período:</p>
                                    <div class="text-gray-900 mt-0.5">
                                        {{ $contrato->fecha_inicio->format('d/m/Y') }} - {{ $contrato->fecha_vencimiento->format('d/m/Y') }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-1/2 py-2 border-0">
                                    <p class="text-xs text-gray-500 font-semibold">Estado:</p>
                                    <div class="mt-0.5">
                                        <x-badge :variant="$contrato->estado === 'ACTIVO' ? 'green' : 'red'">
                                            {{ $contrato->estado }}
                                        </x-badge>
                                    </div>
                                </td>
                                <td class="w-1/2 py-2 border-0 pl-5">
                                    <p class="text-xs text-gray-500 font-semibold">Valor Contrato:</p>
                                    <div class="text-gray-900 mt-0.5 font-semibold">
                                        ${{ number_format($contrato->valor_total, 0, ',', '.') }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- SLA Info -->
                    <div class="bg-blue-50 p-4 rounded-md border-l-[3px] border-blue-500 hidden lg:block">
                        <div class="text-xs text-gray-500 mb-2">
                            <strong>Cobertura de Servicios:</strong>
                        </div>
                        <div class="text-[13px] text-gray-900 leading-relaxed">
                            @if($contrato->servicios)
                                @php
                                    $serviciosIncluidos = $contrato->servicios->where('incluido', true)->pluck('tipo_servicio');
                                @endphp
                                @foreach($serviciosIncluidos as $servicio)
                                    <div><i class="fas fa-check text-emerald-500"></i> {{ $servicio }}</div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- SLA Times -->
                @if($contrato->servicios)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 mb-3"><strong>SLA por Tipo de Servicio:</strong></p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                            @foreach($contrato->servicios->where('incluido', true) as $servicio)
                                <div class="bg-gray-50 p-3 rounded-md border border-gray-200 text-xs">
                                    <div class="font-semibold text-gray-900 mb-1">{{ $servicio->tipo_servicio }}</div>
                                    <div class="text-gray-500">
                                        Respuesta: <strong>{{ $servicio->sla_horas_respuesta }}h</strong> | Solución: <strong>{{ $servicio->sla_horas_solucion }}h</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </x-card>
        @endforeach
    @endif
@endsection
