<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- 
        Viewport Meta Tag: Configura la escala y el comportamiento responsive
        width=device-width: Usa el ancho del dispositivo
        initial-scale=1.0: Zoom inicial 100%
        maximum-scale=5.0: Permite zoom hasta 5x
        user-scalable=yes: Permite que el usuario amplíe/reduzca
    -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>@yield('title', 'CEOGESTION') - Sistema de Gestión Empresarial</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        /* 
            Estilos base responsivos para mejor UX en móvil
            - Reduce tamaños de fuente en pantallas pequeñas
            - Optimiza espaciado para dedo humano (mínimo 44px)
            - Mejora legibilidad en pantallas pequeñas
        */
        @media (max-width: 768px) {
            body {
                font-size: 14px;
            }
            h1 { font-size: 1.5rem !important; }
            h2 { font-size: 1.25rem !important; }
            h3 { font-size: 1.1rem !important; }
            p { font-size: 0.95rem !important; }
            
            /* Asegurar que los botones sean clickeables (mínimo 44x44px) */
            button, a.btn, [role="button"] {
                min-height: 44px;
                padding: 10px 12px !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Overlay Móvil para Sidebar -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <div class="flex h-screen">
        <!-- Sidebar - Responsive (oculto en móvil, visible en tablet+) -->
        <aside id="sidebar" class="fixed md:static inset-y-0 left-0 w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white shadow-xl overflow-y-auto max-h-screen flex flex-col z-40 -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="p-6 border-b border-blue-700">
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-building"></i> CEOGESTION
                </h1>
                <p class="text-sm text-blue-200 mt-1">Sistema de Gestión</p>
            </div>

            <nav class="mt-6 space-y-2 px-3 flex-1">
                {{-- MENÚ ESPECIAL PARA TÉCNICO: Solo Mis Servicios --}}
                @if(auth()->check() && auth()->user()->tipo_rol === 'tecnico')
                    <a href="{{ route('servicios.technician-panel') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('servicios.technician-panel') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-tasks w-5"></i>
                        <span>Mis Servicios</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Dashboard</span>
                    </a>
                @else
                    {{-- MENÚ COMPLETO PARA OTROS ROLES --}}
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

                    <a href="{{ route('servicios.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('servicios.*') && !request()->routeIs('servicios.technician-panel') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-tools w-5"></i>
                        <span>Servicios</span>
                    </a>

                    {{-- Panel Admin para Servicios Asignados --}}
                    <a href="{{ route('servicios.admin-panel') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('servicios.admin-panel') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-tasks w-5"></i>
                        <span>Servicios Asignados</span>
                    </a>

                    <a href="{{ route('tipos-equipos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('tipos-equipos.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-list w-5"></i>
                        <span>Tipos de Equipos</span>
                    </a>

                    <a href="{{ route('documentos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('documentos.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-file-upload w-5"></i>
                        <span>Documentos</span>
                    </a>
                @endif
            </nav>

            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-blue-700 bg-blue-900 w-full">
                @auth
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-blue-700">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=0D8ABC&color=fff" alt="Avatar" class="w-10 h-10 rounded-full">
                        <div class="text-sm min-w-0">
                            <p class="font-semibold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-blue-200 truncate">{{ auth()->user()->email }}</p>
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
        <main class="flex-1 flex flex-col overflow-auto w-full">
            <!-- Top Bar - Responsive -->
            <header class="bg-white border-b border-gray-200 px-4 sm:px-6 md:px-8 py-4 shadow-sm sticky top-0 z-20">
                <div class="flex justify-between items-center gap-4">
                    <!-- Hamburguesa en móvil -->
                    <button id="sidebarToggle" onclick="toggleSidebar()" class="md:hidden flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-bars text-gray-700 text-lg"></i>
                    </button>
                    
                    <!-- Encabezado -->
                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 truncate">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1 truncate">@yield('page-description')</p>
                    </div>
                    
                    <!-- Info Usuario (oculta en móvil) -->
                    <div class="flex items-center gap-4">
                        <div class="text-right hidden sm:block">
                            @auth
                                <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name ?? 'Usuario' }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->tipo_rol ?? 'usuario') }}</p>
                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content - Responsive -->
            <div class="p-4 sm:p-6 md:p-8 overflow-auto flex-1">
                <!-- Success Alert -->
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex justify-between items-center">
                        <div class="flex items-center gap-3 min-w-0">
                            <i class="fas fa-check-circle text-green-600 flex-shrink-0"></i>
                            <span class="text-green-800 font-semibold truncate">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-800 flex-shrink-0">
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
    
    <script>
        // Función auxiliar para cerrar sidebar
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
        
        // Función auxiliar para abrir sidebar
        function openSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }
        
        // Toggle Sidebar para móvil
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        }
        
        // Cerrar sidebar al hacer click en un enlace
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                // Solo cerrar en pantallas pequeñas (menos de 768px)
                if (window.innerWidth < 768) {
                    closeSidebar();
                }
            });
        });
        
        // Cerrar sidebar cuando se redimensiona a desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                document.getElementById('sidebarOverlay').classList.add('hidden');
            }
        });
        
        // Cerrar sidebar automáticamente en ciertas páginas (móvil solamente)
        document.addEventListener('DOMContentLoaded', function() {
            // Rutas donde queremos cerrar el sidebar automáticamente
            const autoClosePaths = [
                '/servicios/report-technician',
                '/servicios/show',
                '/servicios-panel/tecnico'
            ];
            
            const currentPath = window.location.pathname;
            const shouldClose = autoClosePaths.some(path => currentPath.includes(path));
            
            if (shouldClose && window.innerWidth < 768) {
                closeSidebar();
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
