@extends('layouts.app')

@section('title', 'Registrar Servicio')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">📋 Registrar Nuevo Servicio</h1>
        <a href="{{ route('servicios.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            ← Volver
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form id="form-servicio" method="POST" action="{{ route('servicios.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- SECCIÓN 1: Cliente y Equipo -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">👤 Cliente y Equipo</h3>
                </div>

                <!-- Cliente -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Cliente *</label>
                    <select name="cliente_id" id="cliente_id" class="w-full border rounded px-3 py-2 @error('cliente_id') border-red-500 @enderror" required>
                        <option value="">Seleccione un cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->razon_social }} ({{ $cliente->documento }})
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Equipo -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Equipo *</label>
                    <select name="equipo_id" id="equipo_id" class="w-full border rounded px-3 py-2 @error('equipo_id') border-red-500 @enderror" required disabled>
                        <option value="">Primero seleccione un cliente</option>
                    </select>
                    @error('equipo_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SECCIÓN 2: Tipo y Prioridad -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">⚙️ Tipo de Servicio</h3>
                </div>

                <!-- Tipo de Servicio -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Tipo de Servicio *</label>
                    <select name="tipo_servicio" id="tipo_servicio" class="w-full border rounded px-3 py-2 @error('tipo_servicio') border-red-500 @enderror" required disabled>
                        <option value="">Primero seleccione un cliente</option>
                    </select>
                    @error('tipo_servicio')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prioridad -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Prioridad *</label>
                    <select name="prioridad" id="prioridad" class="w-full border rounded px-3 py-2 @error('prioridad') border-red-500 @enderror" required>
                        <option value="BAJA">BAJA 🟢</option>
                        <option value="MEDIA" selected>MEDIA 🟡</option>
                        <option value="ALTA">ALTA 🟠</option>
                        <option value="CRITICA">CRITICA 🔴</option>
                    </select>
                    @error('prioridad')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SECCIÓN 3: Información de Contacto -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">📞 Información de Contacto</h3>
                </div>

                <!-- Reportado por -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Reportado por *</label>
                    <input type="text" name="reportado_por" id="reportado_por" value="{{ old('reportado_por') }}" class="w-full border rounded px-3 py-2 @error('reportado_por') border-red-500 @enderror" required>
                    @error('reportado_por')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono contacto -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Teléfono contacto *</label>
                    <input type="text" name="telefono_contacto" id="telefono_contacto" value="{{ old('telefono_contacto') }}" class="w-full border rounded px-3 py-2 @error('telefono_contacto') border-red-500 @enderror" required>
                    @error('telefono_contacto')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email contacto -->
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Email contacto</label>
                    <input type="email" name="email_contacto" id="email_contacto" value="{{ old('email_contacto') }}" class="w-full border rounded px-3 py-2">
                </div>

                <!-- SECCIÓN 4: Información del Contrato -->
                <div class="md:col-span-2 mt-4" id="contrato_info"></div>

                <!-- SECCIÓN 5: Descripción del Problema -->
                <div class="md:col-span-2 mt-4">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">🔍 Descripción del Problema</h3>
                </div>

                <div class="md:col-span-2 mb-4">
                    <label class="block text-sm font-bold mb-2">Descripción detallada *</label>
                    <textarea name="descripcion_problema" id="descripcion_problema" rows="4" class="w-full border rounded px-3 py-2 @error('descripcion_problema') border-red-500 @enderror" required>{{ old('descripcion_problema') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Describa con detalle el problema reportado por el cliente</p>
                    @error('descripcion_problema')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Campos ocultos para datos del contrato -->
            <input type="hidden" name="contrato_id" id="contrato_id" value="">
            <input type="hidden" name="sla_respuesta" id="sla_respuesta" value="">
            <input type="hidden" name="sla_solucion" id="sla_solucion" value="">

            <!-- Botones -->
            <div class="flex justify-end space-x-2 mt-6 pt-4 border-t">
                <button type="reset" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition">
                    🔄 Limpiar
                </button>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition font-bold">
                    ✅ Registrar Servicio
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/servicios.js') }}"></script>
@endsection
