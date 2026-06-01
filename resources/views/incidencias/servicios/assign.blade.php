@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Asignar Técnico</h1>
            <p class="text-gray-600">Asigna un técnico responsable para este servicio</p>
        </div>

        <!-- Información del Servicio -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">ID Servicio</p>
                    <p class="text-lg font-semibold">#{{ $servicio->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Equipo</p>
                    <p class="text-lg font-semibold">{{ $servicio->equipo->codigo_interno }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Cliente</p>
                    <p class="text-lg font-semibold">{{ $servicio->equipo->area->sede->cliente->razon_social }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tipo de Servicio</p>
                    <p class="text-lg font-semibold">{{ $servicio->tipo_servicio }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600">Problema</p>
                    <p class="text-lg font-semibold">{{ substr($servicio->descripcion_problema, 0, 100) }}...</p>
                </div>
            </div>
        </div>

        <!-- Formulario de Asignación -->
        <form action="{{ route('incidencias.servicios.store-assign', $servicio) }}" method="POST" class="bg-white shadow-lg rounded-lg p-8">
            @csrf

            <div class="space-y-6">
                <!-- Seleccionar Técnico -->
                <div>
                    <label for="tecnico_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        👨‍💼 Técnico Responsable *
                    </label>
                    <select name="tecnico_id" id="tecnico_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Seleccione un técnico --</option>
                        @foreach($tecnicos as $tecnico)
                            <option value="{{ $tecnico->id }}" 
                                @if($servicio->tecnico_id === $tecnico->id) selected @endif>
                                {{ $tecnico->name }} 
                                @if($tecnico->telefono) ({{ $tecnico->telefono }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('tecnico_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha y hora de Asignación -->
                <div>
                    <label for="fecha_asignacion" class="block text-sm font-semibold text-gray-700 mb-2">
                        📅 Fecha y Hora de Asignación *
                    </label>
                    <input type="datetime-local" name="fecha_asignacion" id="fecha_asignacion" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('fecha_asignacion', $servicio->fecha_asignacion?->format('Y-m-d\\TH:i') ?? now()->format('Y-m-d\\TH:i')) }}"
                        required>
                    @error('fecha_asignacion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Observaciones Opcionales -->
                <div>
                    <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-2">
                        📝 Observaciones (Opcional)
                    </label>
                    <textarea name="observaciones" id="observaciones" rows="3" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Notas adicionales para el técnico..."></textarea>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-green-900">
                        <input type="checkbox" name="enviar_whatsapp" value="1" class="rounded border-green-400 text-green-600" {{ old('enviar_whatsapp', '1') ? 'checked' : '' }}>
                        Enviar notificación por WhatsApp al técnico después de asignar
                    </label>
                    <p class="text-xs text-green-800 mt-2">
                        Se abrirá WhatsApp Web/App con el mensaje prellenado usando el teléfono registrado del técnico.
                    </p>
                </div>

                <!-- Botones de Acción -->
                <div class="flex gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('incidencias.servicios.show', $servicio) }}" 
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition text-center">
                        Cancelar
                    </a>
                    <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        ✅ Asignar Técnico
                    </button>
                </div>
            </div>
        </form>

        <!-- Información adicional -->
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-yellow-800">
                <strong>💡 Nota:</strong> Una vez asignado, el técnico recibirá una notificación y podrá 
                acceder al formulario de informe técnico para completar el servicio.
            </p>
        </div>
    </div>
</div>
@endsection
