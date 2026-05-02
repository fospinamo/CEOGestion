@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Bienvenido al sistema de gestión CEOGESTION')

@section('content')
<div class="space-y-6">
    <!-- Panel rápido del técnico -->
    @if(auth()->check() && auth()->user()->tipo_rol === 'tecnico')
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-8 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold mb-2">👨‍🔧 Mis Servicios Asignados</h2>
                <p class="text-green-100">Accede rápidamente a tus servicios pendientes de atender</p>
            </div>
            <a href="{{ route('incidencias.servicios.technician-panel') }}" 
                class="bg-white text-green-600 font-bold py-3 px-8 rounded-lg hover:bg-green-50 transition transform hover:scale-105">
                Ir a Mi Panel →
            </a>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Empresas</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Empresa::count() }}</p>
                </div>
                <div class="text-4xl text-blue-600 opacity-20">
                    <i class="fas fa-industry"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Sedes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Sede::count() }}</p>
                </div>
                <div class="text-4xl text-green-600 opacity-20">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Usuarios</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                </div>
                <div class="text-4xl text-purple-600 opacity-20">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Municipios</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Municipio::count() }}</p>
                </div>
                <div class="text-4xl text-orange-600 opacity-20">
                    <i class="fas fa-city"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Gestión Rápida</h3>
            <div class="space-y-2">
                <a href="{{ route('parametros.empresas.create') }}" class="block px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Nueva Empresa
                </a>
                <a href="{{ route('parametros.sedes.create') }}" class="block px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Nueva Sede
                </a>
                @if(auth()->user()->hasPermission('usuarios.crear'))
                    <a href="{{ route('seguridad.usuarios.create') }}" class="block px-4 py-2 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Nuevo Usuario
                    </a>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Accesos Directos</h3>
            <div class="space-y-2">
                <a href="{{ route('parametros.empresas.index') }}" class="block px-4 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition flex items-center gap-2 justify-between">
                    <span>Ver Empresas</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="{{ route('parametros.sedes.index') }}" class="block px-4 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition flex items-center gap-2 justify-between">
                    <span>Ver Sedes</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
                @if(auth()->user()->hasPermission('usuarios.ver'))
                    <a href="{{ route('seguridad.usuarios.index') }}" class="block px-4 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition flex items-center gap-2 justify-between">
                        <span>Ver Usuarios</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Últimas Empresas</h3>
            <div class="space-y-3">
                @forelse(\App\Models\Empresa::latest()->limit(5)->get() as $empresa)
                    <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <p class="font-semibold text-gray-900">{{ $empresa->nombre }}</p>
                        <p class="text-sm text-gray-600">{{ $empresa->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No hay empresas registradas</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Últimos Usuarios</h3>
            <div class="space-y-3">
                @forelse(\App\Models\User::latest()->limit(5)->get() as $usuario)
                    <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ $usuario->name }}&background=0D8ABC&color=fff" alt="{{ $usuario->name }}" class="w-6 h-6 rounded-full">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">{{ $usuario->name }}</p>
                                <p class="text-xs text-gray-600">{{ $usuario->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No hay usuarios registrados</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Help Card -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow p-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-lg font-bold mb-2">¿Necesitas Ayuda?</h3>
                <p class="opacity-90">Consulta la documentación o contacta al equipo de soporte para obtener asistencia.</p>
            </div>
            <i class="fas fa-life-ring text-3xl opacity-30"></i>
        </div>
    </div>
</div>
@endsection
