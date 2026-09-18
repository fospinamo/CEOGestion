@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', 'Clases Documentales')
@section('page-description', 'Gestión de clases documentales')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Clases Documentales</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $clasesDocumentales->count() }} registros</p>
        </div>
        @can('clases_documentales.crear')
        <a href="{{ route('documentacion.parametros.clases_documentales.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nueva Clase Documental
        </a>
        @endcan
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Consecutivo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Descripción</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Versión</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Realizado por</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Fecha</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($clasesDocumentales as $clase)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $clase->consecutivo }}</td>
                        <td class="px-6 py-3 font-semibold text-gray-900">{{ $clase->descripcion }}</td>
                        <td class="px-6 py-3 text-center">
                            @if($clase->estado)
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Activo</span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $clase->version }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $clase->realizadoPor?->name ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $clase->created_at?->format('d/m/Y') ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('documentacion.parametros.clases_documentales.show', ['clase_documental' => $clase->id]) }}" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Ver</a>
                                @can('clases_documentales.editar')
                                <a href="{{ route('documentacion.parametros.clases_documentales.edit', ['clase_documental' => $clase->id]) }}" class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
                                @endcan
                                @can('clases_documentales.eliminar')
                                <form action="{{ route('documentacion.parametros.clases_documentales.destroy', ['clase_documental' => $clase->id]) }}" method="POST" onsubmit="return confirm('¿Eliminar esta clase documental?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200">Eliminar</button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500">No hay clases documentales registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
