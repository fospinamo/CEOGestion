@extends('layouts.app')

@section('title', $marca->nombre)
@section('page-title', $marca->nombre)
@section('page-description', 'Detalle de marca')

@section('content')
<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 space-y-6">
        <!-- Información General -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información General</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Nombre:</span>
                    <p class="font-semibold text-gray-900">{{ $marca->nombre }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Estado:</span>
                    <p class="font-semibold">
                        @if($marca->estado)
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                        @else
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                        @endif
                    </p>
                </div>
                @if($marca->descripcion)
                    <div class="col-span-2">
                        <span class="text-gray-600">Descripción:</span>
                        <p class="text-gray-900 mt-1">{{ $marca->descripcion }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Equipos Asociados -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-server text-blue-600 mr-2"></i>Equipos ({{ $marca->equipos_count ?? 0 }})
            </h3>
            
            @if($marca->equipos && $marca->equipos->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-2 text-left">Código Activo</th>
                                <th class="px-4 py-2 text-left">Modelo</th>
                                <th class="px-4 py-2 text-left">Serial</th>
                                <th class="px-4 py-2 text-left">Área</th>
                                <th class="px-4 py-2 text-center">Estado</th>
                                <th class="px-4 py-2 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($marca->equipos as $equipo)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        <a href="{{ route('parametros.equipos.show', $equipo) }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                                            {{ $equipo->codigo_activo_cliente }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2">{{ $equipo->modelo ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 font-mono text-xs">{{ $equipo->serial }}</td>
                                    <td class="px-4 py-2">{{ $equipo->area?->nombre ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 text-center">
                                        @if($equipo->estado_operativo === 'OPERATIVO')
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">OPERATIVO</span>
                                        @elseif($equipo->estado_operativo === 'MANTENIMIENTO')
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">MANT.</span>
                                        @else
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">{{ $equipo->estado_operativo }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <a href="{{ route('parametros.equipos.show', $equipo) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-8 text-center">
                    <i class="fas fa-inbox text-3xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">No hay equipos registrados con esta marca</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Acciones -->
        <div class="bg-white rounded-lg shadow p-6 space-y-2">
            <a href="{{ route('parametros.marcas.edit', $marca) }}" class="w-full block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            @if(($marca->equipos_count ?? 0) === 0)
                <form action="{{ route('parametros.marcas.destroy', $marca) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar esta marca?')" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-semibold">
                        <i class="fas fa-trash mr-2"></i>Eliminar
                    </button>
                </form>
            @endif
            <a href="{{ route('parametros.marcas.index') }}" class="w-full block text-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        <!-- Info Card -->
        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
            <h4 class="font-semibold text-blue-900 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Información
            </h4>
            <dl class="text-sm text-blue-800 space-y-2">
                <div>
                    <dt class="font-semibold">Creada:</dt>
                    <dd>{{ $marca->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="font-semibold">Última actualización:</dt>
                    <dd>{{ $marca->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
