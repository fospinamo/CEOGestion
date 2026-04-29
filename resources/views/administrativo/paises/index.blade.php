@extends('layouts.app')

@section('title', 'Países')
@section('page-title', 'Países')
@section('page-description', 'Gestión de países en el sistema')

@section('content')
<div class="space-y-6">
    <!-- Header with Create Button -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🌍 Países</h1>
            <p class="text-gray-600 mt-2">Administra los países registrados en el sistema</p>
        </div>
        <a href="{{ route('administrativo.paises.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            ➕ Nuevo País
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaPaises">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Código DANE</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Departamentos</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paises as $pais)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-700 font-mono">{{ $pais->codigo_dane }}</td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $pais->nombre }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                {{ $pais->departamentos->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('administrativo.paises.show', $pais) }}" class="text-blue-600 hover:text-blue-900 transition" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('administrativo.paises.edit', $pais) }}" class="text-yellow-600 hover:text-yellow-900 transition" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('administrativo.paises.destroy', $pais) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este país?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-inbox text-4xl opacity-30"></i>
                                <p>No hay países registrados</p>
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
    $('#tablaPaises').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": 3 }
        ],
        "order": [[0, "asc"]],
        "pageLength": 10,
        "paging": true
    });
});
</script>
@endsection
