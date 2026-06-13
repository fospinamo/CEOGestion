@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', 'Radicaciones')
@section('page-description', 'Gestion de radicaciones')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Radicaciones</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $radicaciones->count() }} radicaciones</p>
        </div>
        <a href="{{ route('documentacion.radicaciones.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nueva Radicacion
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaRadicaciones">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Numero</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Fecha</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Empresa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Sede</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Documento</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($radicaciones as $radicacion)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $radicacion->numero }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $radicacion->fecha_radicacion?->format('Y-m-d') }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $radicacion->empresa?->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $radicacion->sede?->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $radicacion->documento?->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $radicacion->estado }}</td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('documentacion.radicaciones.show', ['radicacion' => $radicacion->id]) }}" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Ver</a>
                                <a href="{{ route('documentacion.radicaciones.edit', ['radicacion' => $radicacion->id]) }}" class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
                                <form action="{{ route('documentacion.radicaciones.destroy', ['radicacion' => $radicacion->id]) }}" method="POST" onsubmit="return confirm('¿Eliminar esta radicacion?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500">No hay radicaciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
