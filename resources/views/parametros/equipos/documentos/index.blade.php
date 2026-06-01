@extends('layouts.app')

@section('title', 'Documentos del Equipo')
@section('page-title', 'Documentos - ' . $equipo->codigo_activo_cliente)
@section('page-description', 'Gestión de documentos del equipo TI')

@section('content')

<div class="max-w-6xl mx-auto">
    <!-- Información del Equipo -->
    <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
        <h3 class="font-semibold text-gray-900">{{ $equipo->marca?->nombre }} {{ $equipo->modelo }}</h3>
        <p class="text-sm text-gray-600">Serial: {{ $equipo->serial }} | Código: {{ $equipo->codigo_activo_cliente }}</p>
    </div>

    <!-- Botones de Acción -->
    <div class="mb-6 flex gap-3">
        <a href="{{ route('parametros.equipos.documentos.create', $equipo->id) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
            + Cargar Nuevo Documento
        </a>
        <a href="{{ route('parametros.equipos.show', $equipo->id) }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
            Volver a Equipo
        </a>
    </div>

    <!-- Documentos por Tipo -->
    @php
        $tipos = [
            'visual' => 'Visual del Equipo',
            'hojas_vida' => 'Hojas de Vida',
            'reportes_anexos' => 'Reportes Anexos',
            'facturas' => 'Facturas',
            'certificados' => 'Certificados',
            'actas' => 'Actas',
        ];
        $documentosPorTipo = $documentos->groupBy('tipo');
    @endphp

    @if ($documentos->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <p class="text-gray-700">No hay documentos cargados para este equipo.</p>
            <a href="{{ route('parametros.equipos.documentos.create', $equipo->id) }}" class="text-blue-600 hover:underline">Cargar el primer documento</a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach ($tipos as $tipoKey => $tipoLabel)
                @if ($documentosPorTipo->has($tipoKey))
                    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden shadow">
                        <div class="bg-gray-100 px-6 py-4 border-b border-gray-300">
                            <h3 class="font-semibold text-gray-900">{{ $tipoLabel }}</h3>
                            <span class="text-xs text-gray-600">({{ $documentosPorTipo[$tipoKey]->count() }} documento{{ $documentosPorTipo[$tipoKey]->count() !== 1 ? 's' : '' }})</span>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @foreach ($documentosPorTipo[$tipoKey] as $doc)
                                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            <span class="text-2xl">{{ $doc->icono }}</span>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $doc->nombre_original }}</p>
                                                <p class="text-sm text-gray-600">
                                                    {{ $doc->usuario?->name ?? 'Usuario desconocido' }} - 
                                                    {{ $doc->created_at->format('d/m/Y H:i') }}
                                                </p>
                                                @if ($doc->descripcion)
                                                    <p class="text-sm text-gray-700 mt-1 italic">{{ $doc->descripcion }}</p>
                                                @endif
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ number_format($doc->tamaño_bytes / 1024, 2) }} KB
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('parametros.equipos.documentos.download', [$equipo->id, $doc->id]) }}" 
                                           class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition">
                                            ⬇️ Descargar
                                        </a>
                                        <form action="{{ route('parametros.equipos.documentos.destroy', [$equipo->id, $doc->id]) }}" 
                                              method="POST" class="inline" onsubmit="return confirm('¿Está seguro?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

@endsection
