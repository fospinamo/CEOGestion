@extends('layouts.app')

@section('title', $equipo ? 'Editar Equipo' : 'Nuevo Equipo')
@section('page-title', $equipo ? 'Editar Equipo' : 'Registrar Nuevo Equipo')
@section('page-description', $equipo ? 'Actualizar equipo TI' : 'Agregar equipo a inventario')

@section('content')

@if(isset($equipo) && !$equipo && request()->method() === 'PUT')
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <h2 class="text-lg font-bold text-red-800 mb-2">Error: Equipo no encontrado</h2>
        <p class="text-red-700 mb-4">No se pudo cargar el equipo para editar.</p>
        <a href="{{ route('parametros.equipos.index') }}" class="inline-block px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
            Volver a la lista
        </a>
    </div>
@else
<div class="max-w-4xl">
    <form action="{{ $equipo ? url('parametros/equipos/' . $equipo->id) : route('parametros.equipos.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @if($equipo)
            @method('PUT')
        @endif

        <!-- Ubicación - Propietario y Sedes -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ubicación</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Empresa</label>
                    <select id="empresa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">-- Selecciona una empresa --</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ $equipo && $equipo->area->sede->empresa_id == $empresa->id ? 'selected' : '' }}>
                                {{ $empresa->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cliente</label>
                    <select id="cliente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">-- Selecciona un cliente --</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ $equipo && $equipo->area->sede->cliente_id == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->razon_social }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sede *</label>
                    <select id="sede_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('sede_id') border-red-500 @enderror">
                        <option value="">Seleccione sede</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id }}" 
                                data-empresa-id="{{ $sede->empresa_id }}"
                                data-cliente-id="{{ $sede->cliente_id }}"
                                {{ $equipo && $equipo->area->sede_id == $sede->id ? 'selected' : '' }}>
                                {{ $sede->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('sede_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Área *</label>
                    <select id="area_id" name="area_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('area_id') border-red-500 @enderror" required>
                        <option value="">Seleccione área</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" 
                                data-sede-id="{{ $area->sede_id }}"
                                {{ old('area_id', $equipo->area_id ?? '') == $area->id ? 'selected' : '' }}>
                                {{ $area->nombre }}
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

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="3" placeholder="Descripción detallada del equipo, características especiales, etc." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('descripcion', $equipo->descripcion ?? '') }}</textarea>
                    @error('descripcion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div class="col-span-2">
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
                    <input type="date" name="fecha_compra" value="{{ old('fecha_compra', $equipo?->fecha_compra?->format('Y-m-d') ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Instalación</label>
                    <input type="date" name="fecha_instalacion" value="{{ old('fecha_instalacion', $equipo?->fecha_instalacion?->format('Y-m-d') ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Garantía</label>
                    <input type="date" name="fecha_garantia" value="{{ old('fecha_garantia', $equipo?->fecha_garantia?->format('Y-m-d') ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Valor Compra</label>
                    <input type="number" name="valor_compra" step="0.01" value="{{ old('valor_compra', $equipo?->valor_compra ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
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
            <a href="{{ route('parametros.equipos.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $equipo ? 'Actualizar' : 'Registrar' }} Equipo
            </button>
        </div>
    </form>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    // Almacenar opciones originales
    const sedeOptions = $('#sede_id').html();
    const areaOptions = $('#area_id').html();
    
    function filterSedes() {
        const empresaId = $('#empresa_id').val();
        const clienteId = $('#cliente_id').val();
        const sedeSelect = $('#sede_id');
        
        // Restaurar opciones originales
        sedeSelect.html(sedeOptions);
        
        // Filtrar opciones
        sedeSelect.find('option').each(function() {
            if ($(this).val() === '') return;
            
            const optEmpresaId = $(this).data('empresa-id');
            const optClienteId = $(this).data('cliente-id');
            
            if (empresaId && optEmpresaId == empresaId) {
                // Mostrar
            } else if (clienteId && optClienteId == clienteId) {
                // Mostrar
            } else if (!empresaId && !clienteId) {
                // Sin filtro, mostrar todas
            } else {
                // Ocultar
                $(this).hide();
            }
        });
        
        // Limpiar área y sede si no coinciden
        const selectedSede = sedeSelect.val();
        if (selectedSede && sedeSelect.find('option[value="' + selectedSede + '"]').is(':hidden')) {
            sedeSelect.val('');
        }
    }
    
    function filterAreas() {
        const sedeId = $('#sede_id').val();
        const areaSelect = $('#area_id');
        
        // Restaurar opciones originales
        areaSelect.html(areaOptions);
        
        // Filtrar opciones
        areaSelect.find('option').each(function() {
            if ($(this).val() === '') return;
            
            const optSedeId = $(this).data('sede-id');
            
            if (sedeId && optSedeId == sedeId) {
                // Mostrar
            } else if (!sedeId) {
                // Sin filtro, mostrar todas
            } else {
                // Ocultar
                $(this).hide();
            }
        });
        
        // Limpiar área si no coincide
        const selectedArea = areaSelect.val();
        if (selectedArea && areaSelect.find('option[value="' + selectedArea + '"]').is(':hidden')) {
            areaSelect.val('');
        }
    }
    
    // Eventos
    $('#empresa_id').on('change', function() {
        if ($(this).val()) {
            $('#cliente_id').val('');
        }
        filterSedes();
        filterAreas();
    });
    
    $('#cliente_id').on('change', function() {
        if ($(this).val()) {
            $('#empresa_id').val('');
        }
        filterSedes();
        filterAreas();
    });
    
    $('#sede_id').on('change', function() {
        filterAreas();
    });
    
    // Filtrar al cargar si hay valores preseleccionados
    filterSedes();
    filterAreas();
});
</script>
@endif
@endsection
