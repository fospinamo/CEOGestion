@extends('layouts.app')

@section('title', $contrato ? 'Editar Contrato' : 'Crear Contrato')
@section('page-title', $contrato ? 'Editar Contrato' : 'Nuevo Contrato')
@section('page-description', $contrato ? 'Actualizar contrato' : 'Registrar nuevo contrato')

@section('content')
<div class="max-w-5xl">
    <form action="{{ $contrato ? route('contratos.update', $contrato) : route('contratos.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @if($contrato)
            @method('PUT')
        @endif

        <!-- Información General -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información General</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cliente *</label>
                    <select name="cliente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('cliente_id') border-red-500 @enderror" required>
                        <option value="">Seleccione cliente</option>
                        @foreach($clientes as $cli)
                            <option value="{{ $cli->id }}" {{ old('cliente_id', $contrato->cliente_id ?? '') == $cli->id ? 'selected' : '' }}>
                                {{ $cli->razon_social }} ({{ $cli->empresa->nombre }})
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Número de Contrato *</label>
                    <input type="text" name="numero_contrato" value="{{ old('numero_contrato', $contrato->numero_contrato ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('numero_contrato') border-red-500 @enderror" required>
                    @error('numero_contrato')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Contrato *</label>
                    <select name="tipo_contrato" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('tipo_contrato') border-red-500 @enderror" required>
                        <option value="">Seleccione</option>
                        <option value="SOPORTE_TI" {{ old('tipo_contrato', $contrato->tipo_contrato ?? '') == 'SOPORTE_TI' ? 'selected' : '' }}>Soporte TI</option>
                        <option value="MANTENIMIENTO" {{ old('tipo_contrato', $contrato->tipo_contrato ?? '') == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
                        <option value="INFRAESTRUCTURA" {{ old('tipo_contrato', $contrato->tipo_contrato ?? '') == 'INFRAESTRUCTURA' ? 'selected' : '' }}>Infraestructura</option>
                        <option value="CONSULTORIA" {{ old('tipo_contrato', $contrato->tipo_contrato ?? '') == 'CONSULTORIA' ? 'selected' : '' }}>Consultoría</option>
                    </select>
                    @error('tipo_contrato')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Modalidad *</label>
                    <select name="modalidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('modalidad') border-red-500 @enderror" required>
                        <option value="">Seleccione</option>
                        <option value="MENSUAL" {{ old('modalidad', $contrato->modalidad ?? '') == 'MENSUAL' ? 'selected' : '' }}>Mensual</option>
                        <option value="TRIMESTRAL" {{ old('modalidad', $contrato->modalidad ?? '') == 'TRIMESTRAL' ? 'selected' : '' }}>Trimestral</option>
                        <option value="SEMESTRAL" {{ old('modalidad', $contrato->modalidad ?? '') == 'SEMESTRAL' ? 'selected' : '' }}>Semestral</option>
                        <option value="ANUAL" {{ old('modalidad', $contrato->modalidad ?? '') == 'ANUAL' ? 'selected' : '' }}>Anual</option>
                    </select>
                    @error('modalidad')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Estado *</label>
                    <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('estado') border-red-500 @enderror" required>
                        <option value="">Seleccione</option>
                        <option value="BORRADOR" {{ old('estado', $contrato->estado ?? 'BORRADOR') == 'BORRADOR' ? 'selected' : '' }}>Borrador</option>
                        <option value="ACTIVO" {{ old('estado', $contrato->estado ?? '') == 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                        <option value="VENCIDO" {{ old('estado', $contrato->estado ?? '') == 'VENCIDO' ? 'selected' : '' }}>Vencido</option>
                        <option value="TERMINADO" {{ old('estado', $contrato->estado ?? '') == 'TERMINADO' ? 'selected' : '' }}>Terminado</option>
                        <option value="RENOVADO" {{ old('estado', $contrato->estado ?? '') == 'RENOVADO' ? 'selected' : '' }}>Renovado</option>
                    </select>
                    @error('estado')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Fechas -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Fechas</h3>
            
            <div class="grid grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Inicio *</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $contrato?->fecha_inicio?->format('Y-m-d') ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('fecha_inicio') border-red-500 @enderror" required>
                    @error('fecha_inicio')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Fin *</label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin', $contrato?->fecha_fin?->format('Y-m-d') ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('fecha_fin') border-red-500 @enderror" required>
                    @error('fecha_fin')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Firma *</label>
                    <input type="date" name="fecha_firma" value="{{ old('fecha_firma', $contrato?->fecha_firma?->format('Y-m-d') ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('fecha_firma') border-red-500 @enderror" required>
                    @error('fecha_firma')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Terminación</label>
                    <input type="date" name="fecha_terminacion" value="{{ old('fecha_terminacion', $contrato?->fecha_terminacion?->format('Y-m-d') ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Valores -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Valores</h3>
            
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Valor del Contrato *</label>
                    <input type="number" name="valor_contrato" step="0.01" value="{{ old('valor_contrato', $contrato->valor_contrato ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('valor_contrato') border-red-500 @enderror" required>
                    @error('valor_contrato')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Moneda *</label>
                    <select name="moneda" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('moneda') border-red-500 @enderror" required>
                        <option value="COP" {{ old('moneda', $contrato->moneda ?? 'COP') == 'COP' ? 'selected' : '' }}>COP</option>
                        <option value="USD" {{ old('moneda', $contrato->moneda ?? '') == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('moneda', $contrato->moneda ?? '') == 'EUR' ? 'selected' : '' }}>EUR</option>
                    </select>
                    @error('moneda')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Descripción del Servicio -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Descripción del Servicio</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alcance de Servicios *</label>
                    <textarea name="alcance_servicios" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('alcance_servicios') border-red-500 @enderror" required>{{ old('alcance_servicios', $contrato->alcance_servicios ?? '') }}</textarea>
                    @error('alcance_servicios')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Condiciones de Pago *</label>
                    <textarea name="condiciones_pago" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('condiciones_pago') border-red-500 @enderror" required>{{ old('condiciones_pago', $contrato->condiciones_pago ?? '') }}</textarea>
                    @error('condiciones_pago')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cláusulas Especiales</label>
                    <textarea name="clausulas_especiales" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('clausulas_especiales', $contrato->clausulas_especiales ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Documento y Firma -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Documento</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Archivo PDF (máx 10MB)</label>
                    <input type="file" name="documento_pdf" accept=".pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    @if($contrato?->documento_pdf)
                        <p class="text-xs text-gray-600 mt-2">Archivo actual: <a href="{{ asset('storage/' . $contrato->documento_pdf) }}" target="_blank" class="text-blue-600 hover:underline">Ver PDF</a></p>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="documento_firmado" value="1" {{ old('documento_firmado', $contrato->documento_firmado ?? false) ? 'checked' : '' }} class="rounded">
                    <label class="text-sm font-semibold text-gray-700">Documento Firmado</label>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="renovacion_automatica" value="1" {{ old('renovacion_automatica', $contrato->renovacion_automatica ?? false) ? 'checked' : '' }} class="rounded">
                    <label class="text-sm font-semibold text-gray-700">Habilitsar Renovación Automática</label>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('contratos.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $contrato ? 'Actualizar' : 'Crear' }} Contrato
            </button>
        </div>
    </form>
</div>
@endsection
