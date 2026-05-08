@extends('layouts.app')

@section('title', isset($categoria) ? 'Editar Categoría' : 'Crear Categoría')
@section('page-title', isset($categoria) ? 'Editar Categoría' : 'Nueva Categoría')
@section('page-description', isset($categoria) ? 'Actualizar información de la categoría' : 'Registrar una nueva categoría')

@section('content')
<div class="max-w-2xl">
    <form action="{{ isset($categoria) ? route('parametros.categorias.update', $categoria) : route('parametros.categorias.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @if(isset($categoria))
            @method('PUT')
        @endif

        <!-- Información Básica -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de la Categoría</h3>
            
            <div class="space-y-4">
                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $categoria->nombre ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nombre') border-red-500 @enderror" required placeholder="Ej: HARDWARE">
                    @error('nombre')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    <p class="text-xs text-gray-500 mt-1">Nombre único para identificar la categoría</p>
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('descripcion') border-red-500 @enderror" placeholder="Descripción detallada de la categoría">{{ old('descripcion', $categoria->descripcion ?? '') }}</textarea>
                    @error('descripcion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <!-- Color -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Color *</label>
                        <div class="flex gap-2">
                            <input type="color" name="color" value="{{ old('color', $categoria->color ?? '#3b82f6') }}" class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer">
                            <input type="text" id="colorInput" value="{{ old('color', $categoria->color ?? '#3b82f6') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('color') border-red-500 @enderror" placeholder="#3b82f6">
                        </div>
                        @error('color')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        <p class="text-xs text-gray-500 mt-1">Código hexadecimal del color (ej: #3b82f6)</p>
                    </div>

                    <!-- Icono -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Icono Font Awesome</label>
                        <div class="flex gap-2">
                            <input type="text" name="icono" value="{{ old('icono', $categoria->icono ?? '') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('icono') border-red-500 @enderror" placeholder="fa-desktop">
                            @if(old('icono', $categoria->icono ?? ''))
                                <div class="flex items-center px-4 py-2 bg-gray-100 rounded-lg">
                                    <i class="fas {{ old('icono', $category->icono ?? '') }} text-xl text-gray-600"></i>
                                </div>
                            @else
                                <div class="flex items-center px-4 py-2 bg-gray-100 rounded-lg text-gray-400">
                                    <i class="fas fa-icon text-xl"></i>
                                </div>
                            @endif
                        </div>
                        @error('icono')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                        <p class="text-xs text-gray-500 mt-1">Ej: fa-desktop, fa-database, fa-network-wired</p>
                    </div>
                </div>

                <!-- Estado -->
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="estado" value="1" {{ old('estado', $categoria->estado ?? true) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-sm font-semibold text-gray-700">Categoría Activa</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Una categoría inactiva no estará disponible para nuevos registros</p>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('parametros.categorias.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-times mr-2"></i> Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-save"></i> {{ isset($categoria) ? 'Actualizar' : 'Crear' }}
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

@section('scripts')
<script>
// Sincronizar color input y color picker
document.querySelector('input[name="color"]').addEventListener('change', function() {
    document.getElementById('colorInput').value = this.value;
});

document.getElementById('colorInput').addEventListener('change', function() {
    document.querySelector('input[name="color"]').value = this.value;
});
</script>
@endsection
