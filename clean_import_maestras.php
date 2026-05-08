<?php
/**
 * Script para limpiar y importar base de datos en producción
 * Versión 3: Robusto para cPanel (sin dependencia de shell_exec)
 * 
 * ⚠️ ELIMINA ESTE ARCHIVO DESPUÉS DE USAR
 */

// Configuración
$host = getenv('DB_HOST') ?: 'localhost';
$database = getenv('DB_DATABASE') ?: 'ceogestion_db';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

$backupFile = __DIR__ . '/backup_maestras.sql';
$cleanFile = __DIR__ . '/limpiar_produccion.sql';

echo "<pre style='font-family: monospace; background: #f5f5f5; padding: 15px; border-radius: 5px; color: #333;'>";
echo "🔧 GESTOR DE BD v3 - ROBUSTO PARA CPANEL\n";
echo "==========================================\n\n";

// === CONEXIÓN ===
echo "📡 Conectando a BD...\n";
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ Conexión OK: $database\n\n";
} catch (Exception $e) {
    die("❌ Error conexión: " . $e->getMessage());
}

// === FUNCIÓN: Ejecutar SQL desde archivo ===
function ejecutarSQL($pdo, $archivo, $nombre) {
    if (!file_exists($archivo)) {
        echo "⚠️  Archivo no encontrado: $archivo\n";
        return false;
    }
    
    echo "📂 Leyendo: " . basename($archivo) . "\n";
    $content = file_get_contents($archivo);
    
    // Para ambos tipos de archivo: ejecutar TODO JUNTO sin parsear
    // Esto evita problemas con comentarios condicionales, LOCK TABLES, etc.
    echo "   Ejecutando SQL (todo junto)...\n";
    try {
        $pdo->exec($content);
        echo "   ✅ Ejecución completada\n";
    } catch (Exception $e) {
        echo "   ⚠️  Error: " . substr($e->getMessage(), 0, 150) . "\n";
        $errors = 1;
    }
    echo "\n";
    
    return true;
}

// === FASE 1: Limpiar datos operacionales ===
echo "=" . str_repeat("=", 40) . "\n";
echo "FASE 1: Limpiar datos operacionales\n";
echo "=" . str_repeat("=", 40) . "\n";
ejecutarSQL($pdo, $cleanFile, 'limpiar');

// === FASE 2: Importar maestras ===
echo "=" . str_repeat("=", 40) . "\n";
echo "FASE 2: Importar tablas maestras\n";
echo "=" . str_repeat("=", 40) . "\n";
ejecutarSQL($pdo, $backupFile, 'maestras');

// === VERIFICACIÓN FINAL ===
echo "=" . str_repeat("=", 40) . "\n";
echo "🔍 VERIFICACIÓN FINAL\n";
echo "=" . str_repeat("=", 40) . "\n";

$masterTables = [
    'paises',
    'departamentos',
    'municipios',
    'barrios',
    'tipos_equipos',
    'categorias',
    'estado_servicios',
    'roles',
    'permissions',
    'role_permissions'
];

$ok = 0;
$fail = 0;

foreach ($masterTables as $table) {
    try {
        $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        if ($count > 0) {
            echo "✅ $table: $count registros\n";
            $ok++;
        } else {
            echo "⚠️  $table: VACÍA\n";
            $fail++;
        }
    } catch (Exception $e) {
        echo "❌ $table: ERROR\n";
        $fail++;
    }
}

echo "\n";
echo "=" . str_repeat("=", 40) . "\n";

// Verificar datos operacionales eliminados
echo "\n🗑️  DATOS OPERACIONALES (deben estar vacíos):\n";
$operacionales = ['servicios', 'equipos', 'empresas', 'clientes', 'sedes', 'areas'];

foreach ($operacionales as $table) {
    try {
        $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        if ($count == 0) {
            echo "✅ $table: $count (LIMPIO)\n";
        } else {
            echo "⚠️  $table: $count registros (REVISAR)\n";
        }
    } catch (Exception $e) {
        // Tabla no existe
    }
}

echo "\n";
echo "=" . str_repeat("=", 40) . "\n";

if ($ok == 10 && $fail == 0) {
    echo "✅ PROCESO COMPLETADO EXITOSAMENTE\n";
    echo "\n📋 Próximos pasos:\n";
    echo "1. ⚠️  ELIMINA ESTOS ARCHIVOS:\n";
    echo "   - import_maestras.php (este)\n";
    echo "   - limpiar_produccion.sql\n";
    echo "   - backup_maestras.sql\n";
    echo "\n2. 🔧 Ejecuta en cPanel Terminal:\n";
    echo "   php artisan migrate --force\n";
    echo "   php artisan db:seed --force\n";
    echo "\n3. ✅ Verifica en: https://tu-dominio.com/\n";
} else {
    echo "⚠️  REVISAR - No todas las maestras están OK\n";
    echo "   Maestras OK: $ok/10\n";
    echo "   Con problemas: $fail\n";
}

echo "=" . str_repeat("=", 40) . "\n";
echo "</pre>";

?>
