@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', 'Documentos')
@section('page-description', 'Gestion de documentos')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Documentos</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $documentos->count() }} documentos</p>
        </div>
        <a href="{{ route('documentacion.documentos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Documento
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full" id="tablaDocumentos">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Codigo</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Empresa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Sede</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Proceso</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Subproceso</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($documentos as $documento)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $documento->codigo }}</td>
                        <td class="px-6 py-3 font-semibold text-gray-900">{{ $documento->nombre }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $documento->empresa?->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $documento->sede?->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $documento->proceso?->proceso ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $documento->subproceso?->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('documentacion.documentos.show', ['documento' => $documento->id]) }}" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Ver</a>
                                <a href="{{ route('documentacion.documentos.edit', ['documento' => $documento->id]) }}" class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
                                <form action="{{ route('documentacion.documentos.destroy', ['documento' => $documento->id]) }}" method="POST" onsubmit="return confirm('¿Eliminar este documento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500">No hay documentos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
