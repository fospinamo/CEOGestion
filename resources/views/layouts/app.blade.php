<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>@yield('title', 'CEOGESTION') - Sistema de Gestión Empresarial</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        @media (max-width: 768px) {
            body {
                font-size: 14px;
            }
            h1 { font-size: 1.5rem !important; }
            h2 { font-size: 1.25rem !important; }
            h3 { font-size: 1.1rem !important; }
            p { font-size: 0.95rem !important; }
            
            button, a.btn, [role="button"] {
                min-height: 44px;
                padding: 10px 12px !important;
            }
        }
        
        /* Estilos CSS personalizados para el sidebar (más confiables que Tailwind dinámico) */
        #sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 256px;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            z-index: 40;
        }
        
        #sidebar.sidebar-open {
            transform: translateX(0);
        }
        
        #sidebarOverlay {
            display: none;
        }
        
        #sidebarOverlay.overlay-visible {
            display: block;
        }
        
        /* En desktop (768px+), mostrar sidebar estáticamente */
        @media (min-width: 768px) {
            #sidebar {
                position: static !important;
                transform: none !important;
                width: auto;
            }
            
            #sidebarOverlay {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30" onclick="closeSidebar()"></div>

    <div class="flex h-screen">
        <!-- Sidebar - Comienza OCULTO en móvil -->
        <aside id="sidebar" class="bg-gradient-to-b from-blue-900 to-blue-800 text-white shadow-xl overflow-y-auto max-h-screen flex flex-col">
            <div class="p-6 border-b border-blue-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold flex items-center gap-2">
                            <i class="fas fa-building"></i> CEOGESTION
                        </h1>
                        <p class="text-sm text-blue-200 mt-1">Sistema de Gestión</p>
                    </div>
                    <!-- Botón cerrar visible SOLO en móvil -->
                    <button onclick="closeSidebar()" class="md:hidden text-white hover:bg-blue-700 p-2 rounded-lg">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <nav class="mt-6 space-y-2 px-3 flex-1">
                @if(auth()->check() && auth()->user()->tipo_rol === 'tecnico')
                    <a href="{{ route('incidencias.servicios.technician-panel') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('incidencias.servicios.technician-panel') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-tasks w-5"></i>
                        <span>Mis Servicios</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Dashboard</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <h3 class="px-4 py-2 text-xs font-bold text-blue-300 uppercase">Gestión Principal</h3>
                    </div>

                    <a href="{{ route('parametros.empresas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('parametros.empresas.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-industry w-5"></i>
                        <span>Empresas</span>
                    </a>

                    <a href="{{ route('parametros.sedes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('parametros.sedes.*') ? 'bg-blue-700' : '' }}">
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

                    <a href="{{ route('administrativo.paises.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('administrativo.paises.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-globe w-5"></i>
                        <span>Países</span>
                    </a>

                    <a href="{{ route('administrativo.departamentos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('administrativo.departamentos.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-map w-5"></i>
                        <span>Departamentos</span>
                    </a>

                    <a href="{{ route('administrativo.municipios.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('administrativo.municipios.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-city w-5"></i>
                        <span>Municipios</span>
                    </a>

                    <div class="pt-4 pb-2">
                        <h3 class="px-4 py-2 text-xs font-bold text-blue-300 uppercase">Gestión TI</h3>
                    </div>

                    <a href="{{ route('parametros.clientes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('parametros.clientes.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-handshake w-5"></i>
                        <span>Clientes</span>
                    </a>

                    <a href="{{ route('contratos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('contratos.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-file-contract w-5"></i>
                        <span>Contratos</span>
                    </a>

                    <a href="{{ route('parametros.areas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('parametros.areas.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-th w-5"></i>
                        <span>Áreas</span>
                    </a>

                    <a href="{{ route('parametros.equipos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('parametros.equipos.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-laptop w-5"></i>
                        <span>Equipos</span>
                    </a>

                    <a href="{{ route('incidencias.servicios.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('incidencias.servicios.*') && !request()->routeIs('incidencias.servicios.technician-panel') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-tools w-5"></i>
                        <span>Servicios</span>
                    </a>

                    <a href="{{ route('incidencias.servicios.panel') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('incidencias.servicios.panel') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-tasks w-5"></i>
                        <span>Servicios Asignados</span>
                    </a>

                    <a href="{{ route('parametros.tipos-equipos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('parametros.tipos-equipos.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-list w-5"></i>
                        <span>Tipos de Equipos</span>
                    </a>

                    <a href="{{ route('documentos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('documentos.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-file-upload w-5"></i>
                        <span>Documentos</span>
                    </a>
                @endif
            </nav>

            <div class="p-4 border-t border-blue-700 bg-blue-900 w-full">
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

        <main class="flex-1 flex flex-col overflow-auto w-full">
            <header class="bg-white border-b border-gray-200 px-4 sm:px-6 md:px-8 py-4 shadow-sm sticky top-0 z-20">
                <div class="flex justify-between items-center gap-4">
                    <button id="sidebarToggle" onclick="openSidebar()" class="md:hidden flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-bars text-gray-700 text-lg"></i>
                    </button>
                    
                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 truncate">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1 truncate">@yield('page-description')</p>
                    </div>
                    
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

            <div class="p-4 sm:p-6 md:p-8 overflow-auto flex-1">
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
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    <script>
        // Funciones para el sidebar
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar) {
                sidebar.classList.remove('sidebar-open');
            }
            if (overlay) {
                overlay.classList.remove('overlay-visible');
            }
        }
        
        function openSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar) {
                sidebar.classList.add('sidebar-open');
            }
            if (overlay) {
                overlay.classList.add('overlay-visible');
            }
        }
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.classList.contains('sidebar-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        }
        
        // Inicializar estado del sidebar en móvil
        document.addEventListener('DOMContentLoaded', function() {
            const isMobile = window.innerWidth < 768;
            
            if (isMobile) {
                // En móvil, SIEMPRE empezar con el sidebar cerrado
                closeSidebar();
            } else {
                // En desktop, asegurar que el sidebar esté visible
                // (El CSS estático de @media lo maneja)
                closeSidebar(); // Quitar clases si las tiene
            }
        });
        
        // Cerrar sidebar al hacer click en un enlace (solo en móvil)
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', function(e) {
                if (window.innerWidth < 768) {
                    setTimeout(() => {
                        closeSidebar();
                    }, 150);
                }
            });
        });
        
        // Cerrar sidebar al hacer click en overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSidebar();
            }
        });
        
        // Manejar cambio de tamaño de pantalla
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                // En desktop, cerrar sidebar (el CSS lo posiciona estáticamente)
                closeSidebar();
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>