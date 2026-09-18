@extends('layouts.app')

@section('title', 'Formatos de Informe')
@section('page-title', 'Gestión de Formatos de Informe')
@section('page-description', 'Administra los formatos de informe técnico disponibles por empresa')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Formatos de Informe</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $formatos->count() }} formatos</p>
        </div>
        @can('informe-formatos.crear')
        <a href="{{ route('parametros.informe-formatos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Formato
        </a>
        @endcan
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaFormatos">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Código</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Template Blade</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Empresas</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($formatos as $formato)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="inline-block px-2 py-1 text-xs font-mono font-semibold rounded bg-gray-100 text-gray-800">{{ $formato->codigo }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $formato->nombre }}</p>
                            @if($formato->descripcion)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $formato->descripcion }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-mono text-purple-700 bg-purple-50 px-2 py-1 rounded">{{ $formato->blade_template }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $formato->empresas_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($formato->activo)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('parametros.informe-formatos.show', $formato) }}" class="px-3 py-1 text-sm bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('informe-formatos.editar')
                                <a href="{{ route('parametros.informe-formatos.edit', $formato) }}" class="px-3 py-1 text-sm bg-yellow-50 hover:bg-yellow-100 text-yellow-600 rounded-lg transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @if($formato->empresas_count === 0)
                                    @can('informe-formatos.eliminar')
                                    <form action="{{ route('parametros.informe-formatos.destroy', $formato) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este formato?')" class="px-3 py-1 text-sm bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-file-alt text-3xl mb-2 opacity-50"></i>
                            <p>No hay formatos de informe registrados</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#tablaFormatos').DataTable({
        "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json" },
        "responsive": true,
        "columnDefs": [{ "orderable": false, "targets": 5 }],
        "order": [[0, "asc"]],
        "pageLength": 10
    });
});
</script>
@endsection
