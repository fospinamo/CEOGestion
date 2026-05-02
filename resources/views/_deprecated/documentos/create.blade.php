@extends('layouts.app')
@section('title', 'Subir Documento')
@section('page-title', 'Subir Nuevo Documento')
@section('page-description', 'Adjuntar archivo a entidad')
@section('content')
<div class="max-w-2xl">
    <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Entidad *</label>
                <select name="entidad_type" id="entidadType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('entidad_type') border-red-500 @enderror" required>
                    <option value="">Seleccione tipo</option>
                    <option value="App\Models\Contrato" {{ old('entidad_type') == 'App\\Models\\Contrato' ? 'selected' : '' }}>Contrato</option>
                    <option value="App\Models\Servicio" {{ old('entidad_type') == 'App\\Models\\Servicio' ? 'selected' : '' }}>Servicio</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Entidad *</label>
                <select name="entidad_id" id="entidadId" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('entidad_id') border-red-500 @enderror" required>
                    <option value="">Seleccione entidad</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Documento *</label>
            <select name="tipo_documento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('tipo_documento') border-red-500 @enderror" required>
                <option value="">Seleccione tipo</option>
                <option value="CONTRATO" {{ old('tipo_documento') == 'CONTRATO' ? 'selected' : '' }}>Contrato</option>
                <option value="FACTURA" {{ old('tipo_documento') == 'FACTURA' ? 'selected' : '' }}>Factura</option>
                <option value="REPORTE" {{ old('tipo_documento') == 'REPORTE' ? 'selected' : '' }}>Reporte</option>
                <option value="EVIDENCIA" {{ old('tipo_documento') == 'EVIDENCIA' ? 'selected' : '' }}>Evidencia</option>
                <option value="OTRO" {{ old('tipo_documento') == 'OTRO' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Archivo *</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-400 transition" id="dropZone">
                <input type="file" name="archivo" id="archivo" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" required>
                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                <p class="text-sm text-gray-600">Arrastra archivos aquí o haz clic para seleccionar</p>
                <p class="text-xs text-gray-500 mt-1">Máximo 10 MB (PDF, Word, Excel, Imágenes)</p>
            </div>
            <p id="fileName" class="text-sm text-gray-600 mt-2"></p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
            <textarea name="descripcion" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Información sobre el documento">{{ old('descripcion') }}</textarea>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('documentos.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                <i class="fas fa-upload mr-2"></i> Subir Documento
            </button>
        </div>
    </form>
</div>

<script>
const dropZone = document.getElementById('dropZone');
const archivoInput = document.getElementById('archivo');
const fileName = document.getElementById('fileName');
const entidadTypeSelect = document.getElementById('entidadType');
const entidadIdSelect = document.getElementById('entidadId');

// Drag and drop
dropZone.addEventListener('click', () => archivoInput.click());
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-blue-400', 'bg-blue-50');
});
dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
});
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        archivoInput.files = files;
        updateFileName();
    }
});

archivoInput.addEventListener('change', updateFileName);

function updateFileName() {
    if (archivoInput.files.length > 0) {
        fileName.textContent = '✓ ' + archivoInput.files[0].name;
    }
}

// Cargar entidades según tipo seleccionado
entidadTypeSelect.addEventListener('change', async function() {
    const type = this.value;
    entidadIdSelect.innerHTML = '<option value="">Seleccione entidad</option>';
    
    if (!type) return;
    
    try {
        const response = await fetch(`/api/entidades?type=${encodeURIComponent(type)}`);
        const data = await response.json();
        
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.nombre;
            entidadIdSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error cargando entidades:', error);
    }
});
</script>
@endsection
