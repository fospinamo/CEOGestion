<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CEOGESTION') - Sistema de Gestión Empresarial</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white shadow-xl overflow-y-auto">
            <div class="p-6 border-b border-blue-700">
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-building"></i> CEOGESTION
                </h1>
                <p class="text-sm text-blue-200 mt-1">Sistema de Gestión</p>
            </div>

            <nav class="mt-6 space-y-2 px-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>

                <div class="pt-4 pb-2">
                    <h3 class="px-4 py-2 text-xs font-bold text-blue-300 uppercase">Gestión Principal</h3>
                </div>

                <a href="{{ route('empresas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('empresas.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-industry w-5"></i>
                    <span>Empresas</span>
                </a>

                <a href="{{ route('sedes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('sedes.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-map-marker-alt w-5"></i>
                    <span>Sedes</span>
                </a>

                <a href="{{ route('usuarios.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('usuarios.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span>Usuarios</span>
                </a>

                <div class="pt-4 pb-2">
                    <h3 class="px-4 py-2 text-xs font-bold text-blue-300 uppercase">Ubicación DANE</h3>
                </div>

                <a href="{{ route('departamentos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('departamentos.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-map w-5"></i>
                    <span>Departamentos</span>
                </a>

                <a href="{{ route('municipios.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('municipios.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-city w-5"></i>
                    <span>Municipios</span>
                </a>

                <div class="pt-4 pb-2">
                    <h3 class="px-4 py-2 text-xs font-bold text-blue-300 uppercase">Gestión TI</h3>
                </div>

                <a href="{{ route('clientes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('clientes.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-handshake w-5"></i>
                    <span>Clientes</span>
                </a>

                <a href="{{ route('contratos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('contratos.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-file-contract w-5"></i>
                    <span>Contratos</span>
                </a>

                <a href="{{ route('areas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('areas.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-th w-5"></i>
                    <span>Áreas</span>
                </a>

                <a href="{{ route('equipos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('equipos.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-laptop w-5"></i>
                    <span>Equipos</span>
                </a>

                <a href="{{ route('servicios.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('servicios.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-tools w-5"></i>
                    <span>Servicios</span>
                </a>

                <a href="{{ route('tipos-equipos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('tipos-equipos.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-list w-5"></i>
                    <span>Tipos de Equipos</span>
                </a>

                <a href="{{ route('documentos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('documentos.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-file-upload w-5"></i>
                    <span>Documentos</span>
                </a>
            </nav>

            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-blue-700 bg-blue-900 w-64">
                @auth
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-blue-700">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=0D8ABC&color=fff" alt="Avatar" class="w-10 h-10 rounded-full">
                        <div class="text-sm">
                            <p class="font-semibold">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-blue-200">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-4 py-2 rounded-lg w-full hover:bg-blue-700 transition text-left text-sm">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                @endauth
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 px-8 py-4 shadow-sm sticky top-0">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-500 mt-1">@yield('page-description')</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name ?? 'Usuario' }}</p>
                            <p class="text-xs text-gray-500">Administrador</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-8">
                <!-- Success Alert -->
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-600"></i>
                            <span class="text-green-800 font-semibold">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <h3 class="text-red-800 font-bold mb-2 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i> Errores encontrados:
                        </h3>
                        <ul class="text-red-700 text-sm space-y-1 ml-7">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    @yield('scripts')
</body>
</html>
