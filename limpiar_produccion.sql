-- Limpiar datos operacionales en producción
-- Mantener SOLO tablas maestras
-- Ejecutar TODO JUNTO (no por sentencias individuales)

SET FOREIGN_KEY_CHECKS=0;

-- Limpiar tablas que tienen dependencias (orden importante)
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

-- Mantener solo usuario admin (id=1)
DELETE FROM users WHERE id > 1;

-- Limpiar sesiones y temporales
DELETE FROM sessions;
DELETE FROM jobs;
DELETE FROM job_batches;
DELETE FROM failed_jobs;
DELETE FROM password_reset_tokens;
DELETE FROM cache;
DELETE FROM cache_locks;

-- Re-habilitar constraints
SET FOREIGN_KEY_CHECKS=1;

-- Resetear auto-increments (opcional)
ALTER TABLE servicios AUTO_INCREMENT = 1;
ALTER TABLE equipos AUTO_INCREMENT = 1;
ALTER TABLE areas AUTO_INCREMENT = 1;
ALTER TABLE sedes AUTO_INCREMENT = 1;
ALTER TABLE clientes AUTO_INCREMENT = 1;
ALTER TABLE empresas AUTO_INCREMENT = 1;
