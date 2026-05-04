-- Limpiar datos operacionales en producción
-- Mantener SOLO tablas maestras

SET FOREIGN_KEY_CHECKS=0;

-- Vaciar tablas de datos operacionales
TRUNCATE TABLE servicios;
TRUNCATE TABLE equipos;
TRUNCATE TABLE areas;
TRUNCATE TABLE sedes;
TRUNCATE TABLE clientes;
TRUNCATE TABLE empresas;
TRUNCATE TABLE contratos;
TRUNCATE TABLE contrato_servicios;
TRUNCATE TABLE seguimientos_servicios;
TRUNCATE TABLE documentos_adjuntos;

-- Mantener solo usuarios con role admin
DELETE FROM users WHERE id > 1;  -- Eliminar todos excepto id=1 (admin)

-- Limpiar sesiones y temporales
TRUNCATE TABLE sessions;
TRUNCATE TABLE jobs;
TRUNCATE TABLE job_batches;
TRUNCATE TABLE failed_jobs;
TRUNCATE TABLE password_reset_tokens;
TRUNCATE TABLE cache;
TRUNCATE TABLE cache_locks;

SET FOREIGN_KEY_CHECKS=1;

-- Verificar que maestras están intactas
SELECT 'Maestras verificadas:';
SELECT COUNT(*) as 'paises' FROM paises;
SELECT COUNT(*) as 'departamentos' FROM departamentos;
SELECT COUNT(*) as 'municipios' FROM municipios;
SELECT COUNT(*) as 'barrios' FROM barrios;
SELECT COUNT(*) as 'tipos_equipos' FROM tipos_equipos;
SELECT COUNT(*) as 'categorias' FROM categorias;
SELECT COUNT(*) as 'estado_servicios' FROM estado_servicios;
SELECT COUNT(*) as 'roles' FROM roles;
SELECT COUNT(*) as 'permissions' FROM permissions;
SELECT COUNT(*) as 'users (solo admin)' FROM users;
