<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1A4B8E">
    <title>Restablecer Contraseña - CEOGestion</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='80' font-size='80'>📊</text></svg>">
    <style>
        * { margin: 0 !important; padding: 0 !important; box-sizing: border-box !important; }
        html, body { width: 100% !important; height: 100% !important; }
        body { 
            font-family: 'Segoe UI', sans-serif !important;
            background: linear-gradient(135deg, #1A4B8E 0%, #0D2A54 100%) !important;
            min-height: 100vh !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow-x: hidden !important;
        }
        
        .container-login {
            width: 100% !important;
            max-width: 448px !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        .card-login {
            width: 100% !important;
            background-color: white !important;
            border-radius: 12px !important;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15) !important;
            padding: 2rem !important;
            overflow: hidden !important;
        }
        
        .header-title {
            text-align: center !important;
            margin-bottom: 2rem !important;
        }
        
        .header-title h1 {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: #0D2A54 !important;
            margin: 0 !important;
        }
        
        .header-title p {
            color: #666 !important;
            font-size: 0.875rem !important;
            margin: 0.5rem 0 0 0 !important;
        }
        
        .form-group {
            margin-bottom: 1rem !important;
            width: 100% !important;
        }
        
        .form-group label {
            display: block !important;
            color: #333 !important;
            font-size: 0.875rem !important;
            font-weight: 700 !important;
            margin-bottom: 0.5rem !important;
        }
        
        .form-group input {
            width: 100% !important;
            padding: 0.5rem 1rem !important;
            border: 2px solid #d1d5db !important;
            border-radius: 8px !important;
            font-size: 1rem !important;
            font-family: 'Segoe UI', sans-serif !important;
        }
        
        .form-group input:focus {
            border-color: #1A4B8E !important;
            outline: none !important;
            box-shadow: 0 0 5px rgba(26, 75, 142, 0.3) !important;
        }
        
        .submit-btn {
            width: 100% !important;
            background: linear-gradient(90deg, #1A4B8E 0%, #0D2A54 100%) !important;
            color: white !important;
            font-weight: 700 !important;
            padding: 0.5rem 1rem !important;
            border-radius: 8px !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            font-size: 1rem !important;
            margin-bottom: 1rem !important;
        }
        
        .submit-btn:hover {
            background: linear-gradient(90deg, #1540A3 0%, #08193A 100%) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
        }
        
        .back-link {
            text-align: center !important;
            margin-top: 1rem !important;
        }
        
        .back-link a {
            color: #1A4B8E !important;
            font-size: 0.875rem !important;
            text-decoration: none !important;
            font-weight: 600 !important;
        }
        
        .back-link a:hover {
            text-decoration: underline !important;
        }
        
        .error-box {
            background-color: #fee !important;
            border-left: 4px solid #dc2626 !important;
            padding: 1rem !important;
            margin-bottom: 1rem !important;
            border-radius: 4px !important;
        }
        
        .error-box p {
            color: #dc2626 !important;
            font-size: 0.875rem !important;
            margin: 0.25rem 0 !important;
        }
        
        .footer {
            text-align: center !important;
            margin-top: 1.5rem !important;
            color: white !important;
            font-size: 0.875rem !important;
            width: 100% !important;
            max-width: 448px !important;
            padding: 0 1rem !important;
            box-sizing: border-box !important;
        }
        
        .footer p {
            margin: 0 !important;
            word-break: break-word !important;
        }

        .info-box {
            background-color: #f0f6ff !important;
            border-left: 4px solid #2E7DFF !important;
            padding: 1rem !important;
            border-radius: 4px !important;
            margin-bottom: 1rem !important;
        }

        .info-box p {
            margin: 0 !important;
            font-size: 0.875rem !important;
            color: #333 !important;
        }
    </style>
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
