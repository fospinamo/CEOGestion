@extends('layouts.app')

@section('title', 'Departamentos')
@section('page-title', 'Departamentos de Colombia')
@section('page-description', 'Consulta los departamentos registrados en el sistema')

@section('content')
<div class="space-y-6">
    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaDepartamentos">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Código DANE</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">País</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Municipios</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departamentos as $departamento)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-700 font-mono">{{ $departamento->codigo_dane }}</td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $departamento->nombre }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $departamento->pais->nombre }}</td>
                        <td class="px-6 py-4 text-gray-700">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                {{ $departamento->municipios->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('administrativo.departamentos.show', $departamento) }}" class="text-blue-600 hover:text-blue-900 transition" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-inbox text-4xl opacity-30"></i>
                                <p>No hay departamentos registrados</p>
                            </div>
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
    $('#tablaDepartamentos').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": 4 }
        ],
        "order": [[0, "asc"]],
        "pageLength": 10,
        "paging": true
    });
});
</script>
@endsection
