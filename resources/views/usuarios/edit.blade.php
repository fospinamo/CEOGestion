@extends('layouts.app')

@section('title', 'Editar Usuario')
@section('page-title', 'Editar Usuario')
@section('page-description', 'Actualiza la información del usuario')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('seguridad.usuarios.update', $usuario) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Row 1: Nombre y Email -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nombre <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $usuario->name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Email <span class="text-red-600">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 2: Contraseña (opcional) -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nueva Contraseña (dejar en blanco si no cambias)
                    </label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Confirmar Contraseña
                    </label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 3: Empresa y Rol -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Empresa <span class="text-red-600">*</span>
                    </label>
                    <select id="empresa_id" name="empresa_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('empresa_id') border-red-500 @enderror">
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ old('empresa_id', $usuario->empresa_id) == $empresa->id ? 'selected' : '' }}>
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
                        Tipo de Rol <span class="text-red-600">*</span>
                    </label>
                    <select name="tipo_rol" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tipo_rol') border-red-500 @enderror">
                        <option value="admin" {{ old('tipo_rol', $usuario->tipo_rol) === 'admin' ? 'selected' : '' }}>👨‍💼 Administrador</option>
                        <option value="tecnico" {{ old('tipo_rol', $usuario->tipo_rol) === 'tecnico' ? 'selected' : '' }}>🔧 Técnico</option>
                        <option value="coordinador" {{ old('tipo_rol', $usuario->tipo_rol) === 'coordinador' ? 'selected' : '' }}>📋 Coordinador</option>
                        <option value="operario" {{ old('tipo_rol', $usuario->tipo_rol) === 'operario' ? 'selected' : '' }}>👤 Operario</option>
                        <option value="cliente" {{ old('tipo_rol', $usuario->tipo_rol) === 'cliente' ? 'selected' : '' }}>🏢 Cliente</option>
                    </select>
                    @error('tipo_rol')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 4: Sede -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sede (Opcional)</label>
                <select id="sede_id" name="sede_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecciona una sede</option>
                    @foreach($sedes as $sede)
                        <option value="{{ $sede->id }}" data-empresa-id="{{ $sede->empresa_id }}" {{ old('sede_id', $usuario->sede_id) == $sede->id ? 'selected' : '' }}>
                            {{ $sede->nombre }} - {{ $sede->cliente?->razon_social }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- State Toggle -->
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="estado" value="1" {{ old('estado', $usuario->estado) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <span class="text-sm font-semibold text-gray-700">Usuario Activo</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('seguridad.usuarios.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const empresaSelect = document.getElementById('empresa_id');
    const sedeSelect = document.getElementById('sede_id');
    
    // Guardar opciones originales de sede
    const originalSedeOptions = Array.from(sedeSelect.options).map(opt => ({
        value: opt.value,
        text: opt.text,
        empresaId: opt.dataset.empresaId
    }));
    
    function actualizarSedes() {
        const empresaId = empresaSelect.value;
        const sedeIdActual = sedeSelect.dataset.currentValue || '';
        
        if (!empresaId) {
            // Si no hay empresa seleccionada, mostrar todas las sedes
            sedeSelect.innerHTML = '<option value="">Selecciona una sede</option>';
            originalSedeOptions.forEach(opt => {
                if (opt.value) {
                    const option = document.createElement('option');
                    option.value = opt.value;
                    option.text = opt.text;
                    option.dataset.empresaId = opt.empresaId;
                    sedeSelect.appendChild(option);
                }
            });
            return;
        }
        
        // Filtrar opciones por empresa
        sedeSelect.innerHTML = '<option value="">Selecciona una sede</option>';
        originalSedeOptions.forEach(opt => {
            if (opt.value && opt.empresaId == empresaId) {
                const option = document.createElement('option');
                option.value = opt.value;
                option.text = opt.text;
                option.dataset.empresaId = opt.empresaId;
                sedeSelect.appendChild(option);
            }
        });
    }
    
    // Ejecutar al cambiar empresa
    empresaSelect.addEventListener('change', actualizarSedes);
    
    // Ejecutar al cargar la página para filtrar si hay empresa pre-seleccionada
    actualizarSedes();
});
</script>
@endsection
