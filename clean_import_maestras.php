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
    
    // Si es limpieza, ejecutar TODO JUNTO para preservar FOREIGN_KEY_CHECKS=0
    if (strpos($nombre, 'limpiar') !== false) {
        echo "   Ejecutando LIMPIEZA (todo junto)...\n";
        try {
            $pdo->exec($content);
            echo "   ✅ Limpieza completada\n";
        } catch (Exception $e) {
            echo "   ⚠️  Error: " . substr($e->getMessage(), 0, 100) . "\n";
        }
        echo "\n";
        return true;
    }
    
    // Si es importación, parsear y ejecutar por partes
    echo "   Parseando SQL...\n";
    $statements = [];
    $statement = '';
    $lines = explode("\n", $content);
    
    foreach ($lines as $line) {
        $trimmed = trim($line);
        
        // Skip comentarios y líneas vacías
        if (empty($trimmed) || strpos($trimmed, '--') === 0) {
            continue;
        }
        if (strpos($trimmed, '/*') === 0 || strpos($trimmed, '*/') !== false) {
            continue;
        }
        
        $statement .= $line . "\n";
        
        // Fin de statement
        if (strpos($line, ';') !== false) {
            $stmt = trim($statement);
            if (!empty($stmt)) {
                $statements[] = $stmt;
            }
            $statement = '';
        }
    }
    
    echo "   Encontrados: " . count($statements) . " comandos\n";
    
    // Ejecutar
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0;");
    
    $executed = 0;
    $errors = [];
    
    foreach ($statements as $idx => $stmt) {
        try {
            if (!empty(trim($stmt))) {
                $pdo->exec($stmt);
                $executed++;
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
            if (count($errors) <= 3) {
                echo "   ⚠️  Error en comando " . ($idx + 1) . ": " . substr($e->getMessage(), 0, 80) . "\n";
            }
        }
    }
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1;");
    
    echo "   ✅ Ejecutados: $executed\n";
    if (count($errors) > 0) {
        echo "   ❌ Errores: " . count($errors) . " (primeros 3 mostrados)\n";
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
