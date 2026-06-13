<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - CEOGestion</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9fafb;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1A4B8E;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #0D2A54;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .content p {
            margin: 10px 0;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            background-color: #1A4B8E;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            font-weight: bold;
        }
        .button:hover {
            background-color: #0D2A54;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 30px;
            border-top: 1px solid #d1d5db;
            padding-top: 20px;
        }
        .link-text {
            background-color: #f3f4f6;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
            word-break: break-all;
            font-family: monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>Recuperación de Contraseña</h1>
            </div>

            <div class="content">
                <p>Hola <strong>{{ $user->name }}</strong>,</p>

                <p>Hemos recibido una solicitud para recuperar tu contraseña en CEOGestion. Haz clic en el botón de abajo para crear una nueva contraseña.</p>

                <div class="button-container">
                    <a href="{{ $resetLink }}" class="button">Restablecer Contraseña</a>
                </div>

                <p>O copia y pega este enlace en tu navegador:</p>
                <div class="link-text">{{ $resetLink }}</div>

                <div class="warning">
                    <strong>⚠️ Importante:</strong> Este enlace expirará en 24 horas. Si no solicitaste la recuperación de contraseña, puedes ignorar este correo.
                </div>

                <p>Si tienes problemas, contacta con el equipo de soporte.</p>
            </div>

            <div class="footer">
                <p>© 2026 CEO Soluciones. Todos los derechos reservados.</p>
                <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            </div>
        </div>
    </div>
</body>
</html>
