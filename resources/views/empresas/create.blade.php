@extends('layouts.app')

@section('title', 'Crear Empresa')
@section('page-title', 'Crear Nueva Empresa')
@section('page-description', 'Completa el formulario para registrar una nueva empresa')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('empresas.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Row 1: Nombre y NIT -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nombre de la Empresa <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        NIT <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="nit" value="{{ old('nit') }}" required
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
                    <input type="text" name="digito_verificacion" value="{{ old('digito_verificacion') }}" maxlength="1" required
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
                        <option value="">Selecciona un tipo</option>
                        <option value="persona_natural" {{ old('tipo_contribuyente') === 'persona_natural' ? 'selected' : '' }}>Persona Natural</option>
                        <option value="persona_juridica" {{ old('tipo_contribuyente') === 'persona_juridica' ? 'selected' : '' }}>Persona Jurídica</option>
                        <option value="gran_contribuyente" {{ old('tipo_contribuyente') === 'gran_contribuyente' ? 'selected' : '' }}>Gran Contribuyente</option>
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
                    <input type="tel" name="telefono" value="{{ old('telefono') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 4: Página Web -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Página Web</label>
                <input type="url" name="pagina_web" value="{{ old('pagina_web') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Row 5: Dirección -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Dirección</label>
                <textarea name="direccion" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('direccion') }}</textarea>
            </div>

            <!-- State Toggle -->
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="estado" value="1" {{ old('estado', true) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <span class="text-sm font-semibold text-gray-700">Empresa Activa</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('empresas.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Guardar Empresa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
