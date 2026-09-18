@extends('layouts.app')
@section('title', 'Documentación')
@section('page-title', 'Detalle de Clase Documental')
@section('page-description', 'Información completa de la clase documental')
@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="max-w-3xl space-y-4">
    <div class="bg-white rounded-lg shadow p-6 space-y-3">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Clase Documental #{{ $claseDocumental->consecutivo }}</h2>
                <p class="text-sm text-gray-500">Creada: {{ $claseDocumental->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
            @if($claseDocumental->estado)
                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Activo</span>
            @else
                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Inactivo</span>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Descripción</p>
                <p class="font-semibold text-gray-900">{{ $claseDocumental->descripcion }}</p>
            </div>
            <div>
                <p class="text-gray-500">Versión</p>
                <p class="font-semibold text-gray-900">{{ $claseDocumental->version }}</p>
            </div>
            <div>
                <p class="text-gray-500">Realizado por</p>
                <p class="font-semibold text-gray-900">{{ $claseDocumental->realizadoPor?->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Registrado por</p>
                <p class="font-semibold text-gray-900">{{ $claseDocumental->registradoPor?->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Revisado por</p>
                <p class="font-semibold text-gray-900">{{ $claseDocumental->revisadoPor?->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Última actualización</p>
                <p class="font-semibold text-gray-900">{{ $claseDocumental->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            </div>
            @if($claseDocumental->observacion)
            <div class="md:col-span-2">
                <p class="text-gray-500">Observación</p>
                <p class="font-semibold text-gray-900">{{ $claseDocumental->observacion }}</p>
            </div>
            @endif
            @if($claseDocumental->imagen)
            <div class="md:col-span-2">
                <p class="text-gray-500 mb-2">Archivo adjunto</p>
                @if(in_array(pathinfo($claseDocumental->imagen, PATHINFO_EXTENSION), ['jpg','jpeg','png','gif','webp']))
                    <img src="{{ Storage::disk('private')->url($claseDocumental->imagen) }}" alt="Imagen" class="max-w-full max-h-64 rounded border">
                @else
                    <a href="{{ Storage::disk('private')->url($claseDocumental->imagen) }}" target="_blank" class="text-blue-600 underline">Ver archivo</a>
                @endif
            </div>
            @endif
        </div>
    </div>

    @if($claseDocumental->historiales->count())
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Historial de Versiones</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Código</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Versión</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Fecha Cambio</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Imagen</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($claseDocumental->historiales as $historial)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $historial->codigo }}</td>
                        <td class="px-4 py-2">{{ $historial->version }}</td>
                        <td class="px-4 py-2">{{ $historial->fecha_cambio?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            @if($historial->imagen)
                                @if(in_array(pathinfo($historial->imagen, PATHINFO_EXTENSION), ['jpg','jpeg','png','gif','webp']))
                                    <a href="{{ Storage::disk('private')->url($historial->imagen) }}" target="_blank" class="text-blue-600 underline">Ver imagen</a>
                                @else
                                    <a href="{{ Storage::disk('private')->url($historial->imagen) }}" target="_blank" class="text-blue-600 underline">Ver archivo</a>
                                @endif
                            @else
                                <span class="text-gray-400">Sin archivo</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="flex gap-3">
        @can('clases_documentales.editar')
        <a href="{{ route('documentacion.parametros.clases_documentales.edit', ['clase_documental' => $claseDocumental->id]) }}" class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
        @endcan
        <a href="{{ route('documentacion.parametros.clases_documentales.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Volver</a>
    </div>
</div>
@endsection
