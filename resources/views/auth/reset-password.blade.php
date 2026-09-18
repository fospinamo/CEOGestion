<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1A4B8E">
    <title>Restablecer Contraseña - CEOGestion</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='80' font-size='80'>📊</text></svg>">
    @vite(['resources/css/login-modern.css'])
</head>
<body>
    <div class="container-login">
        <!-- Card Login -->
        <div class="card-login">
            <!-- Header -->
            <div class="header-title">
                <h1>Restablecer Contraseña</h1>
                <p>Ingresa tu nueva contraseña</p>
            </div>

            <!-- Errores -->
            @if($errors->any())
                <div class="error-box">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Información -->
            <div class="info-box">
                <p><strong>Correo:</strong> {{ $email }}</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.reset') }}" style="width: 100%;">
                @csrf

                <!-- Hidden Fields -->
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Password -->
                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        minlength="6"
                    >
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label>Confirmar Contraseña</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        placeholder="••••••••"
                        minlength="6"
                    >
                </div>

                <!-- Submit -->
                <button type="submit" class="submit-btn">
                    Restablecer Contraseña
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
