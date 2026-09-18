@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', $tabla ? 'Editar TRD' : 'Nueva TRD')
@section('page-description', $tabla ? 'Actualizar Tabla de Retención Documental' : 'Crear nueva Tabla de Retención Documental')
@section('content')
@php
    $isEdit = isset($tabla) && $tabla;
    $formRoute = $isEdit
        ? route('documentacion.tablas_retencion.update', ['tabla_retencion' => $tabla->id])
        : route('documentacion.tablas_retencion.store');
@endphp
<div class="max-w-6xl space-y-6">
    <form action="{{ $formRoute }}" method="POST" id="trdForm" class="space-y-6">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        {{-- Encabezado de la TRD --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Información General</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Empresa *</label>
                    <select name="empresa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('empresa_id') border-red-500 @enderror" required>
                        <option value="">-- Seleccione --</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ old('empresa_id', $isEdit ? $tabla->empresa_id : '') == $empresa->id ? 'selected' : '' }}>
                                {{ $empresa->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('empresa_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre / Título *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $isEdit ? $tabla->nombre : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('nombre') border-red-500 @enderror" required placeholder="Ej: TRD General 2026">
                    @error('nombre')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Creación *</label>
                    <input type="date" name="fecha_creacion" value="{{ old('fecha_creacion', isset($tabla) && $tabla ? $tabla->fecha_creacion?->format('Y-m-d') : date('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('fecha_creacion') border-red-500 @enderror" required>
                    @error('fecha_creacion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción / Alcance</label>
                    <textarea name="descripcion" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('descripcion') border-red-500 @enderror" placeholder="Describa el alcance de esta TRD">{{ old('descripcion', $isEdit ? $tabla->descripcion : '') }}</textarea>
                    @error('descripcion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Creado por *</label>
                    <select name="usuario_crea_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('usuario_crea_id') border-red-500 @enderror" required>
                        <option value="">-- Seleccione --</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ old('usuario_crea_id', $isEdit ? $tabla->usuario_crea_id : auth()->id()) == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('usuario_crea_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                @if($isEdit)
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Estado *</label>
                    <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('estado') border-red-500 @enderror" required>
                        <option value="1" {{ old('estado', $tabla->estado ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado', $tabla->estado ?? 0) == 0 ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Aprobado por Comité</label>
                    <select name="aprobado_comite" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="0" {{ old('aprobado_comite', $tabla->aprobado_comite ?? 0) == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('aprobado_comite', $tabla->aprobado_comite ?? 0) == 1 ? 'selected' : '' }}>Sí</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Convalidado AGN</label>
                    <select name="convalidado_agn" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="0" {{ old('convalidado_agn', $tabla->convalidado_agn ?? 0) == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('convalidado_agn', $tabla->convalidado_agn ?? 0) == 1 ? 'selected' : '' }}>Sí</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Aprobación</label>
                    <input type="date" name="fecha_aprobacion" value="{{ old('fecha_aprobacion', $tabla->fecha_aprobacion?->format('Y-m-d') ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                @endif
                <div class="md:col-span-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Observaciones</label>
                    <textarea name="observacion" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('observacion') border-red-500 @enderror">{{ old('observacion', $isEdit ? $tabla->observacion : '') }}</textarea>
                    @error('observacion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- Detalles de la TRD --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Registros de la TRD</h3>
                <button type="button" onclick="addRow()" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm flex items-center gap-2">
                    <i class="fas fa-plus"></i> Agregar Registro
                </button>
            </div>

            <div id="error-detalles" class="hidden mb-4 p-3 bg-red-50 border border-red-300 rounded-lg text-red-700 text-sm">
                Debe agregar al menos un registro a la TRD.
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="detallesTable">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700 w-12">#</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Dependencia *</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Serie *</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Subserie</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Tipo Documental *</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Archivo Gestión *</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Archivo Central *</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Disposición Final *</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Observaciones</th>
                            <th class="px-3 py-2 text-center font-semibold text-gray-700 w-16">Quitar</th>
                        </tr>
                    </thead>
                    <tbody id="detallesBody" class="divide-y">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('documentacion.tablas_retencion.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $isEdit ? 'Actualizar' : 'Crear' }} TRD
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let rowCounter = 0;
    const disposiciones = {
        'CP': 'Conservación Permanente',
        'EL': 'Eliminación',
        'D': 'Digitalización'
    };

    function addRow(data = {}) {
        rowCounter++;
        const tbody = document.getElementById('detallesBody');
        const row = document.createElement('tr');
        row.id = `row-${rowCounter}`;
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="px-3 py-2 text-gray-500 align-top pt-3">${rowCounter}</td>
            <td class="px-3 py-2">
                <input type="text" name="detalles[${rowCounter}][dependencia]" value="${escapeHtml(data.dependencia || '')}" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm @error('detalles.0.dependencia') border-red-500 @enderror" required placeholder="Ej: Gestión Financiera">
            </td>
            <td class="px-3 py-2">
                <input type="text" name="detalles[${rowCounter}][serie]" value="${escapeHtml(data.serie || '')}" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm" required placeholder="Ej: Presupuesto">
            </td>
            <td class="px-3 py-2">
                <input type="text" name="detalles[${rowCounter}][subserie]" value="${escapeHtml(data.subserie || '')}" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm" placeholder="Ej: Formulación">
            </td>
            <td class="px-3 py-2">
                <input type="text" name="detalles[${rowCounter}][tipo_documental]" value="${escapeHtml(data.tipo_documental || '')}" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm" required placeholder="Ej: Proyecto de Presupuesto">
            </td>
            <td class="px-3 py-2">
                <input type="text" name="detalles[${rowCounter}][archivo_gestion_tiempo]" value="${escapeHtml(data.archivo_gestion_tiempo || '')}" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm" required placeholder="Ej: 5 años">
            </td>
            <td class="px-3 py-2">
                <input type="text" name="detalles[${rowCounter}][archivo_central_tiempo]" value="${escapeHtml(data.archivo_central_tiempo || '')}" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm" required placeholder="Ej: 10 años">
            </td>
            <td class="px-3 py-2">
                <select name="detalles[${rowCounter}][disposicion_final]" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm" required>
                    <option value="">--</option>
                    ${Object.entries(disposiciones).map(([key, label]) =>
                        `<option value="${key}" ${data.disposicion_final === key ? 'selected' : ''}>${label}</option>`
                    ).join('')}
                </select>
            </td>
            <td class="px-3 py-2">
                <input type="text" name="detalles[${rowCounter}][observaciones]" value="${escapeHtml(data.observaciones || '')}" class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm" placeholder="Normativa">
            </td>
            <td class="px-3 py-2 text-center align-top pt-2">
                <button type="button" onclick="removeRow('row-${rowCounter}')" class="text-red-500 hover:text-red-700 text-lg" title="Quitar registro">
                    <i class="fas fa-times-circle"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        updateNumbers();
    }

    function removeRow(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            row.remove();
            updateNumbers();
        }
    }

    function updateNumbers() {
        const rows = document.querySelectorAll('#detallesBody tr');
        rows.forEach((row, index) => {
            row.querySelector('td:first-child').textContent = index + 1;
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML.replace(/"/g, '&quot;');
    }

    document.getElementById('trdForm').addEventListener('submit', function(e) {
        const rows = document.querySelectorAll('#detallesBody tr');
        const errorDiv = document.getElementById('error-detalles');
        if (rows.length === 0) {
            e.preventDefault();
            errorDiv.classList.remove('hidden');
            return;
        }
        errorDiv.classList.add('hidden');
    });

    @if($isEdit)
        @foreach($tabla->detalles->sortBy('orden') as $detalle)
            addRow({
                dependencia: @json($detalle->dependencia),
                serie: @json($detalle->serie),
                subserie: @json($detalle->subserie),
                tipo_documental: @json($detalle->tipo_documental),
                archivo_gestion_tiempo: @json($detalle->archivo_gestion_tiempo),
                archivo_central_tiempo: @json($detalle->archivo_central_tiempo),
                disposicion_final: @json($detalle->disposicion_final),
                observaciones: @json($detalle->observaciones)
            });
        @endforeach
    @endif
</script>
@endsection
