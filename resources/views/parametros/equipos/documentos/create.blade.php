@extends('layouts.app')

@section('title', 'Cargar Documento')
@section('page-title', 'Cargar Documento')
@section('page-description', 'Agregar documento al equipo')

@section('content')

<div class="max-w-2xl mx-auto">
    <form action="{{ route('parametros.equipos.documentos.store', $equipo->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf

        <!-- Información del Equipo -->
        <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
            <p class="font-semibold text-gray-900">{{ $equipo->marca?->nombre }} {{ $equipo->modelo }}</p>
            <p class="text-sm text-gray-600">{{ $equipo->codigo_activo_cliente }}</p>
        </div>

        <!-- Tipo de Documento -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Documento *</label>
            <select name="tipo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('tipo') border-red-500 @enderror" required>
                <option value="">-- Selecciona tipo --</option>
                <option value="visual" {{ old('tipo') === 'visual' ? 'selected' : '' }}>📷 Visual del Equipo</option>
                <option value="hojas_vida" {{ old('tipo') === 'hojas_vida' ? 'selected' : '' }}>📄 Hojas de Vida</option>
                <option value="reportes_anexos" {{ old('tipo') === 'reportes_anexos' ? 'selected' : '' }}>📋 Reportes Anexos</option>
                <option value="facturas" {{ old('tipo') === 'facturas' ? 'selected' : '' }}>💰 Facturas</option>
                <option value="certificados" {{ old('tipo') === 'certificados' ? 'selected' : '' }}>✅ Certificados</option>
                <option value="actas" {{ old('tipo') === 'actas' ? 'selected' : '' }}>📑 Actas</option>
            </select>
            @error('tipo')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <!-- Archivo -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Archivo *</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition"
                 onclick="document.getElementById('archivo').click();">
                <input type="file" id="archivo" name="archivo" class="hidden" required accept="image/*,.pdf,.mp4,.mov,.avi" @change="fileName = $event.target.files[0]?.name">
                <div id="fileInfo">
                    <p class="text-gray-600">Arrastra archivo aquí o haz clic para seleccionar</p>
                    <p class="text-xs text-gray-500 mt-2">Soportados: JPG, PNG, PDF, MP4, MOV, AVI (máx 100MB)</p>
                </div>
            </div>
            @error('archivo')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <!-- Descripción -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción (Opcional)</label>
            <textarea name="descripcion" rows="3" placeholder="Descripción del documento" 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('descripcion') }}</textarea>
            @error('descripcion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('parametros.equipos.documentos.index', $equipo->id) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-semibold">
                Cargar Documento
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('archivo').addEventListener('change', function(e) {
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
