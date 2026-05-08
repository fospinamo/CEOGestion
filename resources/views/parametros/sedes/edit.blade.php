@extends('layouts.app')

@section('title', 'Editar Sede')
@section('page-title', 'Editar Sede')
@section('page-description', 'Actualiza la información de la sede')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('parametros.sedes.update', $sede) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Row 1: Propietario (Empresa o Cliente) -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-700 font-semibold mb-4">
                    <i class="fas fa-info-circle"></i> Propietario: empresa O cliente (pero no ambos)
                </p>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Empresa <span class="text-red-600">*</span>
                        </label>
                        <select name="empresa_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('empresa_id') border-red-500 @enderror">
                            <option value="">Selecciona una empresa (deja vacío si es sede de cliente)</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}" {{ old('empresa_id', $sede->empresa_id) == $empresa->id ? 'selected' : '' }}>
                                    {{ $empresa->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('empresa_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Cliente <span class="text-red-600">*</span>
                        </label>
                        <select name="cliente_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cliente_id') border-red-500 @enderror">
                            <option value="">Selecciona un cliente (deja vacío si es sede de empresa)</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id', $sede->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->razon_social }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @error('propietario')
                    <p class="text-red-600 text-sm mt-3 font-semibold"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Row 2: Nombre -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nombre de la Sede <span class="text-red-600">*</span>
                </label>
                <input type="text" name="nombre" value="{{ old('nombre', $sede->nombre) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nombre') border-red-500 @enderror">
                @error('nombre')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Row 2: Código -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Código <span class="text-red-600">*</span>
                </label>
                <input type="text" name="codigo" value="{{ old('codigo', $sede->codigo) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('codigo') border-red-500 @enderror">
                @error('codigo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Row 3: Dirección -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Dirección</label>
                <textarea name="direccion" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('direccion', $sede->direccion) }}</textarea>
            </div>

            <!-- Row 4: Ubicación DANE -->
            <div class="grid grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Departamento <span class="text-red-600">*</span>
                    </label>
                    <select name="departamento_id" id="departamentoSelect" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('departamento_id') border-red-500 @enderror">
                        <option value="">Selecciona departamento</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep->id }}" {{ $sede->municipio && $sede->municipio->departamento_id == $dep->id ? 'selected' : '' }}>
                                {{ $dep->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Municipio <span class="text-red-600">*</span>
                    </label>
                    <select name="municipio_id" id="municipioSelect" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('municipio_id') border-red-500 @enderror">
                        <option value="">Selecciona municipio</option>
                        @foreach($municipios as $municipio)
                            <option value="{{ $municipio->id }}" {{ old('municipio_id', $sede->municipio_id) == $municipio->id ? 'selected' : '' }}>
                                {{ $municipio->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('municipio_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Barrio</label>
                    <select name="barrio_id" id="barrioSelect"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecciona un barrio (opcional)</option>
                    </select>
                </div>
            </div>

            <!-- Row 5: Código Postal y Contacto -->
            <div class="grid grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Código Postal</label>
                    <input type="text" name="codigo_postal" value="{{ old('codigo_postal', $sede->codigo_postal) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono</label>
                    <input type="tel" name="telefono" value="{{ old('telefono', $sede->telefono) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $sede->email) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- State Toggle -->
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="estado" value="1" {{ old('estado', $sede->estado) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <span class="text-sm font-semibold text-gray-700">Sede Activa</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('parametros.sedes.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Actualizar Sede
                </button>
            </div>
        </form>
    </div>
</div>

<script>
console.log('🔍 Inicializando cascada de sedes (edit)...');

// 🎯 Usar window.Laravel.baseUrl inyectado desde layouts/app.blade.php
console.log('✅ API Base disponible desde window.Laravel.baseUrl:', window.Laravel.baseUrl);

// ⚠️ CASCADA: Departamento → Municipio
document.getElementById('departamentoSelect').addEventListener('change', async function() {
    const departamento_id = this.value;
    const municipioSelect = document.getElementById('municipioSelect');
    const barrioSelect = document.getElementById('barrioSelect');
    
    console.log('📍 Departamento seleccionado:', departamento_id);
    
    if (!departamento_id) {
        municipioSelect.innerHTML = '<option value="">Selecciona municipio</option>';
        barrioSelect.innerHTML = '<option value="">Selecciona un barrio (opcional)</option>';
        return;
    }
    
    try {
        // URL ABSOLUTA usando window.Laravel.baseUrl (inyectado en layouts/app.blade.php)
        const apiUrl = `${window.Laravel.baseUrl}/api/municipios-por-departamento?departamento_id=${departamento_id}`;
        
        console.log('🌐 URL del API (absoluta):', apiUrl);
        
        const response = await fetch(apiUrl);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const municipios = await response.json();
        console.log('✅ Municipios recibidos:', municipios);
        
        const selectedMunicipio = '{{ old('municipio_id', $sede->municipio_id) }}';
        municipioSelect.innerHTML = '<option value="">Selecciona municipio</option>';
        if (Array.isArray(municipios)) {
            municipios.forEach(municipio => {
                const option = document.createElement('option');
                option.value = municipio.id;
                option.textContent = municipio.nombre;
                if (municipio.id == selectedMunicipio) {
                    option.selected = true;
                }
                municipioSelect.appendChild(option);
            });
        }
        
        barrioSelect.innerHTML = '<option value="">Selecciona un barrio (opcional)</option>';
        
    } catch (error) {
        console.error('❌ Error:', error);
    }
});

// ⚠️ CASCADA: Municipio → Barrio
document.getElementById('municipioSelect').addEventListener('change', async function() {
    const municipio_id = this.value;
    const barrioSelect = document.getElementById('barrioSelect');
    
    console.log('🏙️ Municipio seleccionado:', municipio_id);
    
    if (!municipio_id) {
        barrioSelect.innerHTML = '<option value="">Selecciona un barrio (opcional)</option>';
        return;
    }
    
    try {
        // URL ABSOLUTA usando window.Laravel.baseUrl
        const apiUrl = `${window.Laravel.baseUrl}/api/barrios-por-municipio?municipio_id=${municipio_id}`;
        
        console.log('🌐 URL del API (absoluta):', apiUrl);
        
        const response = await fetch(apiUrl);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const barrios = await response.json();
        console.log('✅ Barrios recibidos:', barrios);
        
        const selectedBarrio = '{{ old('barrio_id', $sede->barrio_id) }}';
        barrioSelect.innerHTML = '<option value="">Selecciona un barrio (opcional)</option>';
        if (Array.isArray(barrios)) {
            barrios.forEach(barrio => {
                const option = document.createElement('option');
                option.value = barrio.id;
                option.textContent = barrio.nombre;
                if (barrio.id == selectedBarrio) {
                    option.selected = true;
                }
                barrioSelect.appendChild(option);
            });
        }
        
    } catch (error) {
        console.error('❌ Error:', error);
    }
});

// Cargar barrios cuando carga la página (si hay municipio seleccionado)
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 DOM Cargado completamente');
    const municipioSelect = document.getElementById('municipioSelect');
    const municipio_id = municipioSelect.value;
    
    if (municipio_id) {
        const event = new Event('change');
        municipioSelect.dispatchEvent(event);
    }
});

console.log('✅ Script de cascada (edit) cargado correctamente');
</script>
@endsection
