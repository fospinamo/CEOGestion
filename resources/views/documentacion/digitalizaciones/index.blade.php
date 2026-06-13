@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', 'Digitalizaciones')
@section('page-description', 'Gestion de digitalizaciones')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Digitalizaciones</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $digitalizaciones->count() }} digitalizaciones</p>
        </div>
        <a href="{{ route('documentacion.digitalizaciones.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nueva Digitalizacion
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaDigitalizaciones">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Titulo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Empresa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Sede</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Documento</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Fecha</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Usuario</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($digitalizaciones as $digitalizacion)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $digitalizacion->titulo ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $digitalizacion->empresa?->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $digitalizacion->sede?->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $digitalizacion->documento?->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $digitalizacion->fecha_documento?->format('Y-m-d') ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $digitalizacion->user?->name ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('documentacion.digitalizaciones.show', ['digitalizacion' => $digitalizacion->id]) }}" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Ver</a>
                                <a href="{{ route('documentacion.digitalizaciones.edit', ['digitalizacion' => $digitalizacion->id]) }}" class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
                                <form action="{{ route('documentacion.digitalizaciones.destroy', ['digitalizacion' => $digitalizacion->id]) }}" method="POST" onsubmit="return confirm('¿Eliminar esta digitalizacion?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500">No hay digitalizaciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
