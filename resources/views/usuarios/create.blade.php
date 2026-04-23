@extends('layouts.app')

@section('title', 'Crear Usuario')
@section('page-title', 'Crear Nuevo Usuario')
@section('page-description', 'Completa el formulario para registrar un nuevo usuario')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('usuarios.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Row 1: Nombre y Email -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nombre <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Email <span class="text-red-600">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 2: Contraseña -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Contraseña <span class="text-red-600">*</span>
                    </label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Confirmar Contraseña <span class="text-red-600">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 3: Empresa y Rol -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Empresa <span class="text-red-600">*</span>
                    </label>
                    <select name="empresa_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('empresa_id') border-red-500 @enderror">
                        <option value="">Selecciona una empresa</option>
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
                        Rol <span class="text-red-600">*</span>
                    </label>
                    <select name="rol" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('rol') border-red-500 @enderror">
                        <option value="">Selecciona un rol</option>
                        <option value="admin" {{ old('rol') === 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="gerente" {{ old('rol') === 'gerente' ? 'selected' : '' }}>Gerente</option>
                        <option value="coordinador" {{ old('rol') === 'coordinador' ? 'selected' : '' }}>Coordinador</option>
                        <option value="empleado" {{ old('rol') === 'empleado' ? 'selected' : '' }}>Empleado</option>
                    </select>
                    @error('rol')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 4: Sede -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sede (Opcional)</label>
                <select name="sede_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecciona una sede</option>
                    @foreach($sedes as $sede)
                        <option value="{{ $sede->id }}" {{ old('sede_id') == $sede->id ? 'selected' : '' }}>
                            {{ $sede->nombre }} - {{ $sede->empresa->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- State Toggle -->
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="estado" value="1" {{ old('estado', true) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <span class="text-sm font-semibold text-gray-700">Usuario Activo</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('usuarios.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Guardar Usuario
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
