<?php
/**
 * GENERADOR DE SQL INSERT
 * 
 * Propósito: Generar INSERT statements para phpMyAdmin
 * Uso: php generate_sql_inserts.php
 */

try {
    $pdo = new PDO('mysql:host=localhost;dbname=ceogestion_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}

$tables = [
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

$sql = "-- INSERTS PARA PHPYADMIN\n";
$sql .= "-- Propósito: Llenar maestras sin Terminal cPanel\n";
$sql .= "-- Uso: Copiar y pegar en phpMyAdmin > SQL\n";
$sql .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n\n";

$sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

foreach ($tables as $table) {
    try {
        $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($rows)) {
            $sql .= "-- Tabla: $table (vacía)\n\n";
            continue;
        }

        $sql .= "-- ==========================================\n";
        $sql .= "-- Tabla: $table (" . count($rows) . " registros)\n";
        $sql .= "-- ==========================================\n";
        $sql .= "DELETE FROM `$table`;\n";

        $cols = array_keys($rows[0]);
        $cols_str = implode('`, `', $cols);

        foreach ($rows as $row) {
            $values = [];
            foreach ($row as $v) {
                if ($v === null) {
                    $values[] = 'NULL';
                } else {
                    $values[] = $pdo->quote($v);
                }
            }
            $sql .= "INSERT INTO `$table` (`$cols_str`) VALUES (" . implode(', ', $values) . ");\n";
        }
        
        $sql .= "\n";
    } catch (Exception $e) {
        $sql .= "-- ERROR en tabla $table: " . $e->getMessage() . "\n\n";
    }
}

$sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

// Guardar archivo
file_put_contents('insert_maestras_phpmyadmin.sql', $sql);

echo "✅ ARCHIVO GENERADO\n";
echo "==========================================\n\n";
echo "Nombre: insert_maestras_phpmyadmin.sql\n";
echo "Tamaño: " . formatBytes(filesize('insert_maestras_phpmyadmin.sql')) . "\n";
echo "Tablas: " . count($tables) . "\n";
echo "\n📋 PASOS PARA USAR:\n";
echo "1. Descargar archivo: insert_maestras_phpmyadmin.sql\n";
echo "2. Ir a phpMyAdmin\n";
echo "3. Seleccionar BD: simotec_ceogestion_prod\n";
echo "4. Ir a: SQL tab\n";
echo "5. Copiar y pegar contenido del archivo\n";
echo "6. Hacer click: Ejecutar (Go)\n";
echo "7. Verificar resultado\n";

function formatBytes($bytes) {
    $units = ['B', 'KB', 'MB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, 2) . ' ' . $units[$pow];
}
?>
