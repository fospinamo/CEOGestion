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

        <form id="informe-form" action="{{ route('incidencias.servicios.store-report', $servicio) }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6 md:space-y-8">
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
                        <p class="text-base sm:text-lg font-bold text-gray-900 mt-1">{{ $servicio->equipo->area->sede->cliente->documento_formateado ?? $servicio->equipo->area->sede->cliente->documento ?? 'N/A' }}</p>
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
                        <p class="text-base sm:text-lg font-bold text-gray-900 mt-1">{{ $servicio->equipo->area->sede->municipio->nombre ?? $servicio->equipo->area->sede->cliente->ciudadNotificacion->nombre ?? 'N/A' }}</p>
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
                            value="{{ old('hora_inicio_atencion', $servicio->hora_inicio_atencion ? \Carbon\Carbon::parse($servicio->hora_inicio_atencion)->format('H:i') : '') }}"
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
                            value="{{ old('hora_fin_atencion', $servicio->hora_fin_atencion ? \Carbon\Carbon::parse($servicio->hora_fin_atencion)->format('H:i') : '') }}"
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

            <!-- EQUIPOS EN LA MISMA UBICACIÓN -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-orange-500">🧰 Equipos en la Misma Ubicación</h2>
                <p class="text-sm text-gray-600 mb-4">Seleccione los equipos que se revisaron durante la visita en esta misma ubicación.</p>

                @php
                    $equiposSeleccionados = collect(old('equipos_adicionales', $servicio->equipos_adicionales_atendidos ?? []))
                        ->map(fn($id) => (int) $id)
                        ->all();
                @endphp

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-3 py-2 text-left w-16">Sel.</th>
                                <th class="px-3 py-2 text-left">Código</th>
                                <th class="px-3 py-2 text-left">Tipo</th>
                                <th class="px-3 py-2 text-left">Marca / Modelo</th>
                                <th class="px-3 py-2 text-left">Serial</th>
                                <th class="px-3 py-2 text-left">Contrato</th>
                                <th class="px-3 py-2 text-left">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($equiposAdicionales as $equipoUbicacion)
                                @php
                                    $esPrincipal = $equipoUbicacion->id === $servicio->equipo_id;
                                @endphp
                                <tr class="border-t {{ $esPrincipal ? 'bg-blue-50' : 'bg-white' }}">
                                    <td class="px-3 py-2">
                                        @if($esPrincipal)
                                            <span class="inline-block px-2 py-1 text-xs rounded bg-blue-100 text-blue-800 font-semibold">Principal</span>
                                        @else
                                            <input type="checkbox" name="equipos_adicionales[]" value="{{ $equipoUbicacion->id }}"
                                                class="w-4 h-4"
                                                {{ in_array($equipoUbicacion->id, $equiposSeleccionados, true) ? 'checked' : '' }}>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 font-semibold text-gray-900">{{ $equipoUbicacion->codigo_activo_cliente ?? ('EQ-' . $equipoUbicacion->id) }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $equipoUbicacion->tipoEquipo->nombre ?? 'N/A' }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $equipoUbicacion->marca->nombre ?? 'N/A' }} {{ $equipoUbicacion->modelo ?? '' }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $equipoUbicacion->serial ?? 'N/A' }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $equipoUbicacion->contrato->numero_contrato ?? 'Sin contrato' }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $equipoUbicacion->estado_operativo ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-4 text-center text-gray-500">No hay equipos registrados en esta ubicación.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @error('equipos_adicionales')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- REPUESTOS INSTALADOS -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2" style="border-color: #f59e0b;">
                    <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900">🔩 Repuestos Instalados</h2>
                    <button type="button" onclick="abrirModalRepuesto()"
                        class="inline-flex items-center gap-2 text-white font-semibold py-2 px-4 rounded-lg transition text-sm shadow"
                        style="background-color: #f59e0b;">
                        <i class="fas fa-plus"></i> Agregar Repuesto
                    </button>
                </div>

                <div id="repuestos-list" class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full text-sm">
                        <thead style="background-color: #fef3c7;">
                            <tr>
                                <th class="px-3 py-2 text-left text-amber-800">Código</th>
                                <th class="px-3 py-2 text-left text-amber-800">Descripción</th>
                                <th class="px-3 py-2 text-left text-amber-800">Marca</th>
                                <th class="px-3 py-2 text-left text-amber-800">Modelo</th>
                                <th class="px-3 py-2 text-left text-amber-800">Serial</th>
                                <th class="px-3 py-2 text-center text-amber-800">Cant.</th>
                                <th class="px-3 py-2 text-center text-amber-800">Facturable</th>
                                <th class="px-3 py-2 text-left text-amber-800">N° Factura</th>
                                <th class="px-3 py-2 text-center text-amber-800">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="repuestos-tbody">
                            @forelse($servicio->repuestos->load('marca') as $repuesto)
                                <tr class="border-t hover:bg-gray-50" data-repuesto-id="{{ $repuesto->id }}">
                                    <td class="px-3 py-2 font-semibold text-gray-900">{{ $repuesto->codigo ?? '-' }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $repuesto->descripcion }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $repuesto->marca->nombre ?? '-' }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $repuesto->modelo ?? '-' }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $repuesto->serial ?? '-' }}</td>
                                    <td class="px-3 py-2 text-center font-semibold">{{ $repuesto->cantidad }}</td>
                                    <td class="px-3 py-2 text-center">
                                        @if($repuesto->facturable)
                                            <span class="inline-block px-2 py-1 text-xs rounded-full font-semibold" style="background-color: #d1fae5; color: #065f46;">Sí</span>
                                        @else
                                            <span class="inline-block px-2 py-1 text-xs rounded-full" style="background-color: #f3f4f6; color: #374151;">No</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-gray-700">{{ $repuesto->numero_factura ?? '-' }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <button type="button" onclick='editarRepuesto(@json($repuesto))' class="text-amber-600 hover:text-amber-800 mx-1" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" onclick="eliminarRepuesto({{ $repuesto->id }})" class="text-red-600 hover:text-red-800 mx-1" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="repuestos-empty">
                                    <td colspan="9" class="px-3 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center gap-2">
                                            <i class="fas fa-box-open text-3xl text-gray-300"></i>
                                            <p>No hay repuestos registrados</p>
                                            <p class="text-xs text-gray-400">Haga clic en "Agregar Repuesto" para añadir uno</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- DESCRIPCIÓN DEL PROBLEMA (SOLO LECTURA) -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-red-500">📝 Descripción del Problema (Solicitud Registrada)</h2>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Campo informativo (no editable)</label>
                    <div class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 text-gray-800 min-h-[96px] whitespace-pre-wrap">{{ $servicio->descripcion_problema ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- DIAGNÓSTICO Y VALIDACIÓN -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-indigo-500">🔍 Diagnóstico / Validación / Labor realizada</h2>
                
                <div>
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <label for="diagnostico_validacion" class="block text-sm font-semibold text-gray-700">Diagnóstico / Validación / Labor realizada *</label>
                        <div class="flex gap-2">
                            <button type="button" data-dictation-target="diagnostico_validacion" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-indigo-300 text-indigo-700 hover:bg-indigo-50 transition">
                                🎤 Dictar
                            </button>
                            <button type="button" data-ia-target="diagnostico_validacion" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-blue-300 text-blue-700 hover:bg-blue-50 transition hidden" id="btn-ia-diagnostico_validacion">
                                🤖 Mejorar con IA
                            </button>
                        </div>
                    </div>
                    <textarea name="diagnostico_validacion" id="diagnostico_validacion" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Describe el diagnóstico realizado y la validación del servicio..."
                        required>{{ old('diagnostico_validacion', $servicio->diagnostico_validacion) }}</textarea>
                    
                    <!-- Campo oculto para guardar texto original -->
                    <input type="hidden" id="diagnostico_validacion_original" name="diagnostico_validacion_original" value="">
                    
                    <p class="text-xs text-gray-500 mt-1" data-dictation-status-for="diagnostico_validacion">Haz clic en Dictar para iniciar reconocimiento de voz.</p>
                    @error('diagnostico_validacion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- OBSERVACIONES -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-cyan-500">📌 Observaciones</h2>
                
                <div>
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <label for="observaciones_informe" class="block text-sm font-semibold text-gray-700">Observaciones Adicionales (Opcional)</label>
                        <div class="flex gap-2">
                            <button type="button" data-dictation-target="observaciones_informe" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-cyan-300 text-cyan-700 hover:bg-cyan-50 transition">
                                🎤 Dictar
                            </button>
                            <button type="button" data-ia-target="observaciones_informe" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-blue-300 text-blue-700 hover:bg-blue-50 transition hidden" id="btn-ia-observaciones_informe">
                                🤖 Mejorar con IA
                            </button>
                        </div>
                    </div>
                    <textarea name="observaciones_informe" id="observaciones_informe" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                        placeholder="Notas, recomendaciones, comentarios...">{{ old('observaciones_informe', $servicio->observaciones_informe) }}</textarea>
                    
                    <!-- Campo oculto para guardar texto original -->
                    <input type="hidden" id="observaciones_informe_original" name="observaciones_informe_original" value="">
                    
                    <p class="text-xs text-gray-500 mt-1" data-dictation-status-for="observaciones_informe"></p>
                    @error('observaciones_informe')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- PERSONA RECEPTORA -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-pink-500">👤 Persona que Recibe el Servicio</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label for="persona_receptora_nombre" class="block text-sm font-semibold text-gray-700 mb-2">Nombre *</label>
                        <input type="text" name="persona_receptora_nombre" id="persona_receptora_nombre"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500"
                            value="{{ old('persona_receptora_nombre', $servicio->persona_receptora_nombre) }}"
                            required>
                        @error('persona_receptora_nombre')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="persona_receptora_apellido" class="block text-sm font-semibold text-gray-700 mb-2">Apellido *</label>
                        <input type="text" name="persona_receptora_apellido" id="persona_receptora_apellido"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500"
                            value="{{ old('persona_receptora_apellido', $servicio->persona_receptora_apellido) }}"
                            required>
                        @error('persona_receptora_apellido')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="persona_receptora_documento" class="block text-sm font-semibold text-gray-700 mb-2">Documento/Cédula *</label>
                        <input type="text" name="persona_receptora_documento" id="persona_receptora_documento"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500"
                            value="{{ old('persona_receptora_documento', $servicio->persona_receptora_documento) }}"
                            required>
                        @error('persona_receptora_documento')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ESTADO DEL SERVICIO -->
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

            <!-- FIRMA DEL RECEPTOR -->
            <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-4 sm:mb-5 md:mb-6 pb-3 sm:pb-4 border-b-2 border-emerald-500">✍️ Firma del Receptor</h2>
                
                <div class="border-2 border-gray-300 rounded-lg p-3 sm:p-4 bg-gray-50" style="touch-action: none;">
                    <canvas id="firma-canvas" 
                        style="border: 2px solid #ddd; cursor: crosshair; display: block; background: white; margin: 0 auto 10px; border-radius: 4px; max-width: 100%; touch-action: none;"
                        class="responsive-canvas"></canvas>
                    <input type="hidden" name="firma_persona_receptora" id="firma_input">
                    
                    <div class="flex gap-2 mt-3">
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
                    <p class="text-red-500 text-sm mt-3">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones de Acción -->
            <div class="flex gap-2 sm:gap-4 flex-wrap pt-6 border-t-2 border-gray-200 sticky bottom-0 bg-white p-4 rounded-lg shadow-lg">
                <button type="submit"
                    class="flex-1 min-w-[140px] bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition text-center">
                    ✅ Guardar Informe
                </button>

                <a href="{{ route('incidencias.servicios.show', $servicio) }}" 
                    class="flex-1 min-w-[140px] bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition text-center">
                    ❌ Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- LibreSignature.js para firma digital -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>
let signaturePad;
let canvas;

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

document.addEventListener('DOMContentLoaded', function() {
    canvas = document.getElementById('firma-canvas');
    if (!canvas) return;
    
    setTimeout(function() {
        resizeCanvas();
        
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
        
        window.addEventListener('resize', debounce(resizeCanvas, 300));
        window.addEventListener('orientationchange', debounce(resizeCanvas, 300));
        
        actualizarEstadoFirma();
    }, 200);
});

function resizeCanvas() {
    if (!canvas) return;
    
    const container = canvas.parentElement;
    if (!container) return;
    
    const rect = container.getBoundingClientRect();
    const width = rect.width - 16;
    const dpr = window.devicePixelRatio || 1;
    
    let height = 120;
    if (window.innerWidth >= 640) height = 130;
    if (window.innerWidth >= 768) height = 140;
    if (window.innerWidth >= 1024) height = 150;
    
    canvas.width = width * dpr;
    canvas.height = height * dpr;
    
    const ctx = canvas.getContext('2d');
    if (ctx) {
        ctx.scale(dpr, dpr);
    }
    
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
    const inputElement = document.getElementById('firma_input');
    
    if (signaturePad && !signaturePad.isEmpty()) {
        const firmaData = signaturePad.toDataURL('image/png');
        inputElement.value = firmaData;
    } else {
        inputElement.value = '';
    }
}

function limpiarFirma() {
    if (signaturePad) {
        signaturePad.clear();
    }
    document.getElementById('firma_input').value = '';
    actualizarEstadoFirma();
}

document.querySelector('form')?.addEventListener('submit', function(e) {
    if (signaturePad && !signaturePad.isEmpty()) {
        const firmaData = signaturePad.toDataURL('image/png');
        document.getElementById('firma_input').value = firmaData;
    }
    
    const firmaInput = document.getElementById('firma_input').value;
    if (!firmaInput || firmaInput.length < 50) {
        e.preventDefault();
        alert('⚠️ Debe dibujar la firma del receptor.');
        return false;
    }
});

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

document.addEventListener('DOMContentLoaded', function() {
    const inicioInput = document.getElementById('hora_inicio_atencion');
    const finInput = document.getElementById('hora_fin_atencion');
    
    if (inicioInput) inicioInput.addEventListener('change', calcularDuracion);
    if (finInput) finInput.addEventListener('change', calcularDuracion);
    
    setTimeout(calcularDuracion, 100);
});

// Dictado por voz (Web Speech API)
document.addEventListener('DOMContentLoaded', function() {
    const SpeechRecognitionApi = window.SpeechRecognition || window.webkitSpeechRecognition;
    const buttons = document.querySelectorAll('[data-dictation-target]');
    const isLocalHost = ['localhost', '127.0.0.1'].includes(window.location.hostname);
    const isSecureSpeechContext = window.isSecureContext || isLocalHost;
    const isMobile = /Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent || '');

    if (!buttons.length) {
        return;
    }

    function disableDictationButtons(message) {
        buttons.forEach((button) => {
            button.disabled = true;
            button.classList.add('opacity-50', 'cursor-not-allowed');
            const target = button.dataset.dictationTarget;
            const status = document.querySelector('[data-dictation-status-for="' + target + '"]');
            if (status) {
                status.textContent = message;
            }
        });
    }

    if (!SpeechRecognitionApi) {
        disableDictationButtons('Dictado no disponible en este navegador. Usa Chrome en Android o navegador compatible.');
        return;
    }

    if (!isSecureSpeechContext) {
        disableDictationButtons('En moviles el microfono por voz requiere HTTPS. Abre el sistema con dominio/HTTPS (no solo IP local).');
        return;
    }

    const recognition = new SpeechRecognitionApi();
    recognition.lang = 'es-CO';
    recognition.interimResults = true;
    recognition.continuous = !isMobile;
    recognition.maxAlternatives = 1;

    let activeTargetId = null;
    let activeButton = null;

    function setStatus(targetId, message) {
        const status = document.querySelector('[data-dictation-status-for="' + targetId + '"]');
        if (status) {
            status.textContent = message;
        }
    }

    function appendTextToTarget(targetId, text) {
        const target = document.getElementById(targetId);
        if (!target || !text.trim()) {
            return;
        }

        const current = target.value.trim();
        target.value = current ? (current + ' ' + text.trim()) : text.trim();
        target.dispatchEvent(new Event('input', { bubbles: true }));
    }

    recognition.onresult = function(event) {
        if (!activeTargetId) {
            return;
        }

        let finalTranscript = '';
        let interimTranscript = '';

        for (let i = event.resultIndex; i < event.results.length; i++) {
            const transcript = event.results[i][0].transcript;
            if (event.results[i].isFinal) {
                finalTranscript += transcript + ' ';
            } else {
                interimTranscript += transcript;
            }
        }

        if (finalTranscript) {
            appendTextToTarget(activeTargetId, finalTranscript);
            
            // NUEVO: Guardar texto original y mostrar botón de IA
            const target = document.getElementById(activeTargetId);
            const originalInput = document.getElementById(activeTargetId + '_original');
            if (originalInput) {
                originalInput.value = target.value;
            }
            
            // Mostrar botón de IA
            const btnIA = document.getElementById('btn-ia-' + activeTargetId);
            if (btnIA) {
                btnIA.classList.remove('hidden');
            }
        }

        setStatus(activeTargetId, interimTranscript ? ('Escuchando: ' + interimTranscript) : 'Escuchando...');
    };

    recognition.onerror = function(event) {
        if (activeTargetId) {
            const errorMap = {
                'not-allowed': 'Permiso de microfono denegado. Habilitalo en el navegador.',
                'service-not-allowed': 'Servicio de voz bloqueado por el navegador/dispositivo.',
                'audio-capture': 'No se detecta microfono disponible en este dispositivo.',
                'network': 'Error de red en reconocimiento de voz. Verifica conexion.',
                'no-speech': 'No se detecto voz. Intenta hablar mas cerca al microfono.',
                'aborted': 'Dictado interrumpido. Puedes iniciar nuevamente.'
            };
            setStatus(activeTargetId, errorMap[event?.error] || 'No se pudo reconocer audio. Intenta nuevamente.');
        }
    };

    recognition.onend = function() {
        if (activeButton) {
            activeButton.textContent = '🎤 Dictar';
            activeButton.classList.remove('bg-red-600', 'text-white', 'border-red-600');
        }
        if (activeTargetId) {
            setStatus(activeTargetId, 'Dictado detenido. Puedes volver a iniciar.');
        }
        activeButton = null;
        activeTargetId = null;
    };

    buttons.forEach((button) => {
        button.addEventListener('click', function() {
            const targetId = button.dataset.dictationTarget;

            if (!navigator.onLine) {
                setStatus(targetId, 'Sin conexion. El dictado por voz requiere internet.');
                return;
            }

            if (activeTargetId && activeTargetId === targetId) {
                recognition.stop();
                return;
            }

            if (activeTargetId && activeTargetId !== targetId) {
                recognition.stop();
            }

            activeTargetId = targetId;
            activeButton = button;
            button.textContent = '⏹️ Detener';
            button.classList.add('bg-red-600', 'text-white', 'border-red-600');
            setStatus(targetId, 'Escuchando... habla ahora.');

            try {
                recognition.start();
            } catch (e) {
                setStatus(targetId, 'No se pudo iniciar el micrófono.');
                button.textContent = '🎤 Dictar';
                button.classList.remove('bg-red-600', 'text-white', 'border-red-600');
                activeButton = null;
                activeTargetId = null;
            }
        });
    });
    
    // ===== PROCESAMIENTO CON IA (DEEPSEEK) =====
    
    /**
     * Procesa texto con IA para mejorar redacción y acentuaciones
     */
    function procesarConIA(targetId) {
        const textarea = document.getElementById(targetId);
        const btnIA = document.getElementById('btn-ia-' + targetId);
        const statusDiv = document.querySelector('[data-dictation-status-for="' + targetId + '"]');
        
        if (!textarea || !textarea.value.trim()) {
            if (statusDiv) statusDiv.textContent = '⚠️ No hay texto para procesar';
            return;
        }
        
        // Mostrar estado de procesamiento
        if (statusDiv) {
            statusDiv.textContent = '🤖 Procesando con IA... por favor espera';
            statusDiv.classList.add('text-blue-600', 'font-semibold');
        }
        if (btnIA) {
            btnIA.disabled = true;
            btnIA.classList.add('opacity-50', 'cursor-not-allowed');
        }
        
        // Llamar endpoint de backend
        fetch('{{ route("incidencias.servicios.procesar-voz") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                texto_capturado: textarea.value,
                campo: targetId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // ✅ Si IA está habilitada, mostrar modal de confirmación
                if (data.ia_enabled && data.require_confirmation) {
                    mostrarModalConfirmacion(targetId, data.texto_original, data.texto_procesado);
                } else if (!data.ia_enabled) {
                    // IA deshabilitada, mantener texto original
                    if (statusDiv) {
                        statusDiv.innerHTML = `
                            ℹ️ <strong>IA deshabilitada</strong><br>
                            <small>Usando captura de voz sin procesar</small>
                        `;
                        statusDiv.classList.add('text-gray-600', 'font-semibold');
                    }
                } else {
                    // Fallback: actualizar directamente
                    textarea.value = data.texto_procesado;
                    textarea.dispatchEvent(new Event('input', { bubbles: true }));
                    
                    if (statusDiv) {
                        statusDiv.innerHTML = `
                            ✅ <strong>Texto procesado</strong><br>
                            <small>Confirma que sea correcto</small>
                        `;
                        statusDiv.classList.add('text-green-600', 'font-semibold');
                    }
                }
            } else {
                // Si falla IA, mantener original
                if (statusDiv) {
                    statusDiv.innerHTML = `
                        ⚠️ <strong>IA no disponible</strong><br>
                        <small>Se mantiene el texto original capturado</small>
                    `;
                    statusDiv.classList.add('text-yellow-600', 'font-semibold');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (statusDiv) {
                statusDiv.innerHTML = `
                    ❌ <strong>Error al procesar</strong><br>
                    <small>El texto original se mantiene intacto</small>
                `;
                statusDiv.classList.add('text-red-600', 'font-semibold');
            }
        })
        .finally(() => {
            // Rehabilitar botón
            if (btnIA) {
                btnIA.disabled = false;
                btnIA.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }
    
    /**
     * Mostrar modal de confirmación: Comparar original vs procesado
     */
    function mostrarModalConfirmacion(targetId, textoOriginal, textoProcesado) {
        const modal = document.getElementById('modal-confirmacion-ia') || crearModalConfirmacion();
        
        document.getElementById('modal-ia-original').textContent = textoOriginal;
        document.getElementById('modal-ia-procesado').textContent = textoProcesado;
        
        // Botones de acción
        const btnConfirmar = document.getElementById('btn-confirmar-ia');
        const btnRechazar = document.getElementById('btn-rechazar-ia');
        
        btnConfirmar.onclick = () => {
            document.getElementById(targetId).value = textoProcesado;
            document.getElementById(targetId).dispatchEvent(new Event('input', { bubbles: true }));
            modal.classList.add('hidden');
            
            const statusDiv = document.querySelector('[data-dictation-status-for="' + targetId + '"]');
            if (statusDiv) {
                statusDiv.innerHTML = `✅ <strong>Texto confirmado</strong>`;
                statusDiv.classList.add('text-green-600', 'font-semibold');
            }
        };
        
        btnRechazar.onclick = () => {
            modal.classList.add('hidden');
        };
        
        modal.classList.remove('hidden');
    }
    
    /**
     * Crear modal de confirmación si no existe
     */
    function crearModalConfirmacion() {
        const template = document.createElement('div');
        template.id = 'modal-confirmacion-ia';
        template.className = 'fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4';
        template.innerHTML = `
            <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[80vh] overflow-y-auto">
                <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4 border-b">
                    <h3 class="text-lg font-bold">🔍 Revisar Cambios de IA</h3>
                    <p class="text-sm text-blue-100">Compara el texto original con la versión mejorada</p>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Original -->
                    <div class="bg-orange-50 border-2 border-orange-300 rounded-lg p-4">
                        <h4 class="font-bold text-orange-900 mb-2">📝 Texto Captado (Original)</h4>
                        <div id="modal-ia-original" class="bg-white p-3 rounded text-sm text-gray-800 max-h-64 overflow-y-auto whitespace-pre-wrap"></div>
                    </div>
                    
                    <!-- Procesado -->
                    <div class="bg-green-50 border-2 border-green-300 rounded-lg p-4">
                        <h4 class="font-bold text-green-900 mb-2">✨ Texto Procesado (IA)</h4>
                        <div id="modal-ia-procesado" class="bg-white p-3 rounded text-sm text-gray-800 max-h-64 overflow-y-auto whitespace-pre-wrap"></div>
                    </div>
                </div>
                
                <div class="border-t p-4 flex gap-3 justify-end bg-gray-50">
                    <button type="button" id="btn-rechazar-ia" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-semibold transition">
                        ❌ Rechazar
                    </button>
                    <button type="button" id="btn-confirmar-ia" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                        ✅ Confirmar
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(template);
        return template;
    }
    
    // Agregar listeners a botones de IA
    document.querySelectorAll('[data-ia-target]').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.iaTarget;
            procesarConIA(targetId);
        });
    });
});
</script>

<!-- MODAL REPUESTO -->
<div id="modal-repuesto" class="fixed inset-0 z-50 hidden items-center justify-center" style="display:none;">
    <div class="absolute inset-0 bg-black bg-opacity-40 backdrop-blur-sm" onclick="cerrarModalRepuesto()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl mx-4 max-h-[90vh] overflow-y-auto" style="animation: modalIn 0.2s ease-out;">
        
        <!-- Header -->
        <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-4 border-b rounded-t-2xl" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <h3 id="modal-repuesto-title" class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Agregar Repuesto
            </h3>
            <button type="button" onclick="cerrarModalRepuesto()" class="text-white hover:text-gray-200 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Form -->
        <form id="form-repuesto" class="p-6">
            @csrf
            
            <div id="repuesto-errors" class="hidden mb-4 p-3 rounded-lg text-sm" style="background-color: #fef2f2; border: 1px solid #fca5a5; color: #991b1b;"></div>

            <!-- Fila 1: Código + Cantidad -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Código</label>
                    <input type="text" id="repuesto-codigo" name="codigo" maxlength="100"
                        class="w-full border-0 border-b-2 border-gray-200 rounded-lg px-0 py-2.5 text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-0 transition"
                        style="background: transparent;" placeholder="Ej: CPU-001">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Cantidad *</label>
                    <input type="number" id="repuesto-cantidad" name="cantidad" min="1" value="1" required
                        class="w-full border-0 border-b-2 border-gray-200 rounded-lg px-0 py-2.5 text-gray-900 focus:border-amber-500 focus:ring-0 transition text-center font-bold text-lg"
                        style="background: transparent;">
                </div>
            </div>

            <!-- Fila 2: Descripción -->
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Descripción *</label>
                <input type="text" id="repuesto-descripcion" name="descripcion" maxlength="255" required
                    class="w-full border-0 border-b-2 border-gray-200 rounded-lg px-0 py-2.5 text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-0 transition"
                    style="background: transparent;" placeholder="Nombre del repuesto o componente">
            </div>

            <!-- Fila 3: Marca + Modelo -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Marca</label>
                    <select id="repuesto-marca_id" name="marca_id"
                        class="w-full border-0 border-b-2 border-gray-200 rounded-lg px-0 py-2.5 text-gray-900 focus:border-amber-500 focus:ring-0 transition"
                        style="background: transparent;">
                        <option value="">-- Seleccione --</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Modelo</label>
                    <input type="text" id="repuesto-modelo" name="modelo" maxlength="150"
                        class="w-full border-0 border-b-2 border-gray-200 rounded-lg px-0 py-2.5 text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-0 transition"
                        style="background: transparent;" placeholder="Modelo del repuesto">
                </div>
            </div>

            <!-- Fila 4: Serial -->
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Número de Serie</label>
                <input type="text" id="repuesto-serial" name="serial" maxlength="150"
                    class="w-full border-0 border-b-2 border-gray-200 rounded-lg px-0 py-2.5 text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-0 transition"
                    style="background: transparent;" placeholder="Serial del fabricante (si aplica)">
            </div>

            <!-- Fila 5: Facturable + N° Factura -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Facturación</label>
                    <label class="flex items-center gap-3 p-3 rounded-xl cursor-pointer transition hover:shadow-md"
                        style="background: linear-gradient(135deg, #fef3c7, #fde68a); border: 2px solid #f59e0b;" id="facturable-label">
                        <input type="checkbox" id="repuesto-facturable" name="facturable" value="1"
                            class="w-5 h-5 rounded cursor-pointer" style="accent-color: #d97706;">
                        <div>
                            <p class="font-bold text-gray-800 text-sm">💰 Facturable</p>
                            <p class="text-xs text-gray-600">Marcar si debe facturarse al cliente</p>
                        </div>
                    </label>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">N° Factura</label>
                    <input type="text" id="repuesto-numero_factura" name="numero_factura" maxlength="100"
                        class="w-full border-0 border-b-2 border-gray-200 rounded-lg px-0 py-2.5 text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-0 transition"
                        style="background: transparent;" placeholder="Número de factura">
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="submit" id="btn-guardar-repuesto"
                    class="flex-1 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg hover:shadow-xl flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="fas fa-save"></i> Guardar Repuesto
                </button>
                <button type="button" onclick="cerrarModalRepuesto()"
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-4 rounded-xl transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
#form-repuesto input:focus,
#form-repuesto select:focus {
    outline: none;
    box-shadow: none;
}
</style>

<script>
const servicioId = {{ $servicio->id }};
const urlStore = '{{ route("incidencias.servicios.repuestos.store", $servicio) }}';
const urlRepuestosBase = '{{ url("incidencias/servicios") }}/' + servicioId + '/repuestos';
const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

function abrirModalRepuesto(repuesto = null) {
    const title = document.getElementById('modal-repuesto-title');
    const form = document.getElementById('form-repuesto');
    const errorsDiv = document.getElementById('repuesto-errors');
    
    form.reset();
    errorsDiv.classList.add('hidden');
    errorsDiv.innerHTML = '';
    
    if (repuesto) {
        title.innerHTML = '<i class="fas fa-edit"></i> Editar Repuesto';
        document.getElementById('repuesto-codigo').value = repuesto.codigo || '';
        document.getElementById('repuesto-descripcion').value = repuesto.descripcion || '';
        document.getElementById('repuesto-marca_id').value = repuesto.marca_id || '';
        document.getElementById('repuesto-modelo').value = repuesto.modelo || '';
        document.getElementById('repuesto-serial').value = repuesto.serial || '';
        document.getElementById('repuesto-cantidad').value = repuesto.cantidad || 1;
        document.getElementById('repuesto-facturable').checked = !!repuesto.facturable;
        document.getElementById('repuesto-numero_factura').value = repuesto.numero_factura || '';
        form.dataset.editId = repuesto.id;
    } else {
        title.innerHTML = '<i class="fas fa-plus-circle"></i> Agregar Repuesto';
        delete form.dataset.editId;
    }
    
    const modal = document.getElementById('modal-repuesto');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.style.display = '';
}

function cerrarModalRepuesto() {
    const modal = document.getElementById('modal-repuesto');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function editarRepuesto(repuesto) {
    abrirModalRepuesto(repuesto);
}

function eliminarRepuesto(id) {
    if (!confirm('¿Está seguro de eliminar este repuesto permanentemente?')) return;
    
    fetch(urlRepuestosBase + '/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.querySelector(`tr[data-repuesto-id="${id}"]`);
            if (row) {
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    verificarVacio();
                }, 300);
            }
        }
    });
}

function verificarVacio() {
    const tbody = document.getElementById('repuestos-tbody');
    if (tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr id="repuestos-empty">
                <td colspan="9" class="px-3 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center gap-2">
                        <i class="fas fa-box-open text-3xl text-gray-300"></i>
                        <p>No hay repuestos registrados</p>
                        <p class="text-xs text-gray-400">Haga clic en "Agregar Repuesto" para añadir uno</p>
                    </div>
                </td>
            </tr>`;
    }
}

document.getElementById('form-repuesto').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const btn = document.getElementById('btn-guardar-repuesto');
    const errorsDiv = document.getElementById('repuesto-errors');
    
    // Bloquear botón
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    errorsDiv.classList.add('hidden');
    
    const editId = form.dataset.editId;
    const url = editId ? urlRepuestosBase + '/' + editId : urlStore;
    
    const formData = new FormData(form);
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(r => {
        if (!r.ok) {
            return r.json().then(err => { throw err; });
        }
        return r.json();
    })
    .then(data => {
        if (data.success) {
            cerrarModalRepuesto();
            const rep = data.repuesto;
            const isEdit = !!form.dataset.editId;
            
            if (isEdit) {
                // Actualizar fila existente
                const row = document.querySelector(`tr[data-repuesto-id="${rep.id}"]`);
                if (row) {
                    row.cells[0].textContent = rep.codigo || '-';
                    row.cells[1].textContent = rep.descripcion || '';
                    row.cells[2].textContent = rep.marca?.nombre || '-';
                    row.cells[3].textContent = rep.modelo || '-';
                    row.cells[4].textContent = rep.serial || '-';
                    row.cells[5].textContent = rep.cantidad || '';
                    row.cells[6].innerHTML = rep.facturable 
                        ? '<span style="background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:9999px;font-size:11px;">Sí</span>'
                        : '<span style="background:#f3f4f6;color:#374151;padding:2px 8px;border-radius:9999px;font-size:11px;">No</span>';
                    row.cells[7].textContent = rep.numero_factura || '-';
                    // Actualizar botones de acción
                    row.cells[8].innerHTML = `
                        <button type="button" onclick='editarRepuesto(${JSON.stringify(rep)})' class="text-amber-600 hover:text-amber-800 mx-1" title="Editar"><i class="fas fa-edit"></i></button>
                        <button type="button" onclick="eliminarRepuesto(${rep.id})" class="text-red-600 hover:text-red-800 mx-1" title="Eliminar"><i class="fas fa-trash"></i></button>`;
                }
            } else {
                // Agregar nueva fila
                const tbody = document.getElementById('repuestos-tbody');
                const empty = document.getElementById('repuestos-empty');
                if (empty) empty.remove();
                
                const tr = document.createElement('tr');
                tr.className = 'border-t hover:bg-gray-50';
                tr.dataset.repuestoId = rep.id;
                tr.style.opacity = '0';
                tr.innerHTML = `
                    <td class="px-3 py-2 font-semibold text-gray-900">${rep.codigo || '-'}</td>
                    <td class="px-3 py-2 text-gray-700">${rep.descripcion || ''}</td>
                    <td class="px-3 py-2 text-gray-700">${rep.marca?.nombre || '-'}</td>
                    <td class="px-3 py-2 text-gray-700">${rep.modelo || '-'}</td>
                    <td class="px-3 py-2 text-gray-700">${rep.serial || '-'}</td>
                    <td class="px-3 py-2 text-center font-semibold">${rep.cantidad || ''}</td>
                    <td class="px-3 py-2 text-center">${rep.facturable 
                        ? '<span style="background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:9999px;font-size:11px;">Sí</span>'
                        : '<span style="background:#f3f4f6;color:#374151;padding:2px 8px;border-radius:9999px;font-size:11px;">No</span>'}</td>
                    <td class="px-3 py-2 text-gray-700">${rep.numero_factura || '-'}</td>
                    <td class="px-3 py-2 text-center">
                        <button type="button" onclick='editarRepuesto(${JSON.stringify(rep)})' class="text-amber-600 hover:text-amber-800 mx-1" title="Editar"><i class="fas fa-edit"></i></button>
                        <button type="button" onclick="eliminarRepuesto(${rep.id})" class="text-red-600 hover:text-red-800 mx-1" title="Eliminar"><i class="fas fa-trash"></i></button>
                    </td>`;
                tbody.appendChild(tr);
                // Animación de entrada
                requestAnimationFrame(() => { tr.style.transition = 'opacity 0.3s'; tr.style.opacity = '1'; });
            }
            
            // Resetear botón
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Guardar Repuesto';
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Guardar Repuesto';
        
        if (err.errors) {
            let html = '<strong><i class="fas fa-exclamation-circle"></i> Corrija los siguientes errores:</strong><ul class="mt-2 ml-4 list-disc">';
            for (const [field, messages] of Object.entries(err.errors)) {
                html += `<li>${messages[0]}</li>`;
            }
            html += '</ul>';
            errorsDiv.innerHTML = html;
            errorsDiv.classList.remove('hidden');
        } else {
            errorsDiv.innerHTML = '<strong>Error:</strong> No se pudo guardar el repuesto. Intente nuevamente.';
            errorsDiv.classList.remove('hidden');
        }
    });
});
</script>

@endsection
