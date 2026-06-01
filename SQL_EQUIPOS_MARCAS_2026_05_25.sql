-- ============================================
-- SENTENCIAS SQL PARA EJECUTAR EN phpMyAdmin
-- PRODUCCIÓN: simotec_ceogestion_prod
-- Fecha: 2026-05-25
-- ============================================
-- 
-- ⚠️  IMPORTANTE - INSTRUCCIONES DE SEGURIDAD:
-- 
-- 1. Hacer BACKUP de la BD ANTES de ejecutar
-- 2. Ejecutar TODOS los pasos en ORDEN
-- 3. Verificar cada paso antes de continuar
-- 4. Si algo falla, usar ROLLBACK al final
-- 5. No cerrar phpMyAdmin durante la ejecución
--
-- ============================================


-- ============================================
-- PASO 1: CREAR TABLA DE MARCAS
-- ============================================
-- Verificación: SELECT * FROM marcas;
-- Resultado esperado: Tabla vacía (datos se cargarán después)

CREATE TABLE IF NOT EXISTS `marcas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marcas_nombre_unique` (`nombre`),
  KEY `marcas_estado_index` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- PASO 2: MIGRAR MARCAS DESDE EQUIPOS
-- ============================================
-- Verifica cuántas marcas diferentes existen en equipos
-- SELECT COUNT(DISTINCT marca) FROM equipos;

INSERT INTO `marcas` (`nombre`, `estado`, `created_at`, `updated_at`)
SELECT DISTINCT `marca`, 1, NOW(), NOW()
FROM `equipos`
WHERE `marca` IS NOT NULL 
  AND `marca` != ''
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- Verificación: SELECT COUNT(*) FROM marcas;


-- ============================================
-- PASO 3: AGREGAR CLIENTE_ID A EQUIPOS
-- ============================================
-- Verifica que se agregó: DESCRIBE equipos; (buscar cliente_id)

ALTER TABLE `equipos` 
ADD COLUMN IF NOT EXISTS `cliente_id` bigint unsigned NULL 
  AFTER `area_id`
  COMMENT 'Cliente propietario del equipo';

-- Agregar restricción FK para cliente_id
ALTER TABLE `equipos` 
ADD CONSTRAINT IF NOT EXISTS `fk_equipos_cliente_id` 
  FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) 
  ON DELETE SET NULL 
  ON UPDATE CASCADE;

-- Crear índice para cliente_id
ALTER TABLE `equipos` 
ADD INDEX IF NOT EXISTS `idx_cliente_id` (`cliente_id`);


-- ============================================
-- PASO 4: AGREGAR SEDE_ID A EQUIPOS
-- ============================================
-- Verifica que se agregó: DESCRIBE equipos; (buscar sede_id)

ALTER TABLE `equipos` 
ADD COLUMN IF NOT EXISTS `sede_id` bigint unsigned NULL 
  AFTER `cliente_id`
  COMMENT 'Sede donde se encuentra el equipo';

-- Agregar restricción FK para sede_id
ALTER TABLE `equipos` 
ADD CONSTRAINT IF NOT EXISTS `fk_equipos_sede_id` 
  FOREIGN KEY (`sede_id`) REFERENCES `sedes`(`id`) 
  ON DELETE SET NULL 
  ON UPDATE CASCADE;

-- Crear índice para sede_id
ALTER TABLE `equipos` 
ADD INDEX IF NOT EXISTS `idx_sede_id` (`sede_id`);


-- ============================================
-- PASO 5: AGREGAR MARCA_ID A EQUIPOS
-- ============================================
-- Verifica que se agregó: DESCRIBE equipos; (buscar marca_id)

ALTER TABLE `equipos` 
ADD COLUMN IF NOT EXISTS `marca_id` bigint unsigned NULL 
  AFTER `tipo_equipo_id`
  COMMENT 'Marca/Fabricante del equipo';

-- Agregar restricción FK para marca_id
ALTER TABLE `equipos` 
ADD CONSTRAINT IF NOT EXISTS `fk_equipos_marca_id` 
  FOREIGN KEY (`marca_id`) REFERENCES `marcas`(`id`) 
  ON DELETE SET NULL 
  ON UPDATE CASCADE;

-- Crear índice para marca_id
ALTER TABLE `equipos` 
ADD INDEX IF NOT EXISTS `idx_marca_id` (`marca_id`);


-- ============================================
-- PASO 6: MIGRAR DATOS DE MARCA STRING → MARCA_ID
-- ============================================
-- Verifica cuántos equipos se actualizarán:
-- SELECT COUNT(*) FROM equipos WHERE marca IS NOT NULL AND marca != '';

UPDATE `equipos` e
INNER JOIN `marcas` m ON LOWER(e.`marca`) = LOWER(m.`nombre`)
SET e.`marca_id` = m.`id`
WHERE e.`marca_id` IS NULL 
  AND e.`marca` IS NOT NULL 
  AND e.`marca` != '';

-- Verificación: SELECT COUNT(*) FROM equipos WHERE marca_id IS NOT NULL;
-- Resultado esperado: Número de equipos con marca asignada


-- ============================================
-- PASO 7: RENOMBRAR CODIGO_INTERNO → CODIGO_ACTIVO_CLIENTE
-- ============================================
-- Primero, verificar si existe la columna:
-- DESCRIBE equipos; (buscar codigo_interno)

-- Si hay índice duplicado, eliminarlo primero:
ALTER TABLE `equipos` DROP INDEX IF EXISTS `codigo_interno`;

-- Renombrar la columna
ALTER TABLE `equipos` 
CHANGE COLUMN `codigo_interno` `codigo_activo_cliente` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- Recrear índice con nuevo nombre
ALTER TABLE `equipos` 
ADD UNIQUE INDEX `equipos_codigo_activo_cliente_unique` (`codigo_activo_cliente`);

-- Verificación: DESCRIBE equipos; (buscar codigo_activo_cliente)


-- ============================================
-- PASO 8: HACER SERIAL ÚNICO (IMPORTANTE)
-- ============================================
-- Antes: Verificar que no hay seriales duplicados
-- SELECT serial, COUNT(*) as count FROM equipos 
-- WHERE serial IS NOT NULL GROUP BY serial HAVING count > 1;

-- Eliminar índice simple en serial (si existe)
ALTER TABLE `equipos` DROP INDEX IF EXISTS `equipos_serial_index`;

-- Crear índice ÚNICO en serial (MySQL permite múltiples NULLs en UNIQUE)
ALTER TABLE `equipos` 
ADD UNIQUE INDEX `equipos_serial_unique` (`serial`);

-- Verificación: SHOW INDEX FROM equipos WHERE Column_name = 'serial';
-- Resultado esperado: serial debe ser Non_unique = 0 (único)


-- ============================================
-- PASO 9: ELIMINAR COLUMNA MARCA (ÚLTIMO PASO)
-- ============================================
-- SOLO ejecutar después de verificar que marca_id tiene todos los datos

-- VERIFICACIÓN CRÍTICA ANTES DE ELIMINAR:
-- SELECT COUNT(*) as total_equipos, 
--        COUNT(marca_id) as con_marca_id,
--        COUNT(marca) as con_marca_string
-- FROM equipos;
-- 
-- Si total_equipos = con_marca_id, es seguro eliminar

ALTER TABLE `equipos` DROP COLUMN IF EXISTS `marca`;

-- Verificación: DESCRIBE equipos; (NO debe aparecer 'marca')


-- ============================================
-- VERIFICACIONES FINALES
-- ============================================

-- 1. Verificar estructura final de equipos
DESCRIBE `equipos`;

-- 2. Contar registros y columnas pobladas
SELECT COUNT(*) as total, 
       COUNT(marca_id) as con_marca_id,
       COUNT(cliente_id) as con_cliente_id,
       COUNT(sede_id) as con_sede_id,
       COUNT(serial) as con_serial
FROM `equipos`;

-- 3. Ver marcas creadas
SELECT * FROM `marcas` ORDER BY nombre;

-- 4. Ver equipos sin marca (si los hay)
SELECT id, codigo_activo_cliente, serial, marca_id 
FROM `equipos` 
WHERE marca_id IS NULL 
LIMIT 10;

-- 5. Contar equipos por marca
SELECT m.nombre, COUNT(e.id) as cantidad
FROM `marcas` m
LEFT JOIN `equipos` e ON m.id = e.marca_id
GROUP BY m.id
ORDER BY cantidad DESC;


-- ============================================
-- EN CASO DE ERROR: ROLLBACK COMPLETO
-- ============================================
-- Ejecutar TODOS estos pasos en orden para revertir cambios

-- 1. Restaurar columna marca (string)
ALTER TABLE `equipos` 
ADD COLUMN `marca` VARCHAR(100) NULL AFTER `tipo_equipo_id`;

-- 2. Migrar datos de marca_id de vuelta a marca
UPDATE `equipos` e
LEFT JOIN `marcas` m ON e.`marca_id` = m.`id`
SET e.`marca` = m.`nombre`
WHERE e.`marca_id` IS NOT NULL;

-- 3. Remover índice único en serial y restaurar índice simple
ALTER TABLE `equipos` DROP INDEX IF EXISTS `equipos_serial_unique`;
ALTER TABLE `equipos` ADD INDEX `equipos_serial_index` (`serial`);

-- 4. Remover restricciones FK y columnas
ALTER TABLE `equipos`
  DROP FOREIGN KEY IF EXISTS `fk_equipos_marca_id`,
  DROP FOREIGN KEY IF EXISTS `fk_equipos_cliente_id`,
  DROP FOREIGN KEY IF EXISTS `fk_equipos_sede_id`,
  DROP COLUMN IF EXISTS `marca_id`,
  DROP COLUMN IF EXISTS `cliente_id`,
  DROP COLUMN IF EXISTS `sede_id`;

-- 5. Renombrar codigo_activo_cliente de vuelta a codigo_interno
ALTER TABLE `equipos` 
CHANGE COLUMN `codigo_activo_cliente` `codigo_interno` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- 6. Dropear tabla marcas
DROP TABLE IF EXISTS `marcas`;

-- Verificación final rollback:
-- DESCRIBE equipos; (debe tener marca string, codigo_interno, sin cliente_id, sede_id, marca_id)

-- ============================================
-- FIN DE SENTENCIAS SQL
-- ============================================
