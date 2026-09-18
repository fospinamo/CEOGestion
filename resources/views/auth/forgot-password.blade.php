<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1A4B8E">
    <title>Recuperar Contraseña - CEOGestion</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='80' font-size='80'>📊</text></svg>">
    @vite(['resources/css/login-modern.css'])
</head>
<body>
    <div class="container-login">
        <!-- Card Login -->
        <div class="card-login">
            <!-- Header -->
            <div class="header-title">
                <h1>Recuperar Contraseña</h1>
                <p>Ingresa tu correo electrónico para recibir un enlace de recuperación</p>
            </div>

            <!-- Mensajes de éxito -->
            @if(session('success'))
                <div class="success-box">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Errores -->
            @if(session('error'))
                <div class="error-box">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="error-box">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.send-link') }}" style="width: 100%;">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input
                        type="email"
                        name="email"
                        required
                        value="{{ old('email') }}"
                        placeholder="tu@email.com"
                    >
                </div>

                <!-- Submit -->
                <button type="submit" class="submit-btn">
                    Enviar Enlace de Recuperación
                </button>

                <!-- Back Link -->
                <div class="back-link">
                    <a href="{{ route('login') }}">← Volver al Login</a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>CEO Soluciones © 2026</p>
        </div>
    </div>
</body>
</html>
