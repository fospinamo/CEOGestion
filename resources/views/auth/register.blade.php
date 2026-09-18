<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1A4B8E">
    <title>Registrarse - CEOGestion</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='80' font-size='80'>📊</text></svg>">
    @vite(['resources/css/login-modern.css'])
</head>
<body>
    <div class="container-login">
        <!-- Card Register -->
        <div class="card-login">
            <!-- Header -->
            <div class="header-title">
                <div class="header-logos">
                    <div class="logo-container">
                        <img src="{{ asset('images/playstore.png') }}" alt="Logo CEOGestion" class="logo-ceo">
                    </div>
                </div>
                <h1>CEOGestion</h1>
                <p>Crear Nueva Cuenta</p>
            </div>

            <!-- Errores -->
            @if($errors->any())
                <div class="error-box">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Form Register -->
            <form method="POST" action="{{ route('register.store', [], false) }}" style="width: 100%;">
                @csrf

                <!-- Nombre -->
                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input
                        type="text"
                        name="name"
                        required
                        value="{{ old('name') }}"
                        placeholder="Tu nombre"
                    >
                </div>

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

                <!-- Password -->
                <div class="form-group">
                    <label>Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="Mínimo 8 caracteres"
                    >
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label>Confirmar Contraseña</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        placeholder="Confirma tu contraseña"
                    >
                </div>

                <!-- Submit -->
                <button type="submit" class="submit-btn">
                    Crear Cuenta
                </button>
            </form>

            <!-- Login Link -->
            <div class="register-link">
                <p>¿Ya tienes cuenta? <a href="{{ route('login', [], false) }}">Inicia sesión</a></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>CEO Soluciones © 2026</p>
        </div>
    </div>
</body>
</html>
