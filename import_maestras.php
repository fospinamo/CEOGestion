<?php
/**
 * Script para importar backup de tablas maestras en producción
 * Uso: Sube este archivo a /public_html/db/import_maestras.php
 * Accede: https://tu-dominio.com/db/import_maestras.php
 * 
 * ⚠️ ELIMINA ESTE ARCHIVO DESPUÉS DE IMPORTAR
 */

// Configuración
$host = 'localhost';
$database = getenv('DB_DATABASE') ?: 'simotec_ceogestion_prod';
$username = getenv('DB_USERNAME') ?: 'simotec_ceogestion_user';
$password = getenv('DB_PASSWORD') ?: 'Simotec2026';

// Ruta del backup (misma carpeta que este script)
$backupFile = __DIR__ . '/backup_maestras.sql';

if (!file_exists($backupFile)) {
    die("❌ ERROR: Archivo backup_maestras.sql no encontrado en " . $backupFile);
}

echo "<pre style='font-family: monospace; background: #f5f5f5; padding: 15px; border-radius: 5px;'>";
echo "🔧 IMPORTADOR DE BASES DE DATOS\n";
echo "================================\n\n";

// Conectar a MySQL
echo "📡 Conectando a base de datos: $database\n";
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 30
        ]
    );
    echo "✅ Conexión exitosa\n\n";
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    echo "   Host: $host\n";
    echo "   BD: $database\n";
    echo "   Usuario: $username\n";
    die();
}

// Leer archivo SQL
echo "📂 Leyendo archivo: backup_maestras.sql (" . filesize($backupFile) . " bytes)\n";
$sql = file_get_contents($backupFile);
echo "✅ Archivo leído\n\n";

// Dividir por comandos (separador ; que no está dentro de comentarios)
$commands = [];
$current = '';
$lines = explode("\n", $sql);

foreach ($lines as $line) {
    // Ignorar comentarios
    if (substr(trim($line), 0, 2) === '--') {
        continue;
    }
    if (substr(trim($line), 0, 2) === '/*') {
        continue;
    }
    
    $current .= $line . "\n";
    
    // Si termina con ;
    if (strpos($line, ';') !== false && trim($line) !== '') {
        $cmd = trim($current);
        if (!empty($cmd) && $cmd !== ';') {
            $commands[] = $cmd;
            $current = '';
        }
    }
}

// Ejecutar comandos
echo "⚙️  Ejecutando SQL...\n";
$executed = 0;
$errors = [];

foreach ($commands as $idx => $command) {
    try {
        if (!empty(trim($command))) {
            $pdo->exec($command);
            $executed++;
            echo "   ✓ Comando " . ($idx + 1) . "\n";
        }
    } catch (Exception $e) {
        $errors[] = "Comando " . ($idx + 1) . ": " . $e->getMessage();
        echo "   ✗ Comando " . ($idx + 1) . " ERROR: " . $e->getMessage() . "\n";
    }
}

echo "\n";
echo "================================\n";
echo "📊 RESULTADOS DE IMPORTACIÓN\n";
echo "================================\n";
echo "✅ Comandos ejecutados: $executed\n";
echo "❌ Errores: " . count($errors) . "\n";

if (!empty($errors)) {
    echo "\n⚠️  ERRORES ENCONTRADOS:\n";
    foreach ($errors as $err) {
        echo "   - $err\n";
    }
}

// Verificar tablas importadas
echo "\n🔍 TABLAS IMPORTADAS:\n";
try {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        echo "   ✓ $table ($count registros)\n";
    }
} catch (Exception $e) {
    echo "   Error al verificar: " . $e->getMessage() . "\n";
}

echo "\n";
echo "✅ IMPORTACIÓN COMPLETADA\n";
echo "⚠️  ELIMINA ESTE ARCHIVO (import_maestras.php) POR SEGURIDAD\n";
echo "</pre>";

?>
