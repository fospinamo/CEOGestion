<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Portal del Cliente | CEOGESTION</title>

    <!-- CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-700 font-sans">
    <!-- Mobile overlay -->
    <div id="portalOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden" onclick="closePortalSidebar()"></div>

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
        <div class="max-w-[1400px] mx-auto px-5 flex justify-between items-center h-14">
            <!-- Mobile menu button -->
            <button onclick="openPortalSidebar()" class="md:hidden text-gray-500 hover:text-gray-700 mr-3">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <a href="{{ route('portal.dashboard') }}" class="text-lg font-bold text-blue-500 no-underline">
                <i class="fas fa-cube mr-2"></i>CEOGESTION
            </a>

            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600 hidden sm:inline">
                    {{ \App\Models\User::find(session('portal_user_id'))?->cliente?->razon_social ?? 'Cliente' }}
                </span>
                <a href="{{ route('portal.logout') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-500 text-white text-sm font-medium rounded-md hover:bg-red-600 transition no-underline">
                    <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Salir</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside id="portalSidebar" class="bg-white border-r border-gray-200 min-h-[calc(100vh-56px)] w-60 shrink-0 fixed md:static -translate-x-full md:translate-x-0 transition-transform duration-200 z-40">
            <ul class="py-5 list-none">
                <li>
                    <a href="{{ route('portal.dashboard') }}"
                       class="block py-3 px-5 text-gray-500 no-underline transition-all border-l-[3px] border-transparent hover:bg-blue-50 hover:text-blue-500 hover:border-blue-500 {{ Route::currentRouteName() === 'portal.dashboard' ? 'bg-blue-50 text-blue-500 border-blue-500' : '' }}">
                        <i class="fas fa-chart-line w-5 mr-2.5"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('portal.contratos') }}"
                       class="block py-3 px-5 text-gray-500 no-underline transition-all border-l-[3px] border-transparent hover:bg-blue-50 hover:text-blue-500 hover:border-blue-500 {{ Route::currentRouteName() === 'portal.contratos' ? 'bg-blue-50 text-blue-500 border-blue-500' : '' }}">
                        <i class="fas fa-file-contract w-5 mr-2.5"></i> Contratos
                    </a>
                </li>
                <li>
                    <a href="{{ route('portal.equipos') }}"
                       class="block py-3 px-5 text-gray-500 no-underline transition-all border-l-[3px] border-transparent hover:bg-blue-50 hover:text-blue-500 hover:border-blue-500 {{ Route::currentRouteName() === 'portal.equipos' ? 'bg-blue-50 text-blue-500 border-blue-500' : '' }}">
                        <i class="fas fa-server w-5 mr-2.5"></i> Equipos
                    </a>
                </li>
                <li>
                    <a href="{{ route('portal.servicios') }}"
                       class="block py-3 px-5 text-gray-500 no-underline transition-all border-l-[3px] border-transparent hover:bg-blue-50 hover:text-blue-500 hover:border-blue-500 {{ Route::currentRouteName() === 'portal.servicios' ? 'bg-blue-50 text-blue-500 border-blue-500' : '' }}">
                        <i class="fas fa-tools w-5 mr-2.5"></i> Servicios
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Content -->
        <main class="flex-1 min-w-0 p-6 md:p-7">
            @if($errors->any())
                <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-md">
                    <strong>Error:</strong>
                    <ul class="mt-1 ml-5 list-disc text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        function openPortalSidebar() {
            document.getElementById('portalSidebar').classList.remove('-translate-x-full');
            document.getElementById('portalOverlay').classList.remove('hidden');
        }
        function closePortalSidebar() {
            document.getElementById('portalSidebar').classList.add('-translate-x-full');
            document.getElementById('portalOverlay').classList.add('hidden');
        }
    </script>

    @yield('scripts')
</body>
</html>
