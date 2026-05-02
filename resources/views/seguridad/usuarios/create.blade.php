@extends('layouts.app')

@section('page-title', 'Nuevo Usuario')
@section('page-description', 'Crear nuevo usuario del sistema')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Crear Nuevo Usuario</h1>

        <form action="{{ route('seguridad.usuarios.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo *</label>
                <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                    value="{{ old('name') }}" required>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                    value="{{ old('email') }}" required>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Contraseña *</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                    required minlength="8">
                <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres</p>
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Confirmar Contraseña *</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                    required minlength="8">
            </div>

            <!-- Rol -->
            <div>
                <label for="role_id" class="block text-sm font-semibold text-gray-700 mb-1">Rol *</label>
                <select name="role_id" id="role_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                    <option value="">Seleccionar rol...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Empresa -->
            <div>
                <label for="empresa_id" class="block text-sm font-semibold text-gray-700 mb-1">Empresa</label>
                <select name="empresa_id" id="empresa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Seleccionar empresa...</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                            {{ $empresa->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sede -->
            <div>
                <label for="sede_id" class="block text-sm font-semibold text-gray-700 mb-1">Sede</label>
                <select name="sede_id" id="sede_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Seleccionar sede...</option>
                    @foreach($sedes as $sede)
                        <option value="{{ $sede->id }}" {{ old('sede_id') == $sede->id ? 'selected' : '' }}>
                            {{ $sede->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Cédula -->
            <div>
                <label for="cedula" class="block text-sm font-semibold text-gray-700 mb-1">Cédula</label>
                <input type="text" name="cedula" id="cedula" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                    value="{{ old('cedula') }}">
            </div>

            <!-- Teléfono -->
            <div>
                <label for="telefono" class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                    value="{{ old('telefono') }}">
            </div>

            <!-- Estado -->
            <div class="flex items-center gap-2">
                <input type="checkbox" name="estado" id="estado" value="1" class="rounded" {{ old('estado') ? 'checked' : 'checked' }}>
                <label for="estado" class="text-sm font-semibold text-gray-700">Activo</label>
            </div>

            <!-- Botones -->
            <div class="flex gap-2 pt-6">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center gap-2">
                    <i class="fas fa-save"></i> Guardar Usuario
                </button>
                <a href="{{ route('seguridad.usuarios.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Script: Filtrar Sedes por Empresa -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const empresaSelect = document.getElementById('empresa_id');
        const sedeSelect = document.getElementById('sede_id');

        /**
         * Función para cargar sedes dinámicamente según la empresa seleccionada
         */
        function cargarSedesPorEmpresa() {
            const empresaId = empresaSelect.value;

            // Si no hay empresa seleccionada, limpiar dropdown de sedes
            if (!empresaId) {
                sedeSelect.innerHTML = '<option value="">Seleccionar sede...</option>';
                return;
            }

            // Llamar API para obtener sedes de la empresa
            fetch(`/api/sedes-por-empresa?empresa_id=${empresaId}`)
                .then(response => response.json())
                .then(sedes => {
                    // Limpiar opciones anteriores
                    sedeSelect.innerHTML = '<option value="">Seleccionar sede...</option>';

                    // Agregar nuevas opciones
                    sedes.forEach(sede => {
                        const option = document.createElement('option');
                        option.value = sede.id;
                        option.textContent = sede.nombre;
                        sedeSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error al cargar sedes:', error);
                    sedeSelect.innerHTML = '<option value="">Error al cargar sedes</option>';
                });
        }

        // Escuchar cambios en el dropdown de empresa
        empresaSelect.addEventListener('change', cargarSedesPorEmpresa);
    });
</script>
@endsection
