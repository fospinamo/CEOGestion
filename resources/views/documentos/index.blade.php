@extends('layouts.app')
@section('title', 'Documentos Adjuntos')
@section('page-title', 'Documentos')
@section('page-description', 'Gestión de archivos adjuntos')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Documentos Adjuntos</h2>
        <a href="{{ route('documentos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Subir Documento
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaDocumentos">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Archivo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Entidad</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Subido por</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Fecha</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($documentos as $documento)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            <p class="font-semibold text-gray-900">{{ $documento->nombre_archivo }}</p>
                            <p class="text-xs text-gray-500">{{ round($documento->tamaño_bytes / 1024, 2) }} KB</p>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $documento->tipo_documento }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-700">
                            @if($documento->entidad_type === 'App\\Models\\Contrato')
                                <a href="{{ route('contratos.show', $documento->entidad) }}" class="text-blue-600 hover:underline">
                                    {{ $documento->entidad->numero_contrato }}
                                </a>
                            @elseif($documento->entidad_type === 'App\\Models\\Servicio')
                                <a href="{{ route('incidencias.servicios.show', $documento->entidad) }}" class="text-blue-600 hover:underline">
                                    Servicio #{{ $documento->entidad->id }}
                                </a>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $documento->usuario->name ?? 'Sistema' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $documento->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('documentos.download', $documento) }}" class="text-blue-600 hover:text-blue-900" title="Descargar">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="{{ route('documentos.show', $documento) }}" class="text-green-600 hover:text-green-900" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('documentos.destroy', $documento) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-file text-3xl mb-2 opacity-50"></i>
                            <p>No hay documentos</p>
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
    $('#tablaDocumentos').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": 5 },
            { "searchable": false, "targets": 5 }
        ],
        "order": [[0, "asc"]],
        "pageLength": 10,
        "autoWidth": false
    });
});
</script>
@endsection
