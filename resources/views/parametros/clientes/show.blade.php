@extends('layouts.app')

@section('title', $cliente->razon_social)
@section('page-title', $cliente->razon_social)
@section('page-description', 'Detalles del cliente')

@section('content')
<div class="max-w-5xl space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $cliente->razon_social }}</h1>
            @if($cliente->nombre_comercial)
                <p class="text-gray-600">{{ $cliente->nombre_comercial }}</p>
            @endif
        </div>
        <div class="flex gap-2">
            <a href="{{ route('parametros.clientes.edit', $cliente) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
                <i class="fas fa-edit"></i> Editar
            </a>
            <form action="{{ route('parametros.clientes.destroy', $cliente) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar cliente?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>

    <!-- Grid Principal -->
    <div class="grid grid-cols-3 gap-6">
        <!-- Columna Principal -->
        <div class="col-span-2 space-y-6">
            <!-- Información Básica -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Información Básica</h3>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 text-sm">Tipo de Documento</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $cliente->tipo_documento }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Número Documento</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $cliente->documento }}
                            @if($cliente->digito_verificacion)
                                - DV: {{ $cliente->digito_verificacion }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Empresa Asociada</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $cliente->empresa->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Estado</p>
                        <div>
                            @if($cliente->estado)
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 font-semibold rounded-full text-sm">Activo</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-800 font-semibold rounded-full text-sm">Inactivo</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contacto Principal -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Contacto Principal</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Email Principal</p>
                            <p class="text-gray-900"><i class="fas fa-envelope text-blue-600 mr-2"></i>{{ $cliente->email_principal }}</p>
                        </div>
                        <a href="mailto:{{ $cliente->email_principal }}" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                    
                    @if($cliente->email_secundario)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Email Secundario</p>
                                <p class="text-gray-900"><i class="fas fa-envelope text-blue-600 mr-2"></i>{{ $cliente->email_secundario }}</p>
                            </div>
                            <a href="mailto:{{ $cliente->email_secundario }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    @endif

                    <div class="grid grid-cols-3 gap-4">
                        @if($cliente->telefono_fijo)
                            <div>
                                <p class="text-gray-600 text-sm">Teléfono Fijo</p>
                                <p class="text-gray-900"><i class="fas fa-phone text-green-600 mr-2"></i>{{ $cliente->telefono_fijo }}</p>
                            </div>
                        @endif

                        @if($cliente->telefono_movil)
                            <div>
                                <p class="text-gray-600 text-sm">Teléfono Móvil</p>
                                <p class="text-gray-900"><i class="fas fa-mobile-alt text-green-600 mr-2"></i>{{ $cliente->telefono_movil }}</p>
                            </div>
                        @endif

                        @if($cliente->telefono_whatsapp)
                            <div>
                                <p class="text-gray-600 text-sm">WhatsApp</p>
                                <p class="text-gray-900"><i class="fab fa-whatsapp text-green-600 mr-2"></i>{{ $cliente->telefono_whatsapp }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Dirección y Ubicación -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Dirección de Notificación</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-600 text-sm">Dirección</p>
                        <p class="text-gray-900">{{ $cliente->direccion_notificacion }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Ubicación</p>
                        <p class="text-gray-900">
                            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                            {{ $cliente->ciudadNotificacion->nombre }} - {{ $cliente->ciudadNotificacion->departamento->nombre }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contacto en el Cliente -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Contacto en el Cliente</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-600 text-sm">Nombre</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $cliente->contacto_nombre }}</p>
                    </div>
                    @if($cliente->contacto_cargo)
                        <div>
                            <p class="text-gray-600 text-sm">Cargo</p>
                            <p class="text-gray-900">{{ $cliente->contacto_cargo }}</p>
                        </div>
                    @endif
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600 text-sm">Teléfono</p>
                            <p class="text-gray-900"><i class="fas fa-phone text-green-600 mr-2"></i>{{ $cliente->contacto_telefono }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Email</p>
                            <p class="text-gray-900"><i class="fas fa-envelope text-blue-600 mr-2"></i>{{ $cliente->contacto_email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Datos de Persona Natural -->
            @if($cliente->primer_nombre)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Datos Personales</h3>
                    
                    <div class="space-y-3">
                        @if($cliente->primer_nombre)
                            <div>
                                <p class="text-gray-600 text-sm">Nombre</p>
                                <p class="text-gray-900">{{ $cliente->primer_nombre }} {{ $cliente->segundo_nombre ?? '' }}</p>
                            </div>
                        @endif
                        @if($cliente->primer_apellido)
                            <div>
                                <p class="text-gray-600 text-sm">Apellido</p>
                                <p class="text-gray-900">{{ $cliente->primer_apellido }} {{ $cliente->segundo_apellido ?? '' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Contratos Asociados -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Contratos</h3>
                
                @if($cliente->contratos->count() > 0)
                    <div class="space-y-2">
                        @foreach($cliente->contratos->take(5) as $contrato)
                            <a href="{{ route('contratos.show', $contrato) }}" class="block p-2 hover:bg-gray-100 rounded-lg transition">
                                <p class="font-semibold text-blue-600 text-sm">{{ $contrato->numero_contrato }}</p>
                                <p class="text-xs text-gray-600">{{ $contrato->tipo_contrato }}</p>
                            </a>
                        @endforeach
                        @if($cliente->contratos->count() > 5)
                            <p class="text-xs text-gray-500 pt-2">+{{ $cliente->contratos->count() - 5 }} más</p>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Sin contratos</p>
                @endif
            </div>

            <!-- Sedes Asociadas -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Sedes</h3>
                
                @if($cliente->sedes->count() > 0)
                    <div class="space-y-2">
                        @foreach($cliente->sedes->take(5) as $sede)
                            <a href="{{ route('parametros.sedes.show', $sede) }}" class="block p-2 hover:bg-gray-100 rounded-lg transition">
                                <p class="font-semibold text-blue-600 text-sm">{{ $sede->nombre }}</p>
                                <p class="text-xs text-gray-600">{{ $sede->codigo }}</p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Sin sedes</p>
                @endif
            </div>

            <!-- Información General -->
            <div class="bg-blue-50 rounded-lg p-4">
                <p class="text-xs text-gray-600">
                    <strong>Creado:</strong> {{ $cliente->created_at->diffForHumans() }}
                </p>
                @if($cliente->updated_at != $cliente->created_at)
                    <p class="text-xs text-gray-600 mt-1">
                        <strong>Actualizado:</strong> {{ $cliente->updated_at->diffForHumans() }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Botones de Navegación -->
    <div class="flex justify-between">
        <a href="{{ route('parametros.clientes.index') }}" class="text-blue-600 hover:text-blue-900 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>
@endsection
