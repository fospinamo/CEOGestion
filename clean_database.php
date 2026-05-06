<?php
/**
 * LIMPIADOR DE BD PARA PRODUCCIÓN
 * 
 * Propósito: Limpiar datos operacionales sin tocar maestras
 * Uso: php clean_database.php
 * 
 * IMPORTANTE:
 * - Ejecutar DESPUÉS de subir el archivo
 * - Después de esto, ejecutar: php artisan migrate --force && php artisan db:seed --force
 * - Finalmente eliminar este archivo por seguridad
 */

echo "🔧 LIMPIADOR DE BD - PRODUCCIÓN\n";
echo "==========================================\n\n";

// Conexión
try {
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $user = $_ENV['DB_USERNAME'] ?? 'root';
    $pass = $_ENV['DB_PASSWORD'] ?? '';
    $db = $_ENV['DB_DATABASE'] ?? 'ceogestion_db';
    
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "📡 Conectando a BD...\n";
    echo "✅ Conexión OK: $db\n\n";
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    exit(1);
}

// Limpiar datos operacionales
echo "=========================================\n";
echo "LIMPIANDO DATOS OPERACIONALES\n";
echo "=========================================\n\n";

$sql = <<<'SQL'
SET FOREIGN_KEY_CHECKS=0;

DELETE FROM seguimientos_servicios;
DELETE FROM contrato_servicios;
DELETE FROM servicios;
DELETE FROM documentos_adjuntos;
DELETE FROM equipos;
DELETE FROM areas;
DELETE FROM contratos;
DELETE FROM sedes;
DELETE FROM clientes;
DELETE FROM empresas;

ALTER TABLE servicios AUTO_INCREMENT = 1;
ALTER TABLE equipos AUTO_INCREMENT = 1;
ALTER TABLE areas AUTO_INCREMENT = 1;
ALTER TABLE contratos AUTO_INCREMENT = 1;
ALTER TABLE sedes AUTO_INCREMENT = 1;
ALTER TABLE clientes AUTO_INCREMENT = 1;
ALTER TABLE empresas AUTO_INCREMENT = 1;
ALTER TABLE seguimientos_servicios AUTO_INCREMENT = 1;
ALTER TABLE contrato_servicios AUTO_INCREMENT = 1;
ALTER TABLE documentos_adjuntos AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS=1;
SQL;

try {
    $pdo->exec($sql);
    echo "✅ LIMPIEZA COMPLETADA\n\n";
} catch (Exception $e) {
    echo "❌ Error durante limpieza: " . $e->getMessage() . "\n";
    exit(1);
}

// Verificación
echo "=========================================\n";
echo "VERIFICACIÓN FINAL\n";
echo "=========================================\n\n";

$operacionales = [
    'servicios',
    'equipos',
    'empresas',
    'clientes',
    'sedes',
    'areas',
    'contratos',
    'documentos_adjuntos',
];

$maestras = [
    'paises',
    'departamentos',
    'municipios',
    'barrios',
    'tipos_equipos',
    'categorias',
    'estado_servicios',
    'roles',
    'permissions',
    'role_permissions',
];

echo "🗑️  DATOS OPERACIONALES (deben estar vacíos):\n";
$all_clean = true;
foreach ($operacionales as $table) {
    try {
        $result = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
        if ($count == 0) {
            echo "✅ $table: 0 registros (LIMPIO)\n";
        } else {
            echo "⚠️  $table: $count registros (NO LIMPIO)\n";
            $all_clean = false;
        }
    } catch (Exception $e) {
        echo "❌ $table: ERROR (" . $e->getMessage() . ")\n";
        $all_clean = false;
    }
}

echo "\n📊 MAESTRAS DE REFERENCIA:\n";
$maestras_ok = 0;
foreach ($maestras as $table) {
    try {
        $result = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
        echo "✅ $table: $count registros\n";
        $maestras_ok++;
    } catch (Exception $e) {
        echo "⚠️  $table: No existe (se creará en migraciones)\n";
    }
}

echo "\n=========================================\n";
if ($all_clean) {
    echo "✅ LIMPIEZA EXITOSA\n";
    echo "\n📋 PRÓXIMOS PASOS:\n";
    echo "1. En cPanel, ejecutar en Terminal:\n";
    echo "   php artisan migrate --force\n";
    echo "   php artisan db:seed --force\n";
    echo "\n2. Eliminar este archivo por seguridad\n";
    echo "3. Acceder a: https://tu-dominio.com/\n";
} else {
    echo "⚠️  REVISAR - Algunos datos aún existen\n";
}
echo "=========================================\n";
?>
