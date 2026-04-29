@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">📋 Informe Técnico</h1>
            <p class="text-gray-600">Completa la información del servicio atendido</p>
        </div>

        <!-- Información del Servicio -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8 rounded">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">ID Servicio</p>
                    <p class="text-lg font-semibold">#{{ $servicio->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Equipo</p>
                    <p class="text-lg font-semibold">{{ $servicio->equipo->codigo_interno }} - {{ $servicio->equipo->marca }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ubicación</p>
                    <p class="text-lg font-semibold">{{ $servicio->equipo->area->nombre }}, {{ $servicio->equipo->area->sede->nombre }}</p>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('servicios.store-report', $servicio) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-8">
            @csrf

            <div class="space-y-8">
                <!-- SECCIÓN 1: Descripción de la Atención -->
                <div class="border-b pb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">✅ Descripción de la Atención</h2>
                    
                    <label for="descripcion_atencion" class="block text-sm font-semibold text-gray-700 mb-2">
                        ¿Qué se realizó? *
                    </label>
                    <textarea name="descripcion_atencion" id="descripcion_atencion" rows="5"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Describe detalladamente el trabajo realizado, diagnóstico, soluciones aplicadas, repuestos utilizados, etc."
                        required>{{ old('descripcion_atencion', $servicio->descripcion_atencion) }}</textarea>
                    @error('descripcion_atencion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SECCIÓN 2: Observaciones -->
                <div class="border-b pb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">📝 Observaciones Adicionales</h2>
                    
                    <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-2">
                        Observaciones (Opcional)
                    </label>
                    <textarea name="observaciones" id="observaciones" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Notas, comentarios, recomendaciones..."
                        >{{ old('observaciones', $servicio->observaciones) }}</textarea>
                </div>

                <!-- SECCIÓN 3: Estado del Servicio -->
                <div class="border-b pb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">🔄 Estado del Servicio</h2>
                    
                    <label for="estado_servicio_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Estado Actual *
                    </label>
                    <select name="estado_servicio_id" id="estado_servicio_id" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="">-- Seleccione un estado --</option>
                        @foreach($estadosDisponibles as $estado)
                            <option value="{{ $estado->id }}" 
                                @if($servicio->estado_servicio_id === $estado->id) selected @endif
                                style="background-color: {{ $estado->color }}; color: white;">
                                {{ $estado->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado_servicio_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SECCIÓN 4: Equipos Adicionales -->
                @if($equiposAdicionales->count() > 0)
                <div class="border-b pb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">🔧 Equipos Adicionales Atendidos</h2>
                    
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Selecciona otros equipos que fueron atendidos:
                    </label>
                    
                    <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-4">
                        @foreach($equiposAdicionales as $equipo)
                            <label class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                                <input type="checkbox" name="equipos_adicionales_atendidos[]" 
                                    value="{{ $equipo->id }}"
                                    class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                                    @if(in_array($equipo->id, old('equipos_adicionales_atendidos', $servicio->equipos_adicionales_atendidos ?? [])))
                                        checked
                                    @endif>
                                <span class="text-sm text-gray-700">
                                    <strong>{{ $equipo->codigo_interno }}</strong> - {{ $equipo->marca }} {{ $equipo->modelo }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- SECCIÓN 5: Imágenes del Servicio -->
                <div class="border-b pb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">📸 Imágenes del Servicio</h2>
                    
                    <label for="imagenes" class="block text-sm font-semibold text-gray-700 mb-2">
                        Carga fotos del trabajo realizado (máximo 10 imágenes, 5MB cada una)
                    </label>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:bg-gray-50"
                        onclick="document.getElementById('imagenes').click()">
                        <input type="file" id="imagenes" name="imagenes[]" multiple accept="image/*" 
                            class="hidden" onchange="mostrarPreviewImagenes(this)">
                        <p class="text-gray-600">
                            📷 Haz clic aquí o arrastra imágenes
                        </p>
                    </div>
                    
                    <div id="imagenes-preview" class="grid grid-cols-3 md:grid-cols-4 gap-4 mt-4"></div>
                    @error('imagenes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SECCIÓN 6: Información del Receptor -->
                <div class="border-b pb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">👤 Persona que Recibe el Servicio</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="persona_receptora_nombre" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nombre *
                            </label>
                            <input type="text" name="persona_receptora_nombre" id="persona_receptora_nombre"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('persona_receptora_nombre', $servicio->persona_receptora_nombre) }}"
                                required>
                            @error('persona_receptora_nombre')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="persona_receptora_apellido" class="block text-sm font-semibold text-gray-700 mb-2">
                                Apellido *
                            </label>
                            <input type="text" name="persona_receptora_apellido" id="persona_receptora_apellido"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('persona_receptora_apellido', $servicio->persona_receptora_apellido) }}"
                                required>
                            @error('persona_receptora_apellido')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="persona_receptora_documento" class="block text-sm font-semibold text-gray-700 mb-2">
                                Documento/Cédula *
                            </label>
                            <input type="text" name="persona_receptora_documento" id="persona_receptora_documento"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Ej: 12345678"
                                value="{{ old('persona_receptora_documento', $servicio->persona_receptora_documento) }}"
                                required>
                            @error('persona_receptora_documento')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 7: Captura de Firma -->
                <div class="border-b pb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">✍️ Firma del Receptor</h2>
                    
                    <p class="text-sm text-gray-600 mb-4">
                        Dibuja o captura la firma de la persona que recibe el servicio:
                    </p>
                    
                    <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                        <canvas id="firma-canvas" 
                            style="border: 1px solid #ddd; cursor: crosshair; display: block; background: white; margin-bottom: 10px;"
                            width="400" height="150"></canvas>
                        <input type="hidden" name="firma_persona_receptora" id="firma_input">
                        
                        <div class="flex gap-2">
                            <button type="button" onclick="limpiarFirma()" 
                                class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                                🔄 Limpiar
                            </button>
                            <button type="button" onclick="capturarFirmaDesdeWebcam()" 
                                class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                                📱 Webcam
                            </button>
                        </div>
                    </div>
                    
                    @error('firma_persona_receptora')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SECCIÓN 8: Facturación y Soporte -->
                <div class="border-b pb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">💰 Facturación y Soporte</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50">
                            <input type="checkbox" name="puede_facturarse" value="1"
                                class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                @if(old('puede_facturarse', $servicio->puede_facturarse))
                                    checked
                                @endif>
                            <div>
                                <p class="font-semibold text-gray-700">✅ Puede Facturarse</p>
                                <p class="text-xs text-gray-600">Este servicio puede ser facturado al cliente</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50">
                            <input type="checkbox" name="es_soporte_contrato" value="1"
                                class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                @if(old('es_soporte_contrato', $servicio->es_soporte_contrato))
                                    checked
                                @endif>
                            <div>
                                <p class="font-semibold text-gray-700">🆓 Soporte Incluido en Contrato</p>
                                <p class="text-xs text-gray-600">Este servicio es parte del soporte incluido en el contrato</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('servicios.show', $servicio) }}" 
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition text-center">
                        ❌ Cancelar
                    </a>
                    <button type="submit" 
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        ✅ Registrar Informe
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- LibreSignature.js para firma digital -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>
// Variables globales
let signaturePad;
let canvas;
let mediaStream;

// Inicializar canvas y SignaturePad
document.addEventListener('DOMContentLoaded', function() {
    canvas = document.getElementById('firma-canvas');
    const rect = canvas.getBoundingClientRect();
    
    signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)',
        dotSize: 2,
        minWidth: 0.5,
        maxWidth: 2.5,
        throttle: 16,
        minDistance: 5,
        onEnd: guardarFirma
    });
    
    // Ajustar tamaño del canvas en dispositivos móviles
    ajustarCanvasTamano();
    window.addEventListener('resize', ajustarCanvasTamano);
});

function ajustarCanvasTamano() {
    const rect = canvas.getBoundingClientRect();
    const dpr = window.devicePixelRatio || 1;
    
    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;
    
    const ctx = canvas.getContext('2d');
    ctx.scale(dpr, dpr);
    ctx.fillStyle = 'white';
    ctx.fillRect(0, 0, rect.width, rect.height);
}

function limpiarFirma() {
    signaturePad.clear();
    document.getElementById('firma_input').value = '';
}

function guardarFirma() {
    if (!signaturePad.isEmpty()) {
        const dataUrl = signaturePad.toDataURL('image/png');
        document.getElementById('firma_input').value = dataUrl;
    }
}

async function capturarFirmaDesdeWebcam() {
    try {
        mediaStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user' },
            audio: false
        });
        
        alert('Webcam iniciada. Presiona OK para capturar tu firma.');
        
        const video = document.createElement('video');
        video.srcObject = mediaStream;
        video.play();
        
        setTimeout(() => {
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            mediaStream.getTracks().forEach(track => track.stop());
            guardarFirma();
        }, 2000);
    } catch (err) {
        alert('No se pudo acceder a la cámara: ' + err.message);
    }
}

function mostrarPreviewImagenes(input) {
    const preview = document.getElementById('imagenes-preview');
    preview.innerHTML = '';
    
    const files = Array.from(input.files);
    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" alt="Imagen ${index + 1}" 
                    class="w-full h-24 object-cover rounded-lg border border-gray-300">
                <button type="button" onclick="eliminarImagen(this, ${index})" 
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                    ×
                </button>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function eliminarImagen(btn, index) {
    // Este es un ejemplo simple - en producción necesitarías mantener un array separado
    btn.closest('div').remove();
}
</script>

<style>
#firma-canvas {
    width: 100%;
    height: 150px;
    touch-action: none;
}
</style>
@endsection
