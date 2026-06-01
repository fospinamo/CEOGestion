@extends('layouts.app')
@section('title', $proceso ? 'Editar Proceso' : 'Nuevo Proceso')
@section('page-title', $proceso ? 'Editar Proceso' : 'Nuevo Proceso')
@section('page-description', $proceso ? 'Actualizar proceso' : 'Crear nuevo proceso')
@section('content')

@if(isset($proceso) && !$proceso && request()->method() === 'PUT')
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <h2 class="text-lg font-bold text-red-800 mb-2">Error: Proceso no encontrado</h2>
        <p class="text-red-700 mb-4">No se pudo cargar el proceso para editar.</p>
        <a href="{{ route('parametros.procesos.index') }}" class="inline-block px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
            Volver a la lista
        </a>
    </div>
@else
<div class="max-w-3xl">
    <form action="{{ $proceso ? route('parametros.procesos.update', ['proceso' => $proceso->id]) : route('parametros.procesos.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @if($proceso) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Empresa *</label>
                <select name="empresa_id" id="empresa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('empresa_id') border-red-500 @enderror" required>
                    <option value="">-- Seleccione una empresa --</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}" {{ old('empresa_id', $proceso->empresa_id ?? '') == $empresa->id ? 'selected' : '' }}>
                            {{ $empresa->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('empresa_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sede *</label>
                <select name="sede_id" id="sede_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('sede_id') border-red-500 @enderror" required>
                    <option value="">-- Seleccione una sede --</option>
                    @foreach($sedes as $sede)
                        <option value="{{ $sede->id }}" data-empresa-id="{{ $sede->empresa_id }}" {{ old('sede_id', $proceso->sede_id ?? '') == $sede->id ? 'selected' : '' }}>
                            {{ $sede->nombre }} - {{ $sede->cliente?->razon_social ?? $sede->empresa?->nombre ?? 'Sin propietario' }}
                        </option>
                    @endforeach
                </select>
                @error('sede_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Proceso *</label>
                <input type="text" name="proceso" value="{{ old('proceso', $proceso->proceso ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('proceso') border-red-500 @enderror" required>
                @error('proceso')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Subprocesos *</label>
                <div id="subprocesos-container" class="space-y-3">
                    @php
                        $subprocesosOld = old('subprocesos');
                        $subprocesosValues = $subprocesosOld
                            ?? ($proceso?->subprocesos?->map(fn($item) => [
                                'nombre' => $item->nombre,
                                'ruta' => $item->ruta,
                                'estado' => $item->estado,
                            ])->toArray() ?? [
                                ['nombre' => '', 'ruta' => '', 'estado' => true]
                            ]);
                    @endphp
                    @foreach($subprocesosValues as $index => $subproceso)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 border border-gray-200 rounded-lg p-3 subproceso-row">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre</label>
                                <input type="text" name="subprocesos[{{ $index }}][nombre]" value="{{ $subproceso['nombre'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Ruta</label>
                                <input type="text" name="subprocesos[{{ $index }}][ruta]" value="{{ $subproceso['ruta'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="https://... o \\servidor\carpeta\" required>
                            </div>
                            <div class="flex items-center justify-between md:col-span-3">
                                <label class="text-xs font-semibold text-gray-600">
                                    <input type="checkbox" name="subprocesos[{{ $index }}][estado]" value="1" {{ ($subproceso['estado'] ?? true) ? 'checked' : '' }} class="rounded">
                                    Activo
                                </label>
                                <button type="button" class="text-xs text-red-600 hover:text-red-800 remove-subproceso">Quitar</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('subprocesos')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>
        </div>

        <div>
            <button type="button" id="add-subproceso" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                + Agregar subproceso
            </button>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <input type="checkbox" name="estado" value="1" {{ old('estado', $proceso->estado ?? true) ? 'checked' : '' }} class="rounded">
                Activo
            </label>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('parametros.procesos.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                {{ $proceso ? 'Actualizar' : 'Crear' }} Proceso
            </button>
        </div>
    </form>
</div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const empresaSelect = document.getElementById('empresa_id');
        const sedeSelect = document.getElementById('sede_id');
        const originalSedeOptions = Array.from(sedeSelect.options);

        function filterSedes() {
            const empresaId = empresaSelect.value;
            sedeSelect.innerHTML = '';

            originalSedeOptions.forEach(option => {
                if (option.value === '') {
                    sedeSelect.appendChild(option.cloneNode(true));
                    return;
                }

                if (!empresaId || option.dataset.empresaId === empresaId) {
                    sedeSelect.appendChild(option.cloneNode(true));
                }
            });
        }

        if (empresaSelect) {
            empresaSelect.addEventListener('change', function () {
                filterSedes();
                sedeSelect.value = '';
            });
            filterSedes();
        }

        const container = document.getElementById('subprocesos-container');
        const addButton = document.getElementById('add-subproceso');

        function refreshIndices() {
            const rows = container.querySelectorAll('.subproceso-row');
            rows.forEach((row, index) => {
                row.querySelectorAll('input').forEach(input => {
                    const name = input.getAttribute('name');
                    if (!name) return;
                    input.setAttribute('name', name.replace(/subprocesos\[\d+\]/, `subprocesos[${index}]`));
                });
            });
        }

        function addSubprocesoRow() {
            const index = container.querySelectorAll('.subproceso-row').length;
            const template = document.createElement('div');
            template.className = 'grid grid-cols-1 md:grid-cols-3 gap-3 border border-gray-200 rounded-lg p-3 subproceso-row';
            template.innerHTML = `
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre</label>
                    <input type="text" name="subprocesos[${index}][nombre]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Ruta</label>
                    <input type="text" name="subprocesos[${index}][ruta]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="https://... o \\servidor\\carpeta\\" required>
                </div>
                <div class="flex items-center justify-between md:col-span-3">
                    <label class="text-xs font-semibold text-gray-600">
                        <input type="checkbox" name="subprocesos[${index}][estado]" value="1" class="rounded" checked>
                        Activo
                    </label>
                    <button type="button" class="text-xs text-red-600 hover:text-red-800 remove-subproceso">Quitar</button>
                </div>
            `;
            container.appendChild(template);
        }

        if (addButton) {
            addButton.addEventListener('click', () => {
                addSubprocesoRow();
            });
        }

        container.addEventListener('click', function (event) {
            if (!event.target.classList.contains('remove-subproceso')) return;
            const rows = container.querySelectorAll('.subproceso-row');
            if (rows.length <= 1) return;
            event.target.closest('.subproceso-row')?.remove();
            refreshIndices();
        });
    });
</script>
@endsection
