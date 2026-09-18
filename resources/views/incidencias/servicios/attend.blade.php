@extends('layouts.app')

@section('title', 'Atender Servicio #' . $servicio->id)
@section('page-title', 'Atender Servicio')
@section('page-description', 'Capturar firma y cerrar servicio')

@section('content')
<div class="max-w-6xl">
    <form action="{{ route('incidencias.servicios.store-report', $servicio) }}" method="POST" class="space-y-6">
        @csrf

        <!-- Información del Servicio -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Información del Servicio</h3>
            
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-600">Equipo Principal</p>
                    <p class="font-semibold text-gray-900">{{ $servicio->equipo->codigo_activo_cliente }} - {{ $servicio->equipo->modelo }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Ubicación (Área)</p>
                    <p class="font-semibold text-gray-900">{{ $servicio->equipo->area->nombre }} - {{ $servicio->equipo->area->sede->nombre }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Tipo de Servicio</p>
                    <p class="font-semibold text-gray-900">{{ $servicio->tipo_servicio }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Problema Reportado</p>
                    <p class="font-semibold text-gray-900">{{ Str::limit($servicio->descripcion_problema, 50) }}</p>
                </div>
            </div>
        </div>

        <!-- Equipos Adicionales -->
        @if($equiposAdicionalesDisponibles->count() > 0)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-blue-900 mb-4">
                    <i class="fas fa-laptop-code"></i> Equipos Adicionales en la Ubicación
                </h3>
                <p class="text-sm text-blue-700 mb-4">Selecciona otros equipos que también fueron atendidos en esta visita:</p>
                
                <div class="space-y-2">
                    @foreach($equiposAdicionalesDisponibles as $equipo)
                        <label class="flex items-center p-3 border border-blue-200 rounded-lg hover:bg-blue-100 cursor-pointer transition">
                            <input type="checkbox" name="equipos_adicionales[]" value="{{ $equipo->id }}" 
                                class="w-4 h-4 text-blue-600 rounded">
                            <div class="ml-3 flex-1">
                                <p class="font-semibold text-gray-900">{{ $equipo->codigo_activo_cliente }}</p>
                                <p class="text-xs text-gray-600">{{ $equipo->marca?->nombre ?? '' }} {{ $equipo->modelo }} - {{ $equipo->area->nombre }}</p>
                            </div>
                            <span class="text-xs font-semibold px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                {{ $equipo->estado_operativo }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Descripción de lo Realizado -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-pencil-alt"></i> Descripción de lo Realizado
            </h3>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Describe las acciones realizadas <span class="text-red-600">*</span>
                </label>
                <textarea name="descripcion_atencion" rows="5" 
                    placeholder="Detalla qué se realizó, diagnóstico, soluciones aplicadas, repuestos utilizados, etc."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('descripcion_atencion') border-red-500 @enderror"
                    required>{{ old('descripcion_atencion') }}</textarea>
                @error('descripcion_atencion')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Mínimo 20 caracteres</p>
            </div>
        </div>

        <!-- Complementos / Repuestos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-cogs"></i> Complementos / Repuestos
                <span class="text-sm font-normal text-gray-500 ml-2">(Opcional)</span>
            </h3>
            <p class="text-sm text-gray-600 mb-4">Registra los repuestos o complementos utilizados en el servicio.</p>

            <div id="repuestos-container">
                <div class="repuesto-row grid grid-cols-6 gap-3 mb-3 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Código</label>
                        <input type="text" name="repuestos_codigo[]" placeholder="Cód."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Descripción</label>
                        <input type="text" name="repuestos_descripcion[]" placeholder="Nombre del repuesto"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Marca</label>
                        <input type="text" name="repuestos_marca[]" placeholder="Marca"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Modelo</label>
                        <input type="text" name="repuestos_modelo[]" placeholder="Modelo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Serie</label>
                        <input type="text" name="repuestos_serie[]" placeholder="Serie"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Cant.</label>
                            <input type="number" name="repuestos_cantidad[]" value="1" min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="button" onclick="eliminarRepuesto(this)"
                            class="mt-5 px-2 py-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="Eliminar repuesto">
                            <i class="fas fa-trash-alt text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="button" onclick="agregarRepuesto()"
                class="mt-2 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold transition">
                <i class="fas fa-plus"></i> Agregar repuesto
            </button>
        </div>

        <!-- Datos del Receptor -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-user-check"></i> Datos de la Persona Receptora
            </h3>
            
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nombre <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="persona_receptora_nombre" 
                        value="{{ old('persona_receptora_nombre') }}"
                        placeholder="Juan"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('persona_receptora_nombre') border-red-500 @enderror"
                        required>
                    @error('persona_receptora_nombre')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Apellido <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="persona_receptora_apellido" 
                        value="{{ old('persona_receptora_apellido') }}"
                        placeholder="Pérez"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('persona_receptora_apellido') border-red-500 @enderror"
                        required>
                    @error('persona_receptora_apellido')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Documento de Identidad <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="persona_receptora_documento" 
                        value="{{ old('persona_receptora_documento') }}"
                        placeholder="1234567890"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('persona_receptora_documento') border-red-500 @enderror"
                        required>
                    @error('persona_receptora_documento')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Firma Digital -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-signature"></i> Firma del Receptor
            </h3>
            
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-4">Firma con el dedo o mouse en el área de abajo:</p>
                
                <canvas id="signatureCanvas" 
                    style="border: 2px solid #e5e7eb; border-radius: 0.375rem; cursor: crosshair; display: block; background-color: white;"
                    width="600" height="200"></canvas>
                
                <div class="flex gap-2 mt-4">
                    <button type="button" id="clearSignature" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold">
                        <i class="fas fa-eraser"></i> Limpiar
                    </button>
                    <button type="button" id="undoSignature" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold">
                        <i class="fas fa-undo"></i> Deshacer
                    </button>
                </div>
            </div>
            
            <!-- Hidden input para la firma base64 -->
            <input type="hidden" id="signatureInput" name="firma_persona_receptora" value="">
            
            @error('firma_persona_receptora')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('incidencias.servicios.show', $servicio) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-semibold">
                <i class="fas fa-times"></i> Cancelar
            </a>
            @can('servicios.editar')
            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-semibold">
                <i class="fas fa-check"></i> Cerrar Servicio
            </button>
            @endcan
        </div>
    </form>
</div>

<!-- Cargar Signature Pad desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>
function agregarRepuesto() {
    const container = document.getElementById('repuestos-container');
    const row = document.createElement('div');
    row.className = 'repuesto-row grid grid-cols-6 gap-3 mb-3 items-end';
    row.innerHTML = `
        <div>
            <input type="text" name="repuestos_codigo[]" placeholder="Cód."
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <input type="text" name="repuestos_descripcion[]" placeholder="Nombre del repuesto"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <input type="text" name="repuestos_marca[]" placeholder="Marca"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <input type="text" name="repuestos_modelo[]" placeholder="Modelo"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <input type="text" name="repuestos_serie[]" placeholder="Serie"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex gap-2">
            <div class="flex-1">
                <input type="number" name="repuestos_cantidad[]" value="1" min="1"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="button" onclick="eliminarRepuesto(this)"
                class="px-2 py-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="Eliminar repuesto">
                <i class="fas fa-trash-alt text-sm"></i>
            </button>
        </div>
    `;
    container.appendChild(row);
}

function eliminarRepuesto(btn) {
    const container = document.getElementById('repuestos-container');
    if (container.children.length > 1) {
        btn.closest('.repuesto-row').remove();
    } else {
        alert('Debe haber al menos una fila si va a registrar repuestos. Si no desea registrar ninguno, deje los campos vacíos.');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('signatureCanvas');
    const signatureInput = document.getElementById('signatureInput');
    const clearBtn = document.getElementById('clearSignature');
    const undoBtn = document.getElementById('undoSignature');
    const form = document.querySelector('form');
    
    // Inicializar Signature Pad
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)',
        minWidth: 1,
        maxWidth: 2.5,
        throttle: 16,
        minDistance: 5
    });

    // Botón Limpiar
    clearBtn.addEventListener('click', function(e) {
        e.preventDefault();
        signaturePad.clear();
    });

    // Botón Deshacer
    undoBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const data = signaturePad.toData();
        if (data.length > 0) {
            data.pop();
            signaturePad.fromData(data);
        }
    });

    // Antes de enviar el formulario, guardar la firma
    form.addEventListener('submit', function(e) {
        if (signaturePad.isEmpty()) {
            e.preventDefault();
            alert('Por favor captura tu firma antes de enviar');
            return false;
        }

        // Convertir canvas a base64
        const signatureImage = canvas.toDataURL('image/png');
        signatureInput.value = signatureImage;
    });

    // Responsive canvas
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
        signaturePad.clear(); // Limpiar después de redimensionar
    }

    // Redimensionar en caso necesario
    if (window.devicePixelRatio) {
        const ratio = window.devicePixelRatio;
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
    }
});
</script>

<style>
#signatureCanvas {
    touch-action: none;
}

@media (max-width: 768px) {
    #signatureCanvas {
        width: 100% !important;
        height: 150px;
    }
}
</style>
@endsection
