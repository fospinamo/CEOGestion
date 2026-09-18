@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', 'Tablas de Retención Documental')
@section('page-description', 'Gestión de Tablas de Retención Documental (TRD)')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Tablas de Retención Documental</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $tablas->count() }} registros</p>
        </div>
        @can('trd.crear')
        <a href="{{ route('documentacion.tablas_retencion.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nueva TRD
        </a>
        @endcan
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Código</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Empresa</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Detalles</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aprobado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Convalidado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Creado por</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($tablas as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-sm text-gray-700 font-mono">{{ $item->consecutivo }}</td>
                        <td class="px-6 py-3 font-semibold text-gray-900">{{ $item->nombre }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $item->empresa?->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-center text-sm text-gray-700">{{ $item->detalles->count() }}</td>
                        <td class="px-6 py-3 text-center">
                            @if($item->aprobado_comite)
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Sí</span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Pendiente</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if($item->convalidado_agn)
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Sí</span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Pendiente</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if($item->estado)
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Activo</span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $item->usuarioCrea?->name ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('documentacion.tablas_retencion.show', ['tabla_retencion' => $item->id]) }}" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Ver</a>
                                @can('trd.editar')
                                <a href="{{ route('documentacion.tablas_retencion.edit', ['tabla_retencion' => $item->id]) }}" class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
                                @endcan
                                @can('trd.eliminar')
                                <form action="{{ route('documentacion.tablas_retencion.destroy', ['tabla_retencion' => $item->id]) }}" method="POST" onsubmit="return confirm('¿Eliminar esta TRD? Se perderán todos los registros asociados.');">
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
                        <td colspan="9" class="px-6 py-6 text-center text-gray-500">No hay Tablas de Retención Documental registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
