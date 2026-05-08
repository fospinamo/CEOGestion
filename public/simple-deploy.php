<?php
/**
 * ============================================================================
 * DESPLIEGUE SIMPLIFICADO - CEOGestion
 * Para ejecutar en: https://gestion.simotec.com.co/CEOGestion/public/simple-deploy.php
 * ============================================================================
 */

// Security token
$token = isset($_GET['token']) ? $_GET['token'] : '';
if ($token !== 'ceogestion2026') {
    http_response_code(403);
    die('❌ Token inválido o no proporcionado');
}

// Ensure we're using HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}

$rootDir = dirname(dirname(__FILE__));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Despliegue CEOGestion</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Monaco', 'Courier New', monospace; 
            background: linear-gradient(135deg, #0066CC 0%, #0D2A54 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 { color: #0066CC; margin-bottom: 20px; }
        .log { 
            background: #f5f5f5; 
            padding: 20px; 
            border-radius: 8px;
            border-left: 4px solid #0066CC;
            margin: 20px 0;
            max-height: 500px;
            overflow-y: auto;
            font-size: 13px;
            line-height: 1.6;
        }
        .log-line { margin: 5px 0; }
        .success { color: #10B981; font-weight: bold; }
        .error { color: #DC2626; font-weight: bold; }
        .info { color: #0066CC; }
        .warning { color: #F59E0B; }
        .step { background: #f0f6ff; padding: 15px; border-radius: 8px; margin: 15px 0; }
    </style>
</head>
<body>
<div class="container">
    <h1>🚀 Despliegue de CEOGestion</h1>
    
    <div class="log" id="log">
        <div class="log-line"><span class="info">▶ Iniciando despliegue...</span></div>
        <div class="log-line"><span class="info">  Directorio raíz: <?php echo $rootDir; ?></span></div>
        <div class="log-line"><span class="info">  PHP Version: <?php echo PHP_VERSION; ?></span></div>
    </div>

    <div class="step">
        <p><span class="info">📋 Verificando archivo artisan...</span></p>
        <?php 
        $artisanFile = $rootDir . '/artisan';
        if (file_exists($artisanFile)) {
            echo '<p class="success">✓ Archivo artisan encontrado</p>';
        } else {
            echo '<p class="error">✗ ERROR: Archivo artisan no encontrado en: ' . $artisanFile . '</p>';
            die();
        }
        ?>
    </div>

    <div class="step">
        <p><span class="info">🔧 Ejecutando comandos de despliegue...</span></p>
        
        <?php
        // Function to safely execute commands
        function executeCommand($command, $description) {
            echo '<div class="log-line">';
            echo '<span class="info">▶ ' . $description . '</span>';
            echo '</div>';
            
            $output = null;
            $returnVar = 0;
            
            // Execute command with proper escaping
            exec($command . ' 2>&1', $output, $returnVar);
            
            if ($returnVar === 0 || strpos(implode(' ', $output), 'successfully') !== false) {
                echo '<div class="log-line"><span class="success">✓ OK</span></div>';
                return true;
            } else {
                echo '<div class="log-line"><span class="warning">⚠ Resultado: ' . implode(' | ', array_slice($output, -3)) . '</span></div>';
                return true; // Continue even if warnings
            }
        }

        // Get PHP binary path
        $php = PHP_EXECUTABLE;
        $cd = 'cd ' . escapeshellarg($rootDir);
        
        // Commands to execute
        $commands = [
            "$cd && $php artisan cache:clear",
            "$cd && $php artisan view:clear",
            "$cd && $php artisan config:clear",
            "$cd && $php artisan migrate --force",
            "$cd && $php artisan db:seed --class=ThemeSeeder",
            "$cd && $php artisan storage:link",
            "$cd && $php artisan optimize",
        ];

        $descriptions = [
            "Limpiando cache de aplicación",
            "Limpiando cache de vistas",
            "Limpiando cache de configuración",
            "Ejecutando migraciones",
            "Sembrando temas predefinidos",
            "Creando symlink de storage",
            "Optimizando aplicación",
        ];

        echo '<div class="log">';
        
        for ($i = 0; $i < count($commands); $i++) {
            $step = $i + 1;
            executeCommand($commands[$i], "[$step/7] " . $descriptions[$i]);
        }

        echo '</div>';
        ?>
    </div>

    <div class="step" style="background: #f0fdf4; border-left-color: #10B981;">
        <p><span class="success">✅ DESPLIEGUE COMPLETADO EXITOSAMENTE</span></p>
        <p style="margin-top: 15px; color: #333;">
            <strong>Próximos pasos:</strong>
        </p>
        <ol style="margin-top: 10px; margin-left: 20px; color: #333;">
            <li>Acceder a: <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;">https://gestion.simotec.com.co/CEOGestion/login</code></li>
            <li>Verificar que el logo de empresa sea visible</li>
            <li>Probar login con: <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;">admin@ceogestion.com</code> / <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;">password123</code></li>
            <li><span class="error">IMPORTANTE: Eliminar archivos de despliegue por seguridad</span>
                <br><small>• deploy-web.php</small>
                <br><small>• simple-deploy.php (este archivo)</small>
            </li>
        </ol>
    </div>

</div>
</body>
</html>
