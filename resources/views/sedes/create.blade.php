@extends('layouts.app')

@section('title', 'Crear Sede')
@section('page-title', 'Crear Nueva Sede')
@section('page-description', 'Completa el formulario para registrar una nueva sede')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('sedes.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Row 1: Propietario (Empresa o Cliente) -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-700 font-semibold mb-4">
                    <i class="fas fa-info-circle"></i> Selecciona el propietario: empresa O cliente (pero no ambos)
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
                                <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
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
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
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
                <input type="text" name="nombre" value="{{ old('nombre') }}" required
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
                <input type="text" name="codigo" value="{{ old('codigo') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('codigo') border-red-500 @enderror">
                @error('codigo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Row 3: Dirección -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Dirección</label>
                <textarea name="direccion" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('direccion') }}</textarea>
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
                            <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
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
                        @if(old('municipio_id'))
                            @foreach($municipios as $municipio)
                                <option value="{{ $municipio->id }}" {{ old('municipio_id') == $municipio->id ? 'selected' : '' }}>
                                    {{ $municipio->nombre }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('municipio_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Barrio</label>
                    <select name="barrio_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecciona un barrio (opcional)</option>
                        @foreach($barrios as $barrio)
                            <option value="{{ $barrio->id }}" {{ old('barrio_id') == $barrio->id ? 'selected' : '' }}>
                                {{ $barrio->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Row 5: Código Postal y Contacto -->
            <div class="grid grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Código Postal</label>
                    <input type="text" name="codigo_postal" value="{{ old('codigo_postal') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono</label>
                    <input type="tel" name="telefono" value="{{ old('telefono') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- State Toggle -->
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="estado" value="1" {{ old('estado', true) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <span class="text-sm font-semibold text-gray-700">Sede Activa</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('sedes.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Guardar Sede
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('departamentoSelect').addEventListener('change', async function() {
    const departamento_id = this.value;
    const municipioSelect = document.getElementById('municipioSelect');
    
    if (!departamento_id) {
        municipioSelect.innerHTML = '<option value="">Selecciona municipio</option>';
        return;
    }
    
    try {
        const response = await fetch(`/api/municipios-por-departamento?departamento_id=${departamento_id}`);
        const municipios = await response.json();
        
        municipioSelect.innerHTML = '<option value="">Selecciona municipio</option>';
        municipios.forEach(municipio => {
            const option = document.createElement('option');
            option.value = municipio.id;
            option.textContent = municipio.nombre;
            municipioSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error cargando municipios:', error);
    }
});
</script>
@endsection
