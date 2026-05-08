<?php
// Fix Storage Permissions Script
// Ejecuta: https://gestion.simotec.com.co/CEOGestion/fix-storage.php?token=fix_storage_2026

try {
    $token = isset($_GET['token']) ? $_GET['token'] : '';
    if ($token !== 'fix_storage_2026') {
        die('Token inválido');
    }
    
    // Get base path
    $base = dirname(__FILE__);
    
    echo "<h1>Reparando permisos de Storage</h1>";
    echo "<pre style='background: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace;'>";
    
    // Crear carpeta empresas
    $empresasDir = $base . '/storage/app/public/empresas';
    if (!is_dir($empresasDir)) {
        @mkdir($empresasDir, 0755, true);
        echo "Carpeta creada: $empresasDir\n";
    } else {
        echo "Carpeta ya existe: $empresasDir\n";
    }
    
    // Fijar permisos
    @chmod($base . '/storage', 0755);
    @chmod($base . '/storage/app', 0755);
    @chmod($base . '/storage/app/public', 0755);
    @chmod($empresasDir, 0755);
    @chmod($base . '/public/storage', 0755);
    @chmod($base . '/bootstrap/cache', 0755);
    
    echo "Permisos ajustados\n";
    
    // Probar escritura
    $testFile = $empresasDir . '/.write-test-' . time();
    if (@file_put_contents($testFile, 'test')) {
        @unlink($testFile);
        echo "Permisos de escritura: OK\n";
    } else {
        echo "ADVERTENCIA: No hay permisos de escritura\n";
    }
    
    echo "\n=================================\n";
    echo "Proceso completado\n";
    echo "Intenta subir un logo ahora\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
