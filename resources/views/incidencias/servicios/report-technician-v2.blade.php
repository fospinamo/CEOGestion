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
</script>

@endsection
