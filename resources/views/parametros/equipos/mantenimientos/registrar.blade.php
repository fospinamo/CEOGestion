@extends('layouts.app')

@section('title', 'Registrar Mantenimiento Realizado')
@section('page-title', 'Registrar Realización')
@section('page-description', 'Documentar que el mantenimiento fue realizado')

@section('content')

<div class="max-w-2xl mx-auto">
    <form action="{{ route('parametros.equipos.mantenimientos.guardarRealizacion', [$equipo->id, $mantenimiento->id]) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf @method('PUT')

        <!-- Información del Equipo -->
        <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
            <p class="font-semibold text-gray-900">{{ $equipo->marca?->nombre }} {{ $equipo->modelo }}</p>
            <p class="text-sm text-gray-600">{{ $equipo->codigo_activo_cliente }}</p>
            <p class="text-sm text-gray-600 mt-2">
                <strong>Tipo:</strong> {{ ucfirst($mantenimiento->tipo) }} | 
                <strong>Programado:</strong> {{ $mantenimiento->fecha_programada->format('d/m/Y') }}
            </p>
        </div>

        <!-- Fecha Realizada -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Realización *</label>
            <input type="date" name="fecha_realizada" value="{{ old('fecha_realizada', now()->format('Y-m-d')) }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('fecha_realizada') border-red-500 @enderror" required>
            @error('fecha_realizada')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <!-- Número de Reporte -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Número de Reporte/OT *</label>
            <input type="text" name="numero_reporte" value="{{ old('numero_reporte', $mantenimiento->numero_reporte) }}" 
                   placeholder="Ej: MTN-2026-001" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('numero_reporte') border-red-500 @enderror" required>
            <p class="text-gray-500 text-xs mt-1">Número único de este reporte de mantenimiento</p>
            @error('numero_reporte')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <!-- Descripción del Trabajo -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción del Trabajo Realizado</label>
            <textarea name="descripcion_trabajo" rows="3" 
                      placeholder="Detalla qué se realizó..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('descripcion_trabajo', $mantenimiento->descripcion_trabajo) }}</textarea>
        </div>

        <!-- Técnico Responsable -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Técnico Responsable</label>
            <input type="text" name="tecnico_responsable" value="{{ old('tecnico_responsable', $mantenimiento->tecnico_responsable) }}" 
                   placeholder="Nombre del técnico que realizó" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
        </div>

        <!-- Empresa Tercero -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Empresa Responsable</label>
            <input type="text" name="empresa_tercero" value="{{ old('empresa_tercero', $mantenimiento->empresa_tercero) }}" 
                   placeholder="Empresa que realizó el servicio" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
        </div>

        <!-- Costo -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Costo (Opcional)</label>
            <input type="number" name="costo" step="0.01" value="{{ old('costo', $mantenimiento->costo) }}" 
                   placeholder="0.00" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
        </div>

        <!-- Archivo PDF -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Archivo PDF del Reporte (Opcional)</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition"
                 onclick="document.getElementById('archivo_pdf').click();">
                <input type="file" id="archivo_pdf" name="archivo_pdf" class="hidden" accept=".pdf">
                <div id="fileInfo">
                    <p class="text-gray-600">Arrastra PDF aquí o haz clic</p>
                    <p class="text-xs text-gray-500 mt-2">Solo archivos PDF (máx 10MB)</p>
                </div>
            </div>
            @error('archivo_pdf')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('parametros.equipos.mantenimientos.index', $equipo->id) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-semibold">
                Registrar Realización
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('archivo_pdf').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || '';
        const fileSize = e.target.files[0]?.size ? (e.target.files[0].size / 1024 / 1024).toFixed(2) + ' MB' : '';
        
        const fileInfo = document.getElementById('fileInfo');
        if (fileName) {
            fileInfo.innerHTML = `
                <p class="text-green-600 font-semibold">✓ ${fileName}</p>
                <p class="text-xs text-gray-500 mt-2">${fileSize}</p>
            `;
        }
    });
</script>

@endsection
