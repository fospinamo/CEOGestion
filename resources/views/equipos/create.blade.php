@extends('layouts.app')

@section('title', $equipo ? 'Editar Equipo' : 'Nuevo Equipo')
@section('page-title', $equipo ? 'Editar Equipo' : 'Registrar Nuevo Equipo')
@section('page-description', $equipo ? 'Actualizar equipo TI' : 'Agregar equipo a inventario')

@section('content')
<div class="max-w-4xl">
    <form action="{{ $equipo ? route('equipos.update', $equipo) : route('equipos.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @if($equipo)
            @method('PUT')
        @endif

        <!-- Ubicación -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ubicación</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Área *</label>
                    <select name="area_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('area_id') border-red-500 @enderror" required>
                        <option value="">Seleccione área</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ old('area_id', $equipo->area_id ?? '') == $area->id ? 'selected' : '' }}>
                                {{ $area->nombre }} - {{ $area->sede->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('area_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Equipo *</label>
                    <select name="tipo_equipo_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('tipo_equipo_id') border-red-500 @enderror" required>
                        <option value="">Seleccione tipo</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}" {{ old('tipo_equipo_id', $equipo->tipo_equipo_id ?? '') == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_equipo_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Identificación -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Identificación</h3>
            
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Código Interno *</label>
                    <input type="text" name="codigo_interno" value="{{ old('codigo_interno', $equipo->codigo_interno ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('codigo_interno') border-red-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Serial *</label>
                    <input type="text" name="serial" value="{{ old('serial', $equipo->serial ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('serial') border-red-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Estado Operativo *</label>
                    <select name="estado_operativo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('estado_operativo') border-red-500 @enderror" required>
                        <option value="OPERATIVO" {{ old('estado_operativo', $equipo->estado_operativo ?? 'OPERATIVO') == 'OPERATIVO' ? 'selected' : '' }}>Operativo</option>
                        <option value="MANTENIMIENTO" {{ old('estado_operativo', $equipo->estado_operativo ?? '') == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
                        <option value="REPARACION" {{ old('estado_operativo', $equipo->estado_operativo ?? '') == 'REPARACION' ? 'selected' : '' }}>Reparación</option>
                        <option value="BAJA" {{ old('estado_operativo', $equipo->estado_operativo ?? '') == 'BAJA' ? 'selected' : '' }}>Baja</option>
                        <option value="OBSOLETO" {{ old('estado_operativo', $equipo->estado_operativo ?? '') == 'OBSOLETO' ? 'selected' : '' }}>Obsoleto</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Especificaciones -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Especificaciones</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Marca *</label>
                    <input type="text" name="marca" value="{{ old('marca', $equipo->marca ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('marca') border-red-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Modelo *</label>
                    <input type="text" name="modelo" value="{{ old('modelo', $equipo->modelo ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('modelo') border-red-500 @enderror" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Especificaciones (JSON)</label>
                    <textarea name="especificaciones_tecnicas" rows="2" placeholder='{"ram":"8GB","procesador":"Intel i5"}' class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 font-mono text-sm">{{ old('especificaciones_tecnicas', $equipo?->especificaciones_tecnicas ? json_encode($equipo->especificaciones_tecnicas, JSON_PRETTY_PRINT) : '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Usuario Asignado</label>
                    <input type="text" name="usuario_asignado" value="{{ old('usuario_asignado', $equipo->usuario_asignado ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Red -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Configuración de Red</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">IP</label>
                    <input type="text" name="ip_asignada" value="{{ old('ip_asignada', $equipo->ip_asignada ?? '') }}" placeholder="192.168.1.100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">MAC Address</label>
                    <input type="text" name="mac_address" value="{{ old('mac_address', $equipo->mac_address ?? '') }}" placeholder="00:1A:2B:3C:4D:5E" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Fechas y Valor -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Compra</h3>
            
            <div class="grid grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Compra</label>
                    <input type="date" name="fecha_compra" value="{{ old('fecha_compra', $equipo->fecha_compra?->format('Y-m-d') ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Instalación</label>
                    <input type="date" name="fecha_instalacion" value="{{ old('fecha_instalacion', $equipo->fecha_instalacion?->format('Y-m-d') ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Garantía</label>
                    <input type="date" name="fecha_garantia" value="{{ old('fecha_garantia', $equipo->fecha_garantia?->format('Y-m-d') ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Valor Compra</label>
                    <input type="number" name="valor_compra" step="0.01" value="{{ old('valor_compra', $equipo->valor_compra ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Observaciones</label>
            <textarea name="observaciones" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('observaciones', $equipo->observaciones ?? '') }}</textarea>
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('equipos.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $equipo ? 'Actualizar' : 'Registrar' }} Equipo
            </button>
        </div>
    </form>
</div>
@endsection
