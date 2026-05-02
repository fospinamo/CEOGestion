@extends('layouts.app')

@section('title', $contrato->numero_contrato)
@section('page-title', 'Contrato: ' . $contrato->numero_contrato)
@section('page-description', 'Detalles del contrato')

@section('content')
<div class="max-w-5xl space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $contrato->numero_contrato }}</h1>
            <p class="text-gray-600">{{ $contrato->cliente->razon_social }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('contratos.edit', $contrato) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                <i class="fas fa-edit"></i> Editar
            </a>
            <form action="{{ route('contratos.destroy', $contrato) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <!-- Columna Principal -->
        <div class="col-span-2 space-y-6">
            <!-- Información General -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Información General</h3>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 text-sm">Cliente</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $contrato->cliente->razon_social }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Empresa</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $contrato->cliente->empresa->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Tipo de Contrato</p>
                        <p class="text-gray-900">{{ str_replace('_', ' ', $contrato->tipo_contrato) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Modalidad</p>
                        <p class="text-gray-900">{{ $contrato->modalidad }}</p>
                    </div>
                </div>
            </div>

            <!-- Fechas y Valor -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Fechas y Valor</h3>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 text-sm">Período</p>
                        <p class="text-gray-900">{{ $contrato->fecha_inicio->format('d/m/Y') }} - {{ $contrato->fecha_fin->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Fecha de Firma</p>
                        <p class="text-gray-900">{{ $contrato->fecha_firma->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Valor</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($contrato->valor_contrato, 0, ',', '.') }} {{ $contrato->moneda }}</p>
                    </div>
                    @if($contrato->fecha_terminacion)
                        <div>
                            <p class="text-gray-600 text-sm">Fecha Terminación</p>
                            <p class="text-gray-900">{{ $contrato->fecha_terminacion->format('d/m/Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Descripción del Servicio -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Descripción del Servicio</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Alcance</p>
                        <p class="text-gray-900">{{ $contrato->alcance_servicios }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Condiciones de Pago</p>
                        <p class="text-gray-900">{{ $contrato->condiciones_pago }}</p>
                    </div>
                    @if($contrato->clausulas_especiales)
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Cláusulas Especiales</p>
                            <p class="text-gray-900">{{ $contrato->clausulas_especiales }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Servicios Asociados -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Servicios Asociados</h3>
                
                @if($contrato->servicios->count() > 0)
                    <div class="space-y-2">
                        @foreach($contrato->servicios->take(10) as $servicio)
                            <a href="{{ route('incidencias.servicios.show', $servicio) }}" class="block p-3 hover:bg-gray-100 rounded-lg transition border">
                                <p class="font-semibold text-blue-600">{{ $servicio->descripcion_problema }}</p>
                                <p class="text-xs text-gray-600">{{ $servicio->estado }}</p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Sin servicios registrados</p>
                @endif
            </div>

            <!-- Documentos -->
            @if($contrato->documentosAdjuntos->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Documentos</h3>
                    
                    <div class="space-y-2">
                        @foreach($contrato->documentosAdjuntos as $doc)
                            <div class="flex justify-between items-center p-3 hover:bg-gray-100 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $doc->nombre_archivo }}</p>
                                    <p class="text-xs text-gray-600">{{ $doc->tipo_documento }}</p>
                                </div>
                                <a href="{{ route('documentos.download', $doc) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Estado -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Estado</h3>
                
                @php
                    $estadoColor = match($contrato->estado) {
                        'ACTIVO' => 'green',
                        'BORRADOR' => 'yellow',
                        'VENCIDO' => 'red',
                        'TERMINADO' => 'gray',
                        'RENOVADO' => 'blue',
                        default => 'gray'
                    };
                @endphp
                <span class="inline-block px-4 py-2 text-sm font-semibold rounded-full bg-{{ $estadoColor }}-100 text-{{ $estadoColor }}-800">
                    {{ $contrato->estado }}
                </span>

                <div class="mt-4 space-y-2">
                    <label class="flex items-center gap-2 text-sm">
                        <i class="fas fa-file text-{{ $contrato->documento_firmado ? 'green' : 'gray' }}-600"></i>
                        {{ $contrato->documento_firmado ? 'Documento Firmado' : 'Pendiente de Firma' }}
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <i class="fas fa-sync text-{{ $contrato->renovacion_automatica ? 'green' : 'gray' }}-600"></i>
                        {{ $contrato->renovacion_automatica ? 'Renovación Automática' : 'Sin Renovación Automática' }}
                    </label>
                </div>
            </div>

            <!-- Creación -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Información</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Creado Por</p>
                        <p class="text-gray-900">{{ $contrato->creadoPor->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Fecha Creación</p>
                        <p class="text-gray-900">{{ $contrato->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($contrato->updated_by)
                        <div>
                            <p class="text-gray-600">Última Actualización</p>
                            <p class="text-gray-900">{{ $contrato->modificadoPor->name ?? 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Agregar Documento -->
            <a href="{{ route('documentos.create', ['entidad_type' => 'App\Models\Contrato', 'entidad_id' => $contrato->id]) }}" class="block w-full text-center bg-blue-50 hover:bg-blue-100 text-blue-600 font-semibold py-2 px-4 rounded-lg transition">
                <i class="fas fa-upload mr-2"></i> Cargar Documento
            </a>
        </div>
    </div>

    <!-- Botón Volver -->
    <a href="{{ route('contratos.index') }}" class="text-blue-600 hover:text-blue-900 flex items-center gap-2">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@endsection
