@extends('layouts.app')

@section('title', isset($contrato) ? 'Editar Contrato' : 'Crear Contrato')
@section('page-title', isset($contrato) ? 'Editar Contrato' : 'Nuevo Contrato')
@section('page-description', isset($contrato) ? 'Actualizar información del contrato' : 'Registrar un nuevo contrato de servicios TI')

@section('content')
<div class="max-w-4xl">
    <form action="{{ isset($contrato) ? route('parametros.contratos.update', $contrato) : route('parametros.contratos.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($contrato))
            @method('PUT')
        @endif

        <!-- Información Básica -->
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4">Información Básica</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Cliente -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cliente *</label>
                    <select name="cliente_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('cliente_id') border-red-500 @enderror" required>
                        <option value="">Seleccione cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id', $contrato->cliente_id ?? '') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->razon_social }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <!-- Número de Contrato -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Número de Contrato *</label>
                    <input type="text" name="numero_contrato" value="{{ old('numero_contrato', $contrato->numero_contrato ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('numero_contrato') border-red-500 @enderror" required placeholder="Ej: CT-2026-001">
                    @error('numero_contrato')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <!-- Tipo de Contrato -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Contrato *</label>
                    <select name="tipo_contrato" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('tipo_contrato') border-red-500 @enderror" required>
                        <option value="">Seleccione tipo</option>
                        <option value="SOPORTE_TI" {{ old('tipo_contrato', $contrato->tipo_contrato ?? '') == 'SOPORTE_TI' ? 'selected' : '' }}>Soporte TI</option>
                        <option value="MANTENIMIENTO" {{ old('tipo_contrato', $contrato->tipo_contrato ?? '') == 'MANTENIMIENTO' ? 'selected' : '' }}>Mantenimiento</option>
                        <option value="INFRAESTRUCTURA" {{ old('tipo_contrato', $contrato->tipo_contrato ?? '') == 'INFRAESTRUCTURA' ? 'selected' : '' }}>Infraestructura</option>
                        <option value="CONSULTORIA" {{ old('tipo_contrato', $contrato->tipo_contrato ?? '') == 'CONSULTORIA' ? 'selected' : '' }}>Consultoría</option>
                    </select>
                    @error('tipo_contrato')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <!-- Modalidad -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Modalidad *</label>
                    <select name="modalidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('modalidad') border-red-500 @enderror" required>
                        <option value="">Seleccione modalidad</option>
                        <option value="MENSUAL" {{ old('modalidad', $contrato->modalidad ?? '') == 'MENSUAL' ? 'selected' : '' }}>Mensual</option>
                        <option value="TRIMESTRAL" {{ old('modalidad', $contrato->modalidad ?? '') == 'TRIMESTRAL' ? 'selected' : '' }}>Trimestral</option>
                        <option value="SEMESTRAL" {{ old('modalidad', $contrato->modalidad ?? '') == 'SEMESTRAL' ? 'selected' : '' }}>Semestral</option>
                        <option value="ANUAL" {{ old('modalidad', $contrato->modalidad ?? '') == 'ANUAL' ? 'selected' : '' }}>Anual</option>
                    </select>
                    @error('modalidad')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Fechas y Valores -->
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4">Fechas y Valores</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Fecha de Inicio -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', isset($contrato) ? $contrato->fecha_inicio?->format('Y-m-d') ?? '' : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('fecha_inicio') border-red-500 @enderror" required>
                    @error('fecha_inicio')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <!-- Fecha de Fin -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Fin Prevista *</label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin', isset($contrato) ? $contrato->fecha_fin?->format('Y-m-d') ?? '' : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('fecha_fin') border-red-500 @enderror" required>
                    @error('fecha_fin')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <!-- Fecha de Firma -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Firma *</label>
                    <input type="date" name="fecha_firma" value="{{ old('fecha_firma', isset($contrato) ? $contrato->fecha_firma?->format('Y-m-d') ?? '' : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('fecha_firma') border-red-500 @enderror" required>
                    @error('fecha_firma')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <!-- Valor del Contrato -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Valor del Contrato *</label>
                    <input type="number" name="valor_contrato" value="{{ old('valor_contrato', $contrato->valor_contrato ?? 0) }}" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('valor_contrato') border-red-500 @enderror" required placeholder="0.00">
                    @error('valor_contrato')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <!-- Moneda -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Moneda *</label>
                    <select name="moneda" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('moneda') border-red-500 @enderror" required>
                        <option value="">Seleccione moneda</option>
                        <option value="COP" {{ old('moneda', $contrato->moneda ?? 'COP') == 'COP' ? 'selected' : '' }}>COP (Pesos Colombianos)</option>
                        <option value="USD" {{ old('moneda', $contrato->moneda ?? '') == 'USD' ? 'selected' : '' }}>USD (Dólares Estadounidenses)</option>
                        <option value="EUR" {{ old('moneda', $contrato->moneda ?? '') == 'EUR' ? 'selected' : '' }}>EUR (Euros)</option>
                    </select>
                    @error('moneda')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Detalles del Contrato -->
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4">Detalles del Contrato</h3>
            
            <!-- Alcance de Servicios -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alcance de Servicios</label>
                <textarea name="alcance_servicios" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('alcance_servicios') border-red-500 @enderror" placeholder="Descripción detallada del alcance...">{{ old('alcance_servicios', $contrato->alcance_servicios ?? '') }}</textarea>
                @error('alcance_servicios')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <!-- Condiciones de Pago -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Condiciones de Pago</label>
                <textarea name="condiciones_pago" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('condiciones_pago') border-red-500 @enderror" placeholder="Términos de pago...">{{ old('condiciones_pago', $contrato->condiciones_pago ?? '') }}</textarea>
                @error('condiciones_pago')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <!-- Cláusulas Especiales -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Cláusulas Especiales</label>
                <textarea name="clausulas_especiales" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('clausulas_especiales') border-red-500 @enderror" placeholder="Cláusulas especiales...">{{ old('clausulas_especiales', $contrato->clausulas_especiales ?? '') }}</textarea>
                @error('clausulas_especiales')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Estado y Opciones -->
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 border-b pb-4">Estado y Opciones</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Estado -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Estado del Contrato *</label>
                    <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('estado') border-red-500 @enderror" required>
                        <option value="">Seleccione estado</option>
                        <option value="BORRADOR" {{ old('estado', $contrato->estado ?? '') == 'BORRADOR' ? 'selected' : '' }}>Borrador</option>
                        <option value="ACTIVO" {{ old('estado', $contrato->estado ?? '') == 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                        <option value="VENCIDO" {{ old('estado', $contrato->estado ?? '') == 'VENCIDO' ? 'selected' : '' }}>Vencido</option>
                        <option value="TERMINADO" {{ old('estado', $contrato->estado ?? '') == 'TERMINADO' ? 'selected' : '' }}>Terminado</option>
                        <option value="RENOVADO" {{ old('estado', $contrato->estado ?? '') == 'RENOVADO' ? 'selected' : '' }}>Renovado</option>
                    </select>
                    @error('estado')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <!-- Documento Firmado -->
                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="documento_firmado" value="1" {{ old('documento_firmado', $contrato->documento_firmado ?? false) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-sm font-semibold text-gray-700">Documento Firmado</span>
                    </label>
                </div>

                <!-- Renovación Automática -->
                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="renovacion_automatica" value="1" {{ old('renovacion_automatica', $contrato->renovacion_automatica ?? false) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-sm font-semibold text-gray-700">Renovación Automática</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex gap-3 justify-end flex-wrap">
            <a href="{{ route('parametros.contratos.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-save"></i> {{ isset($contrato) ? 'Actualizar' : 'Crear' }}
            </button>
        </div>

        <!-- Errores Generales -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm font-semibold text-red-700 mb-2">Se encontraron errores:</p>
                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
</div>
@endsection
