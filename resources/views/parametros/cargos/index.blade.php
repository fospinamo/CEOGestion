@extends('layouts.app')
@section('title', 'Parámetros')
@section('page-title', 'Cargos')
@section('page-description', 'Gestión de cargos')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Cargos</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $cargos->count() }} registros</p>
        </div>
        @can('cargos.crear')
        <a href="{{ route('parametros.cargos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Cargo
        </a>
        @endcan
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Código</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Descripción</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Empresa</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($cargos as $cargo)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-sm font-mono text-gray-700">{{ $cargo->codigo }}</td>
                        <td class="px-6 py-3 font-semibold text-gray-900">{{ $cargo->descripcion }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $cargo->empresa?->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-center">
                            @if($cargo->estado)
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Activo</span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('parametros.cargos.show', ['cargo' => $cargo->id]) }}" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Ver</a>
                                @can('cargos.editar')
                                <a href="{{ route('parametros.cargos.edit', ['cargo' => $cargo->id]) }}" class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Editar</a>
                                @endcan
                                @can('cargos.eliminar')
                                <form action="{{ route('parametros.cargos.destroy', ['cargo' => $cargo->id]) }}" method="POST" onsubmit="return confirm('¿Eliminar este cargo?');">
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
                        <td colspan="5" class="px-6 py-6 text-center text-gray-500">No hay cargos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
