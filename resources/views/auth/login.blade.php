<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1A4B8E">
    <title>Ingresar - CEOGestion</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='80' font-size='80'>📊</text></svg>">
    @vite(['resources/css/login-modern.css'])
</head>
<body>
    <div class="container-login">
        <!-- Card Login -->
        <div class="card-login">
            <!-- Header con Logos y Nombres -->
            <div class="header-title">
                <div class="header-logos">
                    <!-- Logo de la Empresa -->
                    @if($empresa && $empresa->logo)
                        <div class="logo-container">
                            <img src="{{ asset($empresa->logo) }}" alt="{{ $empresa->nombre }}" class="logo-empresa">
                            <h2 style="font-size: 0.75rem; color: #666;">{{ $empresa->nombre }}</h2>
                        </div>
                    @else
                        <div class="logo-container">
                            <div style="width: 60px; height: 60px; background-color: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 2rem; color: #ccc;">🏢</span>
                            </div>
                            <span style="font-size: 0.75rem; color: #666;">
                                @if($empresa)
                                    {{ $empresa->nombre }}
                                @else
                                    Empresa
                                @endif
                            </span>
                        </div>
                    @endif
                </div>

                <h2 style="font-size: 1.275rem; font-weight: 700; color: #0D2A54; margin: 1rem 0 0 0;">CEOGestion</h2>
                <p style="color: #666; font-size: 0.65rem; margin: 0.05rem 0 0 0;">Sistema de Gestión Empresarial</p>
            </div>

            <!-- Errores -->
            @if($errors->any())
                <div class="error-box">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Form Login -->
            <form method="POST" action="{{ route('login.store') }}" style="width: 100%;">
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

                <!-- Password -->
                <div class="form-group">
                    <label>Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                    >
                </div>

                <!-- Submit -->
                <button type="submit" class="submit-btn">
                    Ingresar
                </button>

                <!-- Forgot Password Link -->
                <div class="back-link">
                    <a href="{{ route('password.forgot') }}">¿Olvidaste tu contraseña?</a>
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
