<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1A4B8E">
    <title>Registrarse - CEOGestion</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='80' font-size='80'>📊</text></svg>">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #1A4B8E 0%, #0D2A54 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .w-full { width: 100%; }
        .max-w-md { max-width: 28rem; }
        .bg-white { background-color: white; }
        .rounded-lg { border-radius: 12px; }
        .shadow-2xl { box-shadow: 0 25px 50px rgba(0,0,0,0.15); }
        .p-8 { padding: 2rem; }
        .text-center { text-align: center; }
        .mb-8 { margin-bottom: 2rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .h-16 { height: 4rem; }
        .w-16 { width: 4rem; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .text-3xl { font-size: 1.875rem; }
        .text-blue-700 { color: #0D2A54; }
        .text-gray-600 { color: #666; }
        .text-sm { font-size: 0.875rem; }
        .font-bold { font-weight: 700; }
        .block { display: block; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .border-2 { border: 2px solid; }
        .border-gray-300 { border-color: #d1d5db; }
        .focus\:border-blue-500:focus { border-color: #1A4B8E; }
        .focus\:outline-none:focus { outline: none; }
        .transition { transition: all 0.2s ease; }
        .text-red-700 { color: #dc2626; }
        .bg-gradient-to-r { background: linear-gradient(90deg, #1A4B8E 0%, #0D2A54 100%); }
        .text-white { color: white; }
        .hover\:from-blue-600:hover { --tw-gradient-from: #1540A3; }
        button:hover { background: linear-gradient(90deg, #1540A3 0%, #08193A 100%); }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .my-6 { margin-top: 1.5rem; margin-bottom: 1.5rem; }
        .mt-6 { margin-top: 1.5rem; }
        .mt-8 { margin-top: 2rem; }
        input { width: 100%; padding: 0.5rem 1rem; border: 2px solid #d1d5db; border-radius: 8px; }
        input:focus { border-color: #1A4B8E; outline: none; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-500 to-blue-700 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Card Register -->
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <img src="{{ asset('images/playstore.png') }}" alt="Logo CEOGestion" class="h-16 w-16 mx-auto mb-4 rounded-lg shadow-lg">
                <h1 class="text-3xl font-bold text-blue-700">CEOGestion</h1>
                <p class="text-gray-600 text-sm">Crear Nueva Cuenta</p>
            </div>

            <!-- Errores -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                    @foreach($errors->all() as $error)
                        <p class="text-red-700 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Form Register -->
            <form method="POST" action="{{ route('register.store', [], false) }}">
                @csrf

                <!-- Nombre -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nombre Completo</label>
                    <input 
                        type="text" 
                        name="name" 
                        required 
                        value="{{ old('name') }}"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition"
                        placeholder="Tu nombre"
                    >
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Correo Electrónico</label>
                    <input 
                        type="email" 
                        name="email" 
                        required 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition"
                        placeholder="tu@email.com"
                    >
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Contraseña</label>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition"
                        placeholder="Mínimo 8 caracteres"
                    >
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Confirmar Contraseña</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        required
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition"
                        placeholder="Confirma tu contraseña"
                    >
                </div>

                <!-- Submit -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold py-2 px-4 rounded-lg transition shadow-lg"
                >
                    Crear Cuenta
                </button>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-gray-600 text-sm">
                    ¿Ya tienes cuenta? 
                    <a href="{{ route('login', [], false) }}" class="text-blue-600 font-bold hover:text-blue-700">
                        Inicia sesión
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-white text-sm">
            <p>CEO Soluciones © 2026</p>
        </div>
    </div>
</body>
</html>
