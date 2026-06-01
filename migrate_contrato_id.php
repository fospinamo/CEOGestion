<?php
/**
 * Script de Migración Manual - Agregar contrato_id a equipos
 * 
 * INSTRUCCIONES:
 * 1. Subir este archivo a la raíz del proyecto en producción
 * 2. Acceder vía: https://tudominio.com/migrate_contrato_id.php
 * 3. Ejecutar y luego ELIMINAR este archivo
 * 
 * ⚠️ IMPORTANTE: Eliminar después de ejecutar por seguridad
 */

// Verificar que solo se ejecute una vez
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Migración Contrato ID</title>
        <style>
            body { font-family: Arial; max-width: 600px; margin: 50px auto; }
            .warning { background: #fff3cd; padding: 15px; border-radius: 5px; color: #856404; margin-bottom: 20px; }
            form { background: #f8f9fa; padding: 20px; border-radius: 5px; }
            button { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
            button:hover { background: #218838; }
        </style>
    </head>
    <body>
        <h1>🔧 Migración Manual - Agregar Contrato ID</h1>
        
        <div class="warning">
            <strong>⚠️ ADVERTENCIA:</strong> Este script ejecutará cambios en la base de datos.
            <br>Se recomienda hacer backup antes de continuar.
        </div>

        <form method="POST">
            <h3>¿Estás seguro de que deseas ejecutar la migración?</h3>
            <p>Se agregará la columna <code>contrato_id</code> a la tabla <code>equipos</code></p>
            <button type="submit" name="confirm" value="yes">✓ Ejecutar Migración</button>
            <button type="submit" name="confirm" value="no" style="background: #dc3545;">✗ Cancelar</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// Procesar confirmación
if ($_POST['confirm'] !== 'yes') {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit('Migración cancelada');
}

// Cargar configuración de Laravel
require __DIR__ . '/bootstrap/app.php';

try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    
    // Obtener conexión a BD
    $db = app('db');
    
    // Log de inicio
    echo "<h1>🚀 Ejecutando Migración...</h1>\n";
    echo "<pre>\n";
    
    // 1. Agregar columna
    echo "1. Agregando columna contrato_id...\n";
    $db->statement("
        ALTER TABLE equipos 
        ADD COLUMN contrato_id BIGINT UNSIGNED NULL 
        AFTER cliente_id 
        COMMENT \"Contrato asociado\"
    ");
    echo "   ✓ Columna agregada\n";
    
    // 2. Crear foreign key
    echo "2. Creando relación con tabla contratos...\n";
    $db->statement("
        ALTER TABLE equipos 
        ADD CONSTRAINT fk_equipos_contrato_id 
        FOREIGN KEY (contrato_id) 
        REFERENCES contratos(id) 
        ON DELETE SET NULL
    ");
    echo "   ✓ Foreign key creada\n";
    
    // 3. Crear índice
    echo "3. Creando índice para mejor rendimiento...\n";
    $db->statement("
        ALTER TABLE equipos 
        ADD INDEX idx_equipos_contrato_id (contrato_id)
    ");
    echo "   ✓ Índice creado\n";
    
    // 4. Registrar migración en tabla migrations
    echo "4. Registrando migración...\n";
    $db->table('migrations')->insert([
        'migration' => '2026_05_21_000001_add_contrato_id_to_equipos_table',
        'batch' => $db->table('migrations')->max('batch') + 1,
    ]);
    echo "   ✓ Migración registrada en BD\n";
    
    echo "\n✅ MIGRACIÓN COMPLETADA EXITOSAMENTE\n";
    echo "</pre>\n";
    
    // Mostrar instrucción de limpieza
    echo "<hr>\n";
    echo "<h3>⚠️ IMPORTANTE:</h3>\n";
    echo "<p><strong>Debes ELIMINAR este archivo:</strong> <code>migrate_contrato_id.php</code></p>\n";
    echo "<p>Por razones de seguridad, este archivo debe ser eliminado inmediatamente.</p>\n";
    
} catch (\Exception $e) {
    echo "<h1>❌ ERROR EN LA MIGRACIÓN</h1>\n";
    echo "<pre style='background: #f8d7da; padding: 15px; color: #721c24; border-radius: 5px;'>\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "</pre>\n";
    echo "<p><strong>Solución:</strong> Ejecuta manualmente el SQL en phpMyAdmin</p>\n";
}
?>
