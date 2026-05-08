@extends('layouts.app')
@section('title', $area ? 'Editar Área' : 'Nueva Área')
@section('page-title', $area ? 'Editar Área' : 'Nueva Área')
@section('page-description', $area ? 'Actualizar área' : 'Crear nueva área')
@section('content')

@if(isset($area) && !$area && request()->method() === 'PUT')
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <h2 class="text-lg font-bold text-red-800 mb-2">Error: Área no encontrada</h2>
        <p class="text-red-700 mb-4">No se pudo cargar el área para editar.</p>
        <a href="{{ route('parametros.areas.index') }}" class="inline-block px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
            Volver a la lista
        </a>
    </div>
@else
<div class="max-w-3xl">
    <form action="{{ $area ? route('parametros.areas.update', ['area' => $area->id]) : route('parametros.areas.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @if($area) @method('PUT') @endif

        <!-- Selector Cliente/Empresa -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Propietario</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Empresa</label>
                    <select id="empresa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">-- Selecciona una empresa --</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ $area && $area->sede->empresa_id == $empresa->id ? 'selected' : '' }}>
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
                            <option value="{{ $cliente->id }}" {{ $area && $area->sede->cliente_id == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->razon_social }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sede *</label>
                <select name="sede_id" id="sede_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('sede_id') border-red-500 @enderror" required>
                    <option value="">Seleccione sede</option>
                    @foreach($sedes as $sede)
                        <option value="{{ $sede->id }}" 
                            data-empresa-id="{{ $sede->empresa_id }}"
                            data-cliente-id="{{ $sede->cliente_id }}"
                            {{ old('sede_id', $area->sede_id ?? '') == $sede->id ? 'selected' : '' }}>
                            {{ $sede->nombre }} - {{ $sede->cliente?->razon_social ?? $sede->empresa?->nombre ?? 'Sin propietario' }}
                        </option>
                    @endforeach
                </select>
                @error('sede_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $area->nombre ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nombre') border-red-500 @enderror" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nivel de Riesgo *</label>
                <select name="nivel_riesgo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nivel_riesgo') border-red-500 @enderror" required>
                    <option value="BAJO" {{ old('nivel_riesgo', $area->nivel_riesgo ?? 'BAJO') == 'BAJO' ? 'selected' : '' }}>Bajo</option>
                    <option value="MEDIO" {{ old('nivel_riesgo', $area->nivel_riesgo ?? '') == 'MEDIO' ? 'selected' : '' }}>Medio</option>
                    <option value="ALTO" {{ old('nivel_riesgo', $area->nivel_riesgo ?? '') == 'ALTO' ? 'selected' : '' }}>Alto</option>
                    <option value="CRITICO" {{ old('nivel_riesgo', $area->nivel_riesgo ?? '') == 'CRITICO' ? 'selected' : '' }}>Crítico</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Responsable</label>
                <input type="text" name="responsable_nombre" value="{{ old('responsable_nombre', $area->responsable_nombre ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Contacto Responsable</label>
                <input type="text" name="responsable_contacto" value="{{ old('responsable_contacto', $area->responsable_contacto ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <input type="checkbox" name="estado" value="1" {{ old('estado', $area->estado ?? true) ? 'checked' : '' }} class="rounded">
                    Activo
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
            <textarea name="descripcion" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('descripcion', $area->descripcion ?? '') }}</textarea>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('parametros.areas.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $area ? 'Actualizar' : 'Crear' }} Área
            </button>
        </div>
    </form>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    // Almacenar opciones originales de sede
    const sedeOptions = $('#sede_id').html();
    
    // Función para filtrar sedes
    function filterSedes() {
        const empresaId = $('#empresa_id').val();
        const clienteId = $('#cliente_id').val();
        const sedeSelect = $('#sede_id');
        
        // Restaurar opciones originales
        sedeSelect.html(sedeOptions);
        
        // Obtener la opción vacía
        let options = sedeSelect.find('option:first');
        
        // Filtrar opciones
        sedeSelect.find('option').each(function() {
            const optEmpresaId = $(this).data('empresa-id');
            const optClienteId = $(this).data('cliente-id');
            
            if ($(this).val() === '') {
                return; // Mantener opción vacía
            }
            
            // Mostrar solo si coincide con empresa o cliente seleccionado
            if (empresaId && optEmpresaId == empresaId) {
                // Coincide con empresa seleccionada
            } else if (clienteId && optClienteId == clienteId) {
                // Coincide con cliente seleccionado
            } else if (!empresaId && !clienteId) {
                // Sin filtro, mostrar todas
            } else {
                // Ocultar esta opción
                $(this).hide();
            }
        });
        
        // Limpiar selección si la sede actual no es válida
        const selectedSede = sedeSelect.val();
        if (selectedSede) {
            const selectedOption = sedeSelect.find('option[value="' + selectedSede + '"]');
            if (selectedOption.is(':hidden')) {
                sedeSelect.val('');
            }
        }
    }
    
    // Evento para cambio de empresa
    $('#empresa_id').on('change', function() {
        if ($(this).val()) {
            $('#cliente_id').val('');
        }
        filterSedes();
    });
    
    // Evento para cambio de cliente
    $('#cliente_id').on('change', function() {
        if ($(this).val()) {
            $('#empresa_id').val('');
        }
        filterSedes();
    });
    
    // Filtrar al cargar si hay sede preseleccionada
    filterSedes();
});
</script>
@endif
@endsection
