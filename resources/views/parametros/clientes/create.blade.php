@extends('layouts.app')

@section('title', $cliente ? 'Editar Cliente' : 'Crear Cliente')
@section('page-title', $cliente ? 'Editar Cliente' : 'Nuevo Cliente')
@section('page-description', $cliente ? 'Actualizar información del cliente' : 'Registrar un nuevo cliente')

@section('content')
<div class="max-w-4xl">
    <form action="{{ $cliente ? route('parametros.clientes.update', $cliente) : route('parametros.clientes.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @if($cliente)
            @method('PUT')
        @endif

        <!-- Información Básica -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Básica</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Empresa *</label>
                    <select name="empresa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('empresa_id') border-red-500 @enderror" required>
                        <option value="">Seleccione empresa</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ old('empresa_id', $cliente->empresa_id ?? '') == $empresa->id ? 'selected' : '' }}>
                                {{ $empresa->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('empresa_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Documento *</label>
                    <select name="tipo_documento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('tipo_documento') border-red-500 @enderror" required>
                        <option value="">Seleccione</option>
                        <option value="NIT" {{ old('tipo_documento', $cliente->tipo_documento ?? '') == 'NIT' ? 'selected' : '' }}>NIT</option>
                        <option value="CC" {{ old('tipo_documento', $cliente->tipo_documento ?? '') == 'CC' ? 'selected' : '' }}>Cédula (CC)</option>
                        <option value="CE" {{ old('tipo_documento', $cliente->tipo_documento ?? '') == 'CE' ? 'selected' : '' }}>Cédula Extranjera (CE)</option>
                        <option value="PASAPORTE" {{ old('tipo_documento', $cliente->tipo_documento ?? '') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                    </select>
                    @error('tipo_documento')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Documento *</label>
                    <input type="text" name="documento" value="{{ old('documento', $cliente->documento ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('documento') border-red-500 @enderror" required>
                    @error('documento')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dígito de Verificación</label>
                    <input type="text" name="digito_verificacion" value="{{ old('digito_verificacion', $cliente->digito_verificacion ?? '') }}" maxlength="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Razón Social *</label>
                    <input type="text" name="razon_social" value="{{ old('razon_social', $cliente->razon_social ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('razon_social') border-red-500 @enderror" required>
                    @error('razon_social')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre Comercial</label>
                    <input type="text" name="nombre_comercial" value="{{ old('nombre_comercial', $cliente->nombre_comercial ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Datos de Persona Natural (si aplica) -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos Personales</h3>
            
            <div class="grid grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Primer Nombre</label>
                    <input type="text" name="primer_nombre" value="{{ old('primer_nombre', $cliente->primer_nombre ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" value="{{ old('segundo_nombre', $cliente->segundo_nombre ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Primer Apellido</label>
                    <input type="text" name="primer_apellido" value="{{ old('primer_apellido', $cliente->primer_apellido ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Segundo Apellido</label>
                    <input type="text" name="segundo_apellido" value="{{ old('segundo_apellido', $cliente->segundo_apellido ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Contacto Principal -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contacto Principal</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Principal *</label>
                    <input type="email" name="email_principal" value="{{ old('email_principal', $cliente->email_principal ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('email_principal') border-red-500 @enderror" required>
                    @error('email_principal')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Secundario</label>
                    <input type="email" name="email_secundario" value="{{ old('email_secundario', $cliente->email_secundario ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono Fijo</label>
                    <input type="text" name="telefono_fijo" value="{{ old('telefono_fijo', $cliente->telefono_fijo ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono Móvil</label>
                    <input type="text" name="telefono_movil" value="{{ old('telefono_movil', $cliente->telefono_movil ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">WhatsApp</label>
                    <input type="text" name="telefono_whatsapp" value="{{ old('telefono_whatsapp', $cliente->telefono_whatsapp ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Dirección de Notificación -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Dirección de Notificación</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dirección *</label>
                    <textarea name="direccion_notificacion" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('direccion_notificacion') border-red-500 @enderror" required>{{ old('direccion_notificacion', $cliente->direccion_notificacion ?? '') }}</textarea>
                    @error('direccion_notificacion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ciudad *</label>
                    <select name="ciudad_notificacion_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('ciudad_notificacion_id') border-red-500 @enderror" required>
                        <option value="">Seleccione ciudad</option>
                        @foreach($municipios as $municipio)
                            <option value="{{ $municipio->id }}" {{ old('ciudad_notificacion_id', $cliente->ciudad_notificacion_id ?? '') == $municipio->id ? 'selected' : '' }}>
                                {{ $municipio->nombre }} - {{ $municipio->departamento->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('ciudad_notificacion_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Contacto en el Cliente -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contacto en el Cliente</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Contacto *</label>
                    <input type="text" name="contacto_nombre" value="{{ old('contacto_nombre', $cliente->contacto_nombre ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('contacto_nombre') border-red-500 @enderror" required>
                    @error('contacto_nombre')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cargo</label>
                    <input type="text" name="contacto_cargo" value="{{ old('contacto_cargo', $cliente->contacto_cargo ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono *</label>
                    <input type="text" name="contacto_telefono" value="{{ old('contacto_telefono', $cliente->contacto_telefono ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('contacto_telefono') border-red-500 @enderror" required>
                    @error('contacto_telefono')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                    <input type="email" name="contacto_email" value="{{ old('contacto_email', $cliente->contacto_email ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('contacto_email') border-red-500 @enderror" required>
                    @error('contacto_email')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Estado -->
        <div class="flex items-center gap-2">
            <input type="checkbox" name="estado" value="1" {{ old('estado', $cliente->estado ?? true) ? 'checked' : '' }} class="rounded">
            <label class="text-sm font-semibold text-gray-700">Cliente Activo</label>
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('parametros.clientes.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $cliente ? 'Actualizar' : 'Crear' }} Cliente
            </button>
        </div>
    </form>
</div>
@endsection
