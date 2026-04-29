@extends('layouts.app')

@section('title', 'Informe Técnico')
@section('page-title', 'Informe Técnico del Servicio')

@section('content')
<!-- 
    Vista de Informe Técnico - Responsive
    - Adaptado para móvil, tablet y desktop
    - Usa breakpoints: sm (640px), md (768px), lg (1024px), xl (1280px)
    - Padding y márgenes reducidos en móvil
    - Tipografía escalable según pantalla
-->
<div class="container mx-auto px-3 sm:px-4 md:px-6 py-4 sm:py-6 md:py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Encabezado del Informe - Responsive -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4 sm:p-6 md:p-8 rounded-lg mb-4 sm:mb-6 md:mb-8 shadow-lg">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 sm:gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold">📋 Informe Técnico</h1>
                    <p class="text-sm sm:text-base text-blue-100">Servicio #{{ $servicio->id }}</p>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-lg sm:text-xl md:text-2xl font-bold">{{ now()->format('d/m/Y H:i') }}</p>
                    <p class="text-xs sm:text-sm text-blue-100">Generado: {{ now()->format('H:i') }}</p>
                </div>
            </div>
        </div>

        <form id="informe-form" action="{{ route('servicios.store-report', $servicio) }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6 md:space-y-8">
            @csrf

            <!-- CABECERA: DATOS DEL CLIENTE Y CONTRATO - Responsive Grid -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-blue-500">👥 Información del Cliente y Contrato</h2>
                
                <!-- Grid: 1 columna en móvil, 2 en tablet, 3 en desktop -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 md:gap-6">
                    <!-- Cliente - Razón Social -->
                    <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                        <p class="text-xs sm:text-sm text-gray-600 font-semibold uppercase tracking-wider">Razón Social</p>
                        <p class="text-base sm:text-lg font-bold text-gray-900 mt-1 line-clamp-2">{{ $servicio->equipo->area->sede->cliente->razon_social }}</p>
                    </div>

                    <!-- NIT/Identificación -->
                    <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                        <p class="text-xs sm:text-sm text-gray-600 font-semibold uppercase tracking-wider">NIT / Identificación</p>
                        <p class="text-base sm:text-lg font-bold text-gray-900 mt-1">{{ $servicio->equipo->area->sede->cliente->nit ?? 'N/A' }}</p>
                    </div>

                    <!-- Sede -->
                    <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                        <p class="text-xs sm:text-sm text-gray-600 font-semibold uppercase tracking-wider">Sede</p>
                        <p class="text-base sm:text-lg font-bold text-gray-900 mt-1">{{ $servicio->equipo->area->sede->nombre }}</p>
                    </div>

                    <!-- Dirección -->
                    <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                        <p class="text-xs sm:text-sm text-gray-600 font-semibold uppercase tracking-wider">Dirección</p>
                        <p class="text-base sm:text-lg font-bold text-gray-900 mt-1 line-clamp-2">{{ $servicio->equipo->area->sede->direccion ?? 'N/A' }}</p>
                    </div>

                    <!-- Teléfono -->
                    <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                        <p class="text-xs sm:text-sm text-gray-600 font-semibold uppercase tracking-wider">Teléfono</p>
                        <p class="text-base sm:text-lg font-bold text-gray-900 mt-1">{{ $servicio->equipo->area->sede->telefono ?? 'N/A' }}</p>
                    </div>

                    <!-- Ciudad -->
                    <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                        <p class="text-xs sm:text-sm text-gray-600 font-semibold uppercase tracking-wider">Ciudad</p>
                        <p class="text-base sm:text-lg font-bold text-gray-900 mt-1">{{ $servicio->equipo->area->sede->ciudad ?? 'N/A' }}</p>
                    </div>

                    <!-- Número de Contrato - Full width -->
                    <div class="bg-green-50 p-3 sm:p-4 rounded-lg col-span-1 sm:col-span-2 lg:col-span-3">
                        <p class="text-xs sm:text-sm text-gray-600 font-semibold uppercase tracking-wider">Número del Contrato</p>
                        <p class="text-base sm:text-lg font-bold text-gray-900 mt-1">{{ $servicio->contrato->numero_contrato ?? 'Sin Contrato' }} @if($servicio->contrato) <span class="text-sm">({{ $servicio->contrato->descripcion }})</span> @endif</p>
                    </div>
                </div>
            </div>

            <!-- FECHAS Y TIEMPOS DE ATENCIÓN -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-purple-500">⏰ Fechas y Tiempo de Atención</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <!-- Fecha de Solicitud -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Solicitud</label>
                        <div class="bg-gray-100 p-3 rounded-lg border border-gray-300">
                            <p class="font-semibold text-gray-900">{{ $servicio->fecha_solicitud->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Fecha de Atención -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Atención *</label>
                        <input type="date" name="fecha_atencion" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            value="{{ old('fecha_atencion', $servicio->fecha_atencion ? $servicio->fecha_atencion->format('Y-m-d') : '') }}"
                            required>
                        @error('fecha_atencion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hora de Inicio de Atención -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Hora de Inicio de Atención *</label>
                        <input type="time" name="hora_inicio_atencion" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            value="{{ old('hora_inicio_atencion', $servicio->hora_inicio_atencion) }}"
                            required>
                        @error('hora_inicio_atencion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hora de Fin de Atención -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Hora de Fin de Atención *</label>
                        <input type="time" name="hora_fin_atencion" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            value="{{ old('hora_fin_atencion', $servicio->hora_fin_atencion) }}"
                            required>
                        @error('hora_fin_atencion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duración Calculada -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Duración del Servicio</label>
                        <div class="bg-yellow-50 p-4 rounded-lg border-2 border-yellow-300">
                            <p class="text-xl font-bold text-yellow-900">
                                <span id="duracion-servicio">--:--</span> horas
                            </p>
                            <p class="text-sm text-yellow-700 mt-1">Se calcula automáticamente</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TIPO DE SERVICIO -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-green-500">🔧 Tipo de Servicio</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <!-- Tipo de Servicio del Informe -->
                    <div>
                        <label for="tipo_servicio_informe" class="block text-sm font-semibold text-gray-700 mb-2">Seleccione el Tipo de Servicio *</label>
                        <select name="tipo_servicio_informe" id="tipo_servicio_informe"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            required>
                            <option value="">-- Seleccione --</option>
                            <option value="INSTALACION" @if(old('tipo_servicio_informe', $servicio->tipo_servicio_informe) === 'INSTALACION') selected @endif>🔌 Instalación</option>
                            <option value="MANTENIMIENTO_PREVENTIVO" @if(old('tipo_servicio_informe', $servicio->tipo_servicio_informe) === 'MANTENIMIENTO_PREVENTIVO') selected @endif>🛡️ Mantenimiento Preventivo</option>
                            <option value="MANTENIMIENTO_CORRECTIVO" @if(old('tipo_servicio_informe', $servicio->tipo_servicio_informe) === 'MANTENIMIENTO_CORRECTIVO') selected @endif>⚙️ Mantenimiento Correctivo</option>
                            <option value="SOPORTE" @if(old('tipo_servicio_informe', $servicio->tipo_servicio_informe) === 'SOPORTE') selected @endif>🆘 Soporte</option>
                        </select>
                        @error('tipo_servicio_informe')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ¿Es Facturable? -->
                    <div class="flex items-end">
                        <label class="flex items-center gap-3 p-3 border-2 border-green-300 rounded-lg cursor-pointer hover:bg-green-50 flex-1">
                            <input type="checkbox" name="puede_facturarse" value="1"
                                class="w-5 h-5 text-green-600 rounded focus:ring-green-500"
                                @if(old('puede_facturarse', $servicio->puede_facturarse)) checked @endif>
                            <div>
                                <p class="font-semibold text-gray-700">💰 Servicio Facturable</p>
                                <p class="text-xs text-gray-600">Se puede facturar al cliente</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- EQUIPOS ATENDIDOS - RESPONSIVE -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-orange-500">🖥️ Equipos Atendidos</h2>
                
                <!-- Vista Tabla para Desktop (≥768px) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-orange-100">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700">Atendido</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700">Descripción</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700">Modelo</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700">Marca</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700">Serie</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y border">
                            <!-- Equipo Principal -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="equipos_atendidos[{{ $servicio->equipo->id }}]" value="1" checked disabled
                                            class="w-5 h-5 text-orange-600 rounded">
                                    </label>
                                </td>
                                <td class="px-3 py-3 font-semibold text-gray-900">{{ $servicio->equipo->descripcion ?? 'N/A' }} (Principal)</td>
                                <td class="px-3 py-3 text-gray-700">{{ $servicio->equipo->modelo ?? 'N/A' }}</td>
                                <td class="px-3 py-3 text-gray-700">{{ $servicio->equipo->marca ?? 'N/A' }}</td>
                                <td class="px-3 py-3 text-gray-700">{{ $servicio->equipo->serie ?? 'N/A' }}</td>
                            </tr>
                            @if($equiposAdicionales->count() > 0)
                                @foreach($equiposAdicionales as $equipo)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-3">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="equipos_adicionales_atendidos[]" value="{{ $equipo->id }}"
                                                    class="w-5 h-5 text-orange-600 rounded"
                                                    @if(in_array($equipo->id, old('equipos_adicionales_atendidos', $servicio->equipos_adicionales_atendidos ?? []))) checked @endif>
                                            </label>
                                        </td>
                                        <td class="px-3 py-3 text-gray-900">{{ $equipo->descripcion ?? 'N/A' }}</td>
                                        <td class="px-3 py-3 text-gray-700">{{ $equipo->modelo ?? 'N/A' }}</td>
                                        <td class="px-3 py-3 text-gray-700">{{ $equipo->marca ?? 'N/A' }}</td>
                                        <td class="px-3 py-3 text-gray-700">{{ $equipo->serie ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!-- Vista Tarjetas para Móvil (<768px) -->
                <div class="md:hidden space-y-3">
                    <!-- Equipo Principal -->
                    <div class="bg-orange-50 border-2 border-orange-200 rounded-lg p-4">
                        <div class="flex items-start gap-3 mb-3">
                            <input type="checkbox" name="equipos_atendidos[{{ $servicio->equipo->id }}]" value="1" checked disabled
                                class="w-5 h-5 text-orange-600 rounded mt-1 flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-gray-900 text-sm">{{ $servicio->equipo->descripcion ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-600 mt-1">Principal</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-gray-600 font-semibold">Modelo</p>
                                <p class="text-gray-900">{{ $servicio->equipo->modelo ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 font-semibold">Marca</p>
                                <p class="text-gray-900">{{ $servicio->equipo->marca ?? 'N/A' }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-600 font-semibold">Serie</p>
                                <p class="text-gray-900 break-all">{{ $servicio->equipo->serie ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Equipos Adicionales -->
                    @if($equiposAdicionales->count() > 0)
                        @foreach($equiposAdicionales as $equipo)
                            <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4">
                                <div class="flex items-start gap-3 mb-3">
                                    <input type="checkbox" name="equipos_adicionales_atendidos[]" value="{{ $equipo->id }}"
                                        class="w-5 h-5 text-orange-600 rounded mt-1 flex-shrink-0"
                                        @if(in_array($equipo->id, old('equipos_adicionales_atendidos', $servicio->equipos_adicionales_atendidos ?? []))) checked @endif>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-900 text-sm">{{ $equipo->descripcion ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <p class="text-gray-600 font-semibold">Modelo</p>
                                        <p class="text-gray-900">{{ $equipo->modelo ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-semibold">Marca</p>
                                        <p class="text-gray-900">{{ $equipo->marca ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-gray-600 font-semibold">Serie</p>
                                        <p class="text-gray-900 break-all">{{ $equipo->serie ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- DESCRIPCIÓN DE LA SOLICITUD -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-red-500">📝 Descripción de la Solicitud</h2>
                
                <div>
                    <label for="descripcion_solicitud" class="block text-sm font-semibold text-gray-700 mb-2">Descripción Detallada *</label>
                    <textarea name="descripcion_solicitud" id="descripcion_solicitud" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="Describe detalladamente la solicitud del cliente..."
                        required>{{ old('descripcion_solicitud', $servicio->descripcion_solicitud) }}</textarea>
                    @error('descripcion_solicitud')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- DIAGNÓSTICO Y VALIDACIÓN -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-indigo-500">🔍 Diagnóstico / Validación del Servicio</h2>
                
                <div>
                    <label for="diagnostico_validacion" class="block text-sm font-semibold text-gray-700 mb-2">Diagnóstico y Validación *</label>
                    <textarea name="diagnostico_validacion" id="diagnostico_validacion" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Describe el diagnóstico realizado y la validación del servicio..."
                        required>{{ old('diagnostico_validacion', $servicio->diagnostico_validacion) }}</textarea>
                    @error('diagnostico_validacion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- PENDIENTES -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-yellow-500">⚠️ Actividades Pendientes</h2>
                
                <div>
                    <label for="pendientes" class="block text-sm font-semibold text-gray-700 mb-2">Pendientes (Opcional)</label>
                    <textarea name="pendientes" id="pendientes" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                        placeholder="Indica qué actividades quedaron pendientes...">{{ old('pendientes', $servicio->pendientes) }}</textarea>
                    @error('pendientes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- OBSERVACIONES -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-cyan-500">📌 Observaciones</h2>
                
                <div>
                    <label for="observaciones_informe" class="block text-sm font-semibold text-gray-700 mb-2">Observaciones Adicionales (Opcional)</label>
                    <textarea name="observaciones_informe" id="observaciones_informe" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                        placeholder="Notas, recomendaciones, comentarios...">{{ old('observaciones_informe', $servicio->observaciones_informe) }}</textarea>
                    @error('observaciones_informe')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- REPUESTOS Y ACCESORIOS -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-rose-500">🔩 Repuestos y Accesorios Instalados</h2>
                
                <div class="overflow-x-auto mb-4">
                    <table class="w-full border-collapse text-xs sm:text-sm" id="tabla-repuestos">
                        <thead class="bg-rose-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Código</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Descripción</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Marca</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Modelo</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Serie</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Cantidad</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-repuestos" class="divide-y border">
                            @if(is_array(old('repuestos_utilizados')) && count(old('repuestos_utilizados')) > 0)
                                @foreach(old('repuestos_utilizados') as $index => $repuesto)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3"><input type="text" name="repuestos_codigo[]" value="{{ $repuesto['codigo'] ?? '' }}" placeholder="Código" class="w-full border border-gray-300 rounded px-2 py-1"></td>
                                        <td class="px-4 py-3"><input type="text" name="repuestos_descripcion[]" value="{{ $repuesto['descripcion'] ?? '' }}" placeholder="Descripción" class="w-full border border-gray-300 rounded px-2 py-1"></td>
                                        <td class="px-4 py-3"><input type="text" name="repuestos_marca[]" value="{{ $repuesto['marca'] ?? '' }}" placeholder="Marca" class="w-full border border-gray-300 rounded px-2 py-1"></td>
                                        <td class="px-4 py-3"><input type="text" name="repuestos_modelo[]" value="{{ $repuesto['modelo'] ?? '' }}" placeholder="Modelo" class="w-full border border-gray-300 rounded px-2 py-1"></td>
                                        <td class="px-4 py-3"><input type="text" name="repuestos_serie[]" value="{{ $repuesto['serie'] ?? '' }}" placeholder="Serie" class="w-full border border-gray-300 rounded px-2 py-1"></td>
                                        <td class="px-4 py-3 text-center"><input type="number" name="repuestos_cantidad[]" value="{{ $repuesto['cantidad'] ?? 1 }}" min="1" class="w-16 border border-gray-300 rounded px-2 py-1 text-center"></td>
                                        <td class="px-4 py-3 text-center"><button type="button" onclick="eliminarRepuesto(this)" class="text-red-600 hover:text-red-900 font-bold">🗑️</button></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="hover:bg-gray-50" id="fila-vacia">
                                    <td class="px-4 py-3"><input type="text" name="repuestos_codigo[]" placeholder="Código" class="w-full border border-gray-300 rounded px-2 py-1"></td>
                                    <td class="px-4 py-3"><input type="text" name="repuestos_descripcion[]" placeholder="Descripción" class="w-full border border-gray-300 rounded px-2 py-1"></td>
                                    <td class="px-4 py-3"><input type="text" name="repuestos_marca[]" placeholder="Marca" class="w-full border border-gray-300 rounded px-2 py-1"></td>
                                    <td class="px-4 py-3"><input type="text" name="repuestos_modelo[]" placeholder="Modelo" class="w-full border border-gray-300 rounded px-2 py-1"></td>
                                    <td class="px-4 py-3"><input type="text" name="repuestos_serie[]" placeholder="Serie" class="w-full border border-gray-300 rounded px-2 py-1"></td>
                                    <td class="px-4 py-3 text-center"><input type="number" name="repuestos_cantidad[]" value="1" min="1" class="w-16 border border-gray-300 rounded px-2 py-1 text-center"></td>
                                    <td class="px-4 py-3 text-center"><button type="button" onclick="eliminarRepuesto(this)" class="text-red-600 hover:text-red-900 font-bold">🗑️</button></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <button type="button" onclick="agregarRepuesto()" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    ➕ Agregar Repuesto
                </button>
            </div>

            <!-- SECCIÓN 8: Información del Receptor (ya existente) -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-pink-500">👤 Persona que Recibe el Servicio</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-4">
                    <div class="md:col-span-1">
                        <label for="persona_receptora_completa" class="block text-sm font-semibold text-gray-700 mb-2">Nombre y Apellido *</label>
                        <input type="text" name="persona_receptora_completa" id="persona_receptora_completa"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500"
                            placeholder="Ej: Juan Pérez"
                            value="{{ old('persona_receptora_completa', trim(($servicio->persona_receptora_nombre ?? '') . ' ' . ($servicio->persona_receptora_apellido ?? ''))) }}"
                            required>
                    </div>

                    <!-- Campos ocultos para mantener compatibilidad con base de datos -->
                    <input type="hidden" name="persona_receptora_nombre" id="persona_receptora_nombre" value="{{ old('persona_receptora_nombre', $servicio->persona_receptora_nombre) }}">
                    <input type="hidden" name="persona_receptora_apellido" id="persona_receptora_apellido" value="{{ old('persona_receptora_apellido', $servicio->persona_receptora_apellido) }}">

                    <div class="md:col-span-2">
                        <label for="persona_receptora_documento" class="block text-sm font-semibold text-gray-700 mb-2">Documento/Cédula *</label>
                        <input type="text" name="persona_receptora_documento" id="persona_receptora_documento"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500"
                            placeholder="Ej: 12345678"
                            value="{{ old('persona_receptora_documento', $servicio->persona_receptora_documento) }}"
                            required>
                        @error('persona_receptora_documento')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 9: Estado del Servicio -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-teal-500">🔄 Estado del Servicio</h2>
                
                <label for="estado_servicio_id" class="block text-sm font-semibold text-gray-700 mb-2">Estado Actual *</label>
                <select name="estado_servicio_id" id="estado_servicio_id" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    required>
                    <option value="">-- Seleccione un estado --</option>
                    @foreach($estadosDisponibles as $estado)
                        <option value="{{ $estado->id }}" 
                            @if($servicio->estado_servicio_id === $estado->id) selected @endif>
                            {{ $estado->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('estado_servicio_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- SECCIÓN 10: Imágenes del Servicio -->
            <div class="bg-white shadow-lg rounded-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-4 border-b-2 border-violet-500">📸 Imágenes del Servicio</h2>
                
                <label for="imagenes" class="block text-sm font-semibold text-gray-700 mb-2">Carga fotos del trabajo realizado (máximo 10 imágenes, 5MB cada una)</label>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:bg-gray-50"
                    onclick="document.getElementById('imagenes').click()">
                    <input type="file" id="imagenes" name="imagenes[]" multiple accept="image/*" 
                        class="hidden" onchange="mostrarPreviewImagenes(this)">
                    <p class="text-gray-600">📷 Haz clic aquí o arrastra imágenes</p>
                </div>
                
                <div id="imagenes-preview" class="grid grid-cols-3 md:grid-cols-4 gap-4 mt-4"></div>
                @error('imagenes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- SECCIÓN 11: Firma del Receptor - Responsive -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-emerald-500">✍️ Firma del Receptor</h2>
                
                <!-- Instrucciones responsive -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-3 sm:p-4 mb-4 rounded">
                    <p class="text-xs sm:text-sm text-blue-800 font-bold">📌 Instrucciones:</p>
                    <ul class="text-xs sm:text-sm text-blue-700 mt-2 ml-4 space-y-1">
                        <li>✓ Haz clic en el recuadro blanco</li>
                        <li>✓ Dibuja la firma (mouse, táctil, o stylus)</li>
                        <li>✓ Indicador verde cuando esté lista</li>
                        <li>✓ Usa "Limpiar" para reintentar</li>
                    </ul>
                </div>
                
                <!-- Canvas responsive para firma -->
                <div class="border-2 border-gray-300 rounded-lg p-3 sm:p-4 bg-gray-50" style="touch-action: none;">
                    <!-- 
                        Canvas signature pad - Responsive
                        - Sin ancho/alto fijo (se calcula con JavaScript)
                        - Responsive: 100% ancho en móvil, limitado en desktop
                        - Altura: 120px en móvil, 150px en tablet+
                        - Mantiene proporción correcta con viewport
                        - touch-action: none permite captura táctil completa
                    -->
                    <canvas id="firma-canvas" 
                        style="border: 2px solid #ddd; cursor: crosshair; display: block; background: white; margin: 0 auto 10px; border-radius: 4px; max-width: 100%; touch-action: none;"
                        class="responsive-canvas"></canvas>
                    <input type="hidden" name="firma_persona_receptora" id="firma_input">
                    
                    <!-- Indicador de estado de firma -->
                    <div id="firma-status" class="text-center mb-3 text-xs sm:text-sm font-semibold">
                        <span id="firma-status-text" class="text-gray-500">⭕ Esperando firma...</span>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="button" onclick="limpiarFirma()" 
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                            🔄 Limpiar
                        </button>
                        <button type="button" onclick="guardarFirma()" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            💾 Guardar Firma
                        </button>
                    </div>
                </div>
                
                @error('firma_persona_receptora')
                    <p class="text-red-500 text-sm mt-3 font-semibold">⚠️ {{ $message }}</p>
                @enderror
            </div>

            <!-- Botones de Acción -->
            <div class="flex gap-2 sm:gap-4 flex-wrap pt-6 border-t-2 border-gray-200 sticky bottom-0 bg-white p-4 rounded-lg shadow-lg">
                <button type="submit" form="informe-form"
                    class="flex-1 min-w-[140px] bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition text-center">
                    ✅ Guardar Informe
                </button>

                <a href="{{ route('servicios.show', $servicio) }}" 
                    class="flex-1 min-w-[140px] bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition text-center">
                    ❌ Cancelar
                </a>
                <button type="submit" 
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                    ✅ Guardar Informe Técnico
                </button>
            </div>
        </form>
    </div>
</div>

<!-- LibreSignature.js para firma digital -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>
/**
 * Canvas Signature Pad - Responsive
 * ==========================================
 * Gestiona la captura de firma con soporte completo para móvil, tablet y desktop
 * - Recalcula tamaño según viewport
 * - Soporta touch events y mouse
 * - Mantiene DPR (Device Pixel Ratio) para pantallas high-DPI
 * - Se adapta a cambios de orientación
 */

// Variables globales
let signaturePad;
let canvas;

/**
 * Debounce - Limita la frecuencia de ejecución de una función
 * Útil para evitar múltiples cálculos durante resize
 * @param {Function} func - Función a ejecutar
 * @param {Number} wait - Milisegundos a esperar
 * @returns {Function} - Función debounceada
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Inicializar canvas y SignaturePad
document.addEventListener('DOMContentLoaded', function() {
    canvas = document.getElementById('firma-canvas');
    if (!canvas) return;
    
    // Esperar un poco más para que el DOM esté completamente listo
    setTimeout(function() {
        // Ajustar tamaño ANTES de inicializar
        resizeCanvas();
        
        // Crear SignaturePad
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)',
            dotSize: 2,
            minWidth: 0.5,
            maxWidth: 2.5,
            throttle: 16,
            minDistance: 5,
            onEnd: actualizarEstadoFirma
        });
        
        // Ajustar tamaño inicial
        window.addEventListener('resize', debounce(resizeCanvas, 300));
        window.addEventListener('orientationchange', debounce(resizeCanvas, 300));
        
        actualizarEstadoFirma();
    }, 200);
});

/**
 * Ajustar tamaño del canvas responsivamente
 */
function resizeCanvas() {
    if (!canvas) return;
    
    const container = canvas.parentElement;
    if (!container) return;
    
    const rect = container.getBoundingClientRect();
    const width = rect.width - 16; // Restar padding
    const dpr = window.devicePixelRatio || 1;
    
    // Altura según viewport
    let height = 120;
    if (window.innerWidth >= 640) height = 130;
    if (window.innerWidth >= 768) height = 140;
    if (window.innerWidth >= 1024) height = 150;
    
    // Establecer dimensiones
    canvas.width = width * dpr;
    canvas.height = height * dpr;
    
    // Escalar para DPR
    const ctx = canvas.getContext('2d');
    if (ctx) {
        ctx.scale(dpr, dpr);
    }
    
    // Dimensiones CSS
    canvas.style.width = width + 'px';
    canvas.style.height = height + 'px';
}

function guardarFirma() {
    if (signaturePad.isEmpty()) {
        alert('⚠️ Por favor, dibuja la firma antes de guardarla.');
        return;
    }
    
    const firmaData = signaturePad.toDataURL('image/png');
    document.getElementById('firma_input').value = firmaData;
    actualizarEstadoFirma();
    alert('✅ Firma guardada correctamente.');
}

function actualizarEstadoFirma() {
    const statusElement = document.getElementById('firma-status-text');
    const inputElement = document.getElementById('firma_input');
    
    if (!signaturePad.isEmpty()) {
        // Guardar automáticamente
        const firmaData = signaturePad.toDataURL('image/png');
        inputElement.value = firmaData;
        
        statusElement.textContent = '✅ Firma lista (se guardará automáticamente)';
        statusElement.className = 'text-green-600 font-bold';
    } else {
        inputElement.value = '';
        statusElement.textContent = '⭕ Esperando firma...';
        statusElement.className = 'text-gray-500';
    }
}

function limpiarFirma() {
    signaturePad.clear();
    document.getElementById('firma_input').value = '';
    actualizarEstadoFirma();
}

// Manejar imágenes
function mostrarPreviewImagenes(input) {
    const previewDiv = document.getElementById('imagenes-preview');
    previewDiv.innerHTML = '';
    
    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="w-full h-32 object-cover rounded border border-gray-300">
                    <button type="button" onclick="eliminarImagen(this, ${index})" class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-700">×</button>
                `;
                previewDiv.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function eliminarImagen(btn, index) {
    // Simplemente ocultar la vista previa, los archivos se controlan en input.files
    btn.parentElement.remove();
}

// Repuestos - Tabla responsiva
function agregarRepuesto() {
    const tbody = document.getElementById('tbody-repuestos');
    const fila = document.createElement('tr');
    fila.className = 'hover:bg-gray-50 text-xs sm:text-sm';
    fila.innerHTML = `
        <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3"><input type="text" name="repuestos_codigo[]" placeholder="Código" class="w-full border border-gray-300 rounded px-2 py-1 text-xs sm:text-sm"></td>
        <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3"><input type="text" name="repuestos_descripcion[]" placeholder="Descripción" class="w-full border border-gray-300 rounded px-2 py-1 text-xs sm:text-sm"></td>
        <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3"><input type="text" name="repuestos_marca[]" placeholder="Marca" class="w-full border border-gray-300 rounded px-2 py-1 text-xs sm:text-sm"></td>
        <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3"><input type="text" name="repuestos_modelo[]" placeholder="Modelo" class="w-full border border-gray-300 rounded px-2 py-1 text-xs sm:text-sm"></td>
        <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3"><input type="text" name="repuestos_serie[]" placeholder="Serie" class="w-full border border-gray-300 rounded px-2 py-1 text-xs sm:text-sm"></td>
        <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-center"><input type="number" name="repuestos_cantidad[]" value="1" min="1" class="w-12 sm:w-16 border border-gray-300 rounded px-2 py-1 text-center text-xs sm:text-sm"></td>
        <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-center"><button type="button" onclick="eliminarRepuesto(this)" class="text-red-600 hover:text-red-900 font-bold">🗑️</button></td>
    `;
    tbody.appendChild(fila);
}

function eliminarRepuesto(btn) {
    btn.closest('tr').remove();
}

// Procesar nombre y apellido
document.addEventListener('DOMContentLoaded', function() {
    const completoInput = document.getElementById('persona_receptora_completa');
    const nombreInput = document.getElementById('persona_receptora_nombre');
    const apellidoInput = document.getElementById('persona_receptora_apellido');
    
    if (completoInput && nombreInput && apellidoInput) {
        completoInput.addEventListener('blur', function() {
            const partes = this.value.trim().split(/\s+/);
            if (partes.length >= 2) {
                nombreInput.value = partes[0];
                apellidoInput.value = partes.slice(1).join(' ');
            } else if (partes.length === 1) {
                nombreInput.value = partes[0];
                apellidoInput.value = '';
            }
        });
        
        // Procesar valor inicial si existe
        if (completoInput.value.trim()) {
            completoInput.blur();
        }
    }
});

// Calcular duración
function calcularDuracion() {
    const inicio = document.getElementById('hora_inicio_atencion')?.value;
    const fin = document.getElementById('hora_fin_atencion')?.value;
    
    if (inicio && fin) {
        const [horaIni, minIni] = inicio.split(':').map(Number);
        const [horaFin, minFin] = fin.split(':').map(Number);
        
        const minutosTotales = (horaFin * 60 + minFin) - (horaIni * 60 + minIni);
        const horas = Math.floor(minutosTotales / 60);
        const minutos = minutosTotales % 60;
        
        if (minutosTotales >= 0) {
            document.getElementById('duracion-servicio').textContent = `${horas}:${String(minutos).padStart(2, '0')}`;
        }
    }
}

// Agregar listeners al cargar DOM
document.addEventListener('DOMContentLoaded', function() {
    const inicioInput = document.getElementById('hora_inicio_atencion');
    const finInput = document.getElementById('hora_fin_atencion');
    
    if (inicioInput) inicioInput.addEventListener('change', calcularDuracion);
    if (finInput) finInput.addEventListener('change', calcularDuracion);
    
    // Calcular duración inicial si hay valores
    setTimeout(calcularDuracion, 100);
});

// Enviar formulario
document.querySelector('form')?.addEventListener('submit', function(e) {
    // Asegurar que la firma esté guardada
    if (!signaturePad.isEmpty()) {
        const firmaData = signaturePad.toDataURL('image/png');
        document.getElementById('firma_input').value = firmaData;
    }
    
    // Validar que hay firma
    const firmaInput = document.getElementById('firma_input').value;
    if (!firmaInput || firmaInput.length < 50) {
        e.preventDefault();
        alert('⚠️ Debe dibujar la firma del receptor.\n\nPasos:\n1. Haz clic en el área blanca\n2. Dibuja la firma con el mouse\n3. La firma se guardará automáticamente\n4. Luego envía el formulario');
        return false;
    }
});
</script>

@endsection
