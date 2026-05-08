@extends('layouts.app')

@section('title', 'Editar Empresa')
@section('page-title', 'Editar Empresa')
@section('page-description', 'Actualiza la información de la empresa')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('parametros.empresas.update', $empresa) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Row 1: Nombre y NIT -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nombre de la Empresa <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="nombre" value="{{ old('nombre', $empresa->nombre) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        NIT <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="nit" value="{{ old('nit', $empresa->nit) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nit') border-red-500 @enderror">
                    @error('nit')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 2: Dígito Verificación y Tipo Contribuyente -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Dígito de Verificación <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="digito_verificacion" value="{{ old('digito_verificacion', $empresa->digito_verificacion) }}" maxlength="1" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('digito_verificacion') border-red-500 @enderror">
                    @error('digito_verificacion')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tipo de Contribuyente <span class="text-red-600">*</span>
                    </label>
                    <select name="tipo_contribuyente" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tipo_contribuyente') border-red-500 @enderror">
                        <option value="persona_natural" {{ old('tipo_contribuyente', $empresa->tipo_contribuyente) === 'persona_natural' ? 'selected' : '' }}>Persona Natural</option>
                        <option value="persona_juridica" {{ old('tipo_contribuyente', $empresa->tipo_contribuyente) === 'persona_juridica' ? 'selected' : '' }}>Persona Jurídica</option>
                        <option value="gran_contribuyente" {{ old('tipo_contribuyente', $empresa->tipo_contribuyente) === 'gran_contribuyente' ? 'selected' : '' }}>Gran Contribuyente</option>
                    </select>
                    @error('tipo_contribuyente')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 3: Teléfono y Email -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono</label>
                    <input type="tel" name="telefono" value="{{ old('telefono', $empresa->telefono) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $empresa->email) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 4: Página Web -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Página Web</label>
                <input type="url" name="pagina_web" value="{{ old('pagina_web', $empresa->pagina_web) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Logo Upload Section -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition cursor-pointer bg-gray-50"
                onclick="document.getElementById('logoInput').click()">
                <div id="logoPreviewContainer" class="{{ $empresa->logo ? '' : 'hidden' }}">
                    @if($empresa->logo)
                        <img id="logoPreview" src="{{ asset($empresa->logo) }}" alt="Logo actual" class="h-32 mx-auto mb-4 object-contain">
                    @else
                        <img id="logoPreview" src="" alt="Vista previa" class="h-32 mx-auto mb-4 object-contain">
                    @endif
                    <p class="text-sm text-gray-600">Click para cambiar logo</p>
                </div>
                <div id="logoPlaceholder" class="{{ $empresa->logo ? 'hidden' : '' }}">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-sm font-semibold text-gray-700">Actualiza el logo de la empresa</p>
                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF (máx. 2MB)</p>
                </div>
                <input type="file" id="logoInput" name="logo" accept="image/*" 
                    class="hidden"
                    onchange="previewImage(event)">
                @error('logo')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Row 5: Descripción -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                <textarea name="descripcion" rows="2" placeholder="Descripción de la empresa"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $empresa->descripcion) }}</textarea>
            </div>

            <!-- Row 6: Dirección -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Dirección</label>
                <textarea name="direccion" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('direccion', $empresa->direccion) }}</textarea>
            </div>

            <!-- State Toggle -->
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="estado" value="1" {{ old('estado', $empresa->estado) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <span class="text-sm font-semibold text-gray-700">Empresa Activa</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('parametros.empresas.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Actualizar Empresa
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Cambiar de placeholder a preview
            const placeholder = document.getElementById('logoPlaceholder');
            const container = document.getElementById('logoPreviewContainer');
            const preview = document.getElementById('logoPreview');
            
            if (placeholder) placeholder.classList.add('hidden');
            if (container) container.classList.remove('hidden');
            if (preview) preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

// Solo restaurar placeholder si NO hay logo en la BD
document.addEventListener('DOMContentLoaded', function() {
    const logoInput = document.getElementById('logoInput');
    const container = document.getElementById('logoPreviewContainer');
    const placeholder = document.getElementById('logoPlaceholder');
    
    // Si no hay archivos seleccionados Y no hay logo en la BD, mostrar placeholder
    if (logoInput && logoInput.files.length === 0 && !{{ $empresa->logo ? 'true' : 'false' }}) {
        if (container) container.classList.add('hidden');
        if (placeholder) placeholder.classList.remove('hidden');
    }
});
</script>
@endsection
