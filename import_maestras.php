<?php
/**
 * Script para importar backup de tablas maestras en producción
 * Uso: Sube este archivo a /public_html/db/import_maestras.php
 * Accede: https://tu-dominio.com/db/import_maestras.php
 * 
 * ⚠️ ELIMINA ESTE ARCHIVO DESPUÉS DE IMPORTAR
 */

// Configuración desde .env
$host = getenv('DB_HOST') ?: 'localhost';
$database = getenv('DB_DATABASE') ?: 'ceogestion_db';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

// Ruta del backup (misma carpeta que este script)
$backupFile = __DIR__ . '/backup_maestras.sql';

if (!file_exists($backupFile)) {
    die("❌ ERROR: Archivo backup_maestras.sql no encontrado en " . $backupFile);
}

echo "<pre style='font-family: monospace; background: #f5f5f5; padding: 15px; border-radius: 5px;'>";
echo "🔧 IMPORTADOR DE BASES DE DATOS\n";
echo "================================\n\n";

// Primero intenta con mysql CLI (método más confiable)
echo "📡 Importando con mysql CLI...\n";

$cmd = sprintf(
    'mysql -h%s -u%s %s %s < "%s" 2>&1',
    escapeshellarg($host),
    escapeshellarg($username),
    (!empty($password) ? "-p" . escapeshellarg($password) : ""),
    escapeshellarg($database),
    escapeshellarg($backupFile)
);

$output = shell_exec($cmd);
$output = trim($output);

if (empty($output) || strpos($output, 'ERROR') === false) {
    echo "✅ Importación exitosa vía mysql CLI\n";
    echo "📂 Archivo: " . basename($backupFile) . " (" . filesize($backupFile) . " bytes)\n";
    echo "💾 Base de datos: $database\n\n";
    
    // Valida importación
    echo "🔍 VERIFICACIÓN DE TABLAS:\n";
    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$database;charset=utf8mb4",
            $username,
            $password
        );
        
        $masterTables = ['paises', 'departamentos', 'municipios', 'barrios', 'tipos_equipos', 
                         'categorias', 'estado_servicios', 'roles', 'permissions', 'role_permissions'];
        
        $imported = 0;
        foreach ($masterTables as $table) {
            try {
                $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
                echo "   ✅ $table ($count registros)\n";
                $imported++;
            } catch (Exception $e) {
                echo "   ❌ $table - error\n";
            }
        }
        
        echo "\n   📊 Tablas maestras importadas: $imported/" . count($masterTables) . "\n";
        
    } catch (Exception $e) {
        echo "   ⚠️  Error al verificar: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "❌ Error en importación:\n$output\n";
}

echo "\n";
echo "================================\n";
echo "✅ PROCESO COMPLETADO\n";
echo "⚠️  ELIMINA ESTE ARCHIVO (import_maestras.php) POR SEGURIDAD\n";
echo "================================\n";
echo "</pre>";

?>
