@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', 'Detalle de TRD')
@section('page-description', $tabla->nombre)
@section('content')
<div class="space-y-6">
    {{-- Encabezado --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-gray-900">TRD #{{ $tabla->consecutivo }}</h2>
                <p class="text-sm text-gray-500">{{ $tabla->nombre }}</p>
            </div>
            <div class="flex gap-2">
                @if($tabla->aprobado_comite)
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Aprobado por Comité</span>
                @else
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Pendiente Aprobación</span>
                @endif
                @if($tabla->convalidado_agn)
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Convalidado AGN</span>
                @else
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Pendiente Convalidación</span>
                @endif
                @if($tabla->estado)
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Activo</span>
                @else
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Inactivo</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mt-4">
            <div>
                <p class="text-gray-500">Empresa</p>
                <p class="font-semibold text-gray-900">{{ $tabla->empresa?->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Creado por</p>
                <p class="font-semibold text-gray-900">{{ $tabla->usuarioCrea?->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Fecha de Creación</p>
                <p class="font-semibold text-gray-900">{{ $tabla->fecha_creacion?->format('d/m/Y') ?? 'N/A' }}</p>
            </div>
            @if($tabla->fecha_aprobacion)
            <div>
                <p class="text-gray-500">Fecha de Aprobación</p>
                <p class="font-semibold text-gray-900">{{ $tabla->fecha_aprobacion?->format('d/m/Y') ?? 'N/A' }}</p>
            </div>
            @endif
            <div>
                <p class="text-gray-500">Creado</p>
                <p class="font-semibold text-gray-900">{{ $tabla->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Última actualización</p>
                <p class="font-semibold text-gray-900">{{ $tabla->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
            @if($tabla->observacion)
            <div class="md:col-span-3">
                <p class="text-gray-500">Observaciones</p>
                <p class="font-semibold text-gray-900">{{ $tabla->observacion }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Detalles de la TRD --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-bold text-gray-900">Registros de la TRD ({{ $tabla->detalles->count() }} ítems)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Dependencia</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Serie</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Subserie</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Tipo Documental</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Archivo Gestión</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Archivo Central</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Disposición Final</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Observaciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($tabla->detalles as $detalle)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">{{ $detalle->orden }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $detalle->dependencia }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $detalle->serie }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $detalle->subserie ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $detalle->tipo_documental }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $detalle->archivo_gestion_tiempo }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $detalle->archivo_central_tiempo }}</td>
                            <td class="px-4 py-3">
                                @switch($detalle->disposicion_final)
                                    @case('CP')
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Conservación Permanente</span>
                                        @break
                                    @case('EL')
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Eliminación</span>
                                        @break
                                    @case('D')
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">Digitalización</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $detalle->observaciones ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-6 text-center text-gray-500">No hay registros en esta TRD.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex gap-3">
        @can('trd.editar')
        <a href="{{ route('documentacion.tablas_retencion.edit', ['tabla_retencion' => $tabla->id]) }}" class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
        @endcan
        <a href="{{ route('documentacion.tablas_retencion.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Volver</a>
    </div>
</div>
@endsection
