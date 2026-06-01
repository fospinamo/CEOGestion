@extends('layouts.app')

@section('title', 'Mantenimientos y Calibraciones')
@section('page-title', 'Mantenimientos & Calibraciones')
@section('page-description', 'Programación y registro de mantenimientos del equipo')

@section('content')

<div class="max-w-6xl mx-auto">
    <!-- Información del Equipo -->
    <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
        <h3 class="font-semibold text-gray-900">{{ $equipo->marca?->nombre }} {{ $equipo->modelo }}</h3>
        <p class="text-sm text-gray-600">Serial: {{ $equipo->serial }} | Código: {{ $equipo->codigo_activo_cliente }}</p>
        <div class="mt-3 grid grid-cols-4 gap-2 text-sm">
            <div>
                <p class="text-gray-600">Mantenimientos/año</p>
                <p class="font-semibold text-gray-900">{{ $equipo->mantenimientos_anuales ?? 1 }}</p>
            </div>
            <div>
                <p class="text-gray-600">Calibraciones/año</p>
                <p class="font-semibold text-gray-900">{{ $equipo->calibraciones_anuales ?? 0 }}</p>
            </div>
            <div>
                <p class="text-gray-600">Último Mantenimiento</p>
                <p class="font-semibold text-gray-900">{{ $equipo->fecha_ultimo_mantenimiento?->format('d/m/Y') ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-600">Próximo Mantenimiento</p>
                <p class="font-semibold {{ $equipo->proxima_fecha_mantenimiento?->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                    {{ $equipo->proxima_fecha_mantenimiento?->format('d/m/Y') ?? 'N/A' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="mb-6 flex gap-3">
        <a href="{{ route('parametros.equipos.mantenimientos.create', $equipo->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
            + Programar Mantenimiento
        </a>
        <a href="{{ route('parametros.equipos.show', $equipo->id) }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
            Volver
        </a>
    </div>

    <!-- Tabla de Mantenimientos -->
    @if ($items->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <p class="text-gray-700">No hay mantenimientos programados.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b border-gray-300">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipo</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Programado</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Realizado</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Reporte</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-sm font-semibold 
                                    {{ $item->tipo === 'mantenimiento' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ ucfirst($item->tipo) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $item->fecha_programada->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $item->fecha_realizada?->format('d/m/Y') ?? '---' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ $item->numero_reporte ?? '---' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($item->estado === 'programado')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Programado</span>
                                @elseif ($item->estado === 'realizado')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Realizado</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Cancelado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-sm space-x-2">
                                @if ($item->estado === 'programado')
                                    <a href="{{ route('parametros.equipos.mantenimientos.registrar', [$equipo->id, $item->id]) }}" 
                                       class="text-green-600 hover:text-green-800 font-semibold">
                                        Registrar
                                    </a>
                                @endif
                                
                                @if ($item->archivo_pdf_path)
                                    <a href="{{ route('parametros.equipos.mantenimientos.descargar', [$equipo->id, $item->id]) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        📄
                                    </a>
                                @endif
                                
                                @if ($item->estado === 'programado')
                                    <form action="{{ route('parametros.equipos.mantenimientos.destroy', [$equipo->id, $item->id]) }}" 
                                          method="POST" class="inline" onsubmit="return confirm('¿Está seguro?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            🗑️
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $items->links() }}
        </div>
    @endif
</div>

@endsection
