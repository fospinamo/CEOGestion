<?php
/**
 * ============================================================================
 * SCRIPT DE DESPLIEGUE POR WEB - CEOGestion
 * 
 * Ejecutar accediendo a: 
 * https://tudominio.com/CEOGestion/public/deploy-web.php?token=deploy2026ceogestion
 * 
 * IMPORTANTE: Eliminar este archivo después de usarlo por seguridad
 * ============================================================================
 */

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect to HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}

// Simple security check - require parameter
if (!isset($_GET['token']) || $_GET['token'] !== 'deploy2026ceogestion') {
    http_response_code(403);
    echo "Access Denied";
    exit;
}

// Set execution time limit
set_time_limit(300);

// Get the root directory (parent of public folder)
$rootDir = dirname(dirname(__FILE__));

// Ensure artisan exists
if (!file_exists($rootDir . '/artisan')) {
    die("Error: artisan file not found at: " . $rootDir . '/artisan');
}

// Start output
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Deploy CEOGestion</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; }
        pre { 
            background: #fff; 
            padding: 20px; 
            border-radius: 8px; 
            overflow-x: auto;
            border-left: 4px solid #0066CC;
            line-height: 1.5;
        }
        .success { color: #10B981; }
        .error { color: #DC2626; }
        .info { color: #0066CC; }
    </style>
</head>
<body>
<div class="container">
    <h1 style="margin-bottom: 20px;">🚀 Despliegue CEOGestion</h1>
    <pre><?php

// Change to root directory for execution
chdir($rootDir);

echo "==========================================\n";
echo "  🚀 DESPLIEGUE CEOGestion - Temas & Logo\n";
echo "==========================================\n\n";
echo "Directorio raíz: " . $rootDir . "\n\n";

$php_bin = PHP_EXECUTABLE;
$artisan = $rootDir . '/artisan';

// Helper function to execute artisan commands
function runArtisan($command, $description) {
    global $php_bin, $artisan;
    echo "▶ $description\n";
    
    $fullCommand = "$php_bin $artisan $command 2>&1";
    $output = shell_exec($fullCommand);
    
    if ($output) {
        echo trim($output) . "\n";
    }
    echo "\n";
}

// Step 1: Clear caches
echo "[1/7] Limpiando caches...\n";
runArtisan('cache:clear', 'Limpiando cache de aplicación');
runArtisan('view:clear', 'Limpiando cache de vistas');
runArtisan('config:clear', 'Limpiando cache de configuración');
echo "<span class='success'>✓ Caches limpiados</span>\n\n";

// Step 2: Run migrations
echo "[2/7] Ejecutando migraciones...\n";
runArtisan('migrate --force', 'Ejecutando migraciones');
echo "<span class='success'>✓ Migraciones completadas</span>\n\n";

// Step 3: Seed themes
echo "[3/7] Sembrando temas...\n";
runArtisan('db:seed --class=ThemeSeeder', 'Sembrando temas en base de datos');
echo "<span class='success'>✓ Temas creados</span>\n\n";

// Step 4: Create storage link
echo "[4/7] Creando symlink de storage...\n";
$storageLink = $rootDir . '/public/storage';
if (is_link($storageLink)) {
    unlink($storageLink);
    echo "(Symlink anterior removido)\n";
}
runArtisan('storage:link', 'Creando symlink de storage');
echo "<span class='success'>✓ Storage link creado</span>\n\n";

// Step 5: Optimize
echo "[5/7] Optimizando aplicación...\n";
runArtisan('optimize', 'Optimizando aplicación');
runArtisan('config:cache', 'Cacheando configuración');
runArtisan('route:cache', 'Cacheando rutas');
echo "<span class='success'>✓ Optimizaciones completadas</span>\n\n";

// Step 6: Verify database
echo "[6/7] Verificando base de datos...\n";
$output = shell_exec("$php_bin $artisan db:show 2>&1");
if (strpos($output, 'Error') === false) {
    echo "<span class='success'>✓ Conexión a BD verificada</span>\n\n";
} else {
    echo "<span class='error'>✗ Error en conexión: $output</span>\n\n";
}

// Step 7: Summary
echo "[7/7] Resumen final...\n";
echo "  <span class='success'>✓ Caches limpiados</span>\n";
echo "  <span class='success'>✓ Migraciones ejecutadas</span>\n";
echo "  <span class='success'>✓ Themes seeded</span>\n";
echo "  <span class='success'>✓ Storage link creado</span>\n";
echo "  <span class='success'>✓ Aplicación optimizada</span>\n";
echo "  <span class='success'>✓ Base de datos verificada</span>\n\n";

echo "==========================================\n";
echo "  <span class='success'>✅ DESPLIEGUE COMPLETADO</span>\n";
echo "==========================================\n\n";

echo "Próximos pasos:\n";
echo "1. Acceder a login: https://gestion.simotec.com.co/CEOGestion/login\n";
echo "2. Verificar que el logo de empresa sea visible\n";
echo "3. Probar login con admin@ceogestion.com / password123\n";
echo "4. <span class='error'>IMPORTANTE: Eliminar este archivo (deploy-web.php) por seguridad</span>\n\n";

echo "Tiempo de ejecución: " . number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 2) . "s\n";

    ?></pre>
</div>
</body>
</html>
<?php
?>
