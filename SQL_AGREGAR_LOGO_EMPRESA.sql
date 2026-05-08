-- ============================================================
-- AGREGAR CAMPOS LOGO Y DESCRIPCIÓN A TABLA EMPRESAS
-- Ejecutar en phpMyAdmin en producción
-- ============================================================

-- Agregar columna logo
ALTER TABLE `empresas` ADD COLUMN `logo` VARCHAR(255) NULL AFTER `email`;

-- Agregar columna descripcion  
ALTER TABLE `empresas` ADD COLUMN `descripcion` TEXT NULL AFTER `logo`;

-- Actualizar empresa principal con valores de ejemplo
UPDATE `empresas` 
SET 
  `logo` = NULL,
  `descripcion` = 'Sistema de Gestión Empresarial'
WHERE `id` = 1;

-- Verificar cambios
SELECT `id`, `nombre`, `email`, `logo`, `descripcion` FROM `empresas`;
