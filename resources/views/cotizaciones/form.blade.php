@extends('layouts.app')

@section('title', isset($cotizacion) ? 'Editar Cotización' : 'Nueva Cotización')
@section('page-title', isset($cotizacion) ? 'Editar Cotización' : 'Nueva Cotización')
@section('page-description', isset($cotizacion) ? $cotizacion->numero_cotizacion : 'Crear una nueva cotización de servicios')

@section('content')
<div class="max-w-4xl">
    <form action="{{ isset($cotizacion) ? route('cotizaciones.update', $cotizacion) : route('cotizaciones.store') }}" method="POST" id="formCotizacion">
        @csrf
        @if(isset($cotizacion))
            @method('PUT')
        @endif

        <div class="space-y-6">
            {{-- Información General --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Información General</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="numero_cotizacion" class="block text-sm font-semibold text-gray-700 mb-1">N° Cotización <span class="text-red-500">*</span></label>
                        <input type="text" name="numero_cotizacion" id="numero_cotizacion"
                            value="{{ old('numero_cotizacion', $cotizacion->numero_cotizacion ?? '') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        @error('numero_cotizacion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="cliente_id" class="block text-sm font-semibold text-gray-700 mb-1">Cliente <span class="text-red-500">*</span></label>
                        <select name="cliente_id" id="cliente_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Seleccione un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id', $cotizacion->cliente_id ?? '') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->razon_social }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="fecha_cotizacion" class="block text-sm font-semibold text-gray-700 mb-1">Fecha Cotización <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_cotizacion" id="fecha_cotizacion"
                            value="{{ old('fecha_cotizacion', $cotizacion->fecha_cotizacion?->format('Y-m-d') ?? date('Y-m-d')) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        @error('fecha_cotizacion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="fecha_vencimiento" class="block text-sm font-semibold text-gray-700 mb-1">Fecha Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                            value="{{ old('fecha_vencimiento', $cotizacion->fecha_vencimiento?->format('Y-m-d') ?? '') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('fecha_vencimiento')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="tipo_servicio" class="block text-sm font-semibold text-gray-700 mb-1">Tipo de Servicio <span class="text-red-500">*</span></label>
                        <select name="tipo_servicio" id="tipo_servicio" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            @foreach(\App\Models\Cotizacion::tiposServicio() as $key => $value)
                                <option value="{{ $key }}" {{ old('tipo_servicio', $cotizacion->tipo_servicio ?? 'SOPORTE_TI') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_servicio')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="estado" class="block text-sm font-semibold text-gray-700 mb-1">Estado <span class="text-red-500">*</span></label>
                        <select name="estado" id="estado" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            @foreach(\App\Models\Cotizacion::estados() as $key => $value)
                                <option value="{{ $key }}" {{ old('estado', $cotizacion->estado ?? 'BORRADOR') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Valores --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Valores</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="valor_subtotal" class="block text-sm font-semibold text-gray-700 mb-1">Subtotal <span class="text-red-500">*</span></label>
                        <input type="number" name="valor_subtotal" id="valor_subtotal" step="0.01" min="0"
                            value="{{ old('valor_subtotal', $cotizacion->valor_subtotal ?? 0) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required oninput="calcularTotal()">
                        @error('valor_subtotal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="valor_iva" class="block text-sm font-semibold text-gray-700 mb-1">IVA</label>
                        <input type="number" name="valor_iva" id="valor_iva" step="0.01" min="0"
                            value="{{ old('valor_iva', $cotizacion->valor_iva ?? 0) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            oninput="calcularTotal()">
                        @error('valor_iva')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="moneda" class="block text-sm font-semibold text-gray-700 mb-1">Moneda <span class="text-red-500">*</span></label>
                        <select name="moneda" id="moneda" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            @foreach(\App\Models\Cotizacion::monedas() as $key => $value)
                                <option value="{{ $key }}" {{ old('moneda', $cotizacion->moneda ?? 'COP') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('moneda')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm font-semibold text-gray-700">Valor Total: <span id="valorTotalDisplay" class="text-lg text-blue-600">$ 0</span></p>
                </div>
            </div>

            {{-- Descripción --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-4 mb-4">Descripción del Servicio</h3>
                <div class="space-y-4">
                    <div>
                        <label for="descripcion_servicio" class="block text-sm font-semibold text-gray-700 mb-1">Descripción <span class="text-red-500">*</span></label>
                        <textarea name="descripcion_servicio" id="descripcion_servicio" rows="4"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>{{ old('descripcion_servicio', $cotizacion->descripcion_servicio ?? '') }}</textarea>
                        @error('descripcion_servicio')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-1">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('observaciones', $cotizacion->observaciones ?? '') }}</textarea>
                        @error('observaciones')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('cotizaciones.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    <i class="fas fa-save"></i> {{ isset($cotizacion) ? 'Actualizar' : 'Guardar' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function calcularTotal() {
    const subtotal = parseFloat(document.getElementById('valor_subtotal').value) || 0;
    const iva = parseFloat(document.getElementById('valor_iva').value) || 0;
    const total = subtotal + iva;
    document.getElementById('valorTotalDisplay').textContent = '$ ' + total.toLocaleString('es-CO');
}

document.addEventListener('DOMContentLoaded', function() {
    calcularTotal();
});
</script>
@endsection
