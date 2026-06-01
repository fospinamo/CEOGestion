-- ============================================================================
-- OPTIMIZACIÓN DE ÍNDICES - CEOGestion
-- Ejecutar en phpMyAdmin SQL Tab para mejorar rendimiento del dashboard
-- ============================================================================

-- 1. ÍNDICES PARA TABLA CLIENTES
-- Mejorar búsqueda por empresa (usado en dashboard)
ALTER TABLE clientes ADD INDEX IF NOT EXISTS idx_empresa_id (empresa_id);
ALTER TABLE clientes ADD INDEX IF NOT EXISTS idx_estado (estado);
ALTER TABLE clientes ADD INDEX IF NOT EXISTS idx_empresa_estado (empresa_id, estado);

-- 2. ÍNDICES PARA TABLA EQUIPOS
-- Mejorar búsqueda por cliente (usado en dashboard y listados)
ALTER TABLE equipos ADD INDEX IF NOT EXISTS idx_cliente_id (cliente_id);
ALTER TABLE equipos ADD INDEX IF NOT EXISTS idx_tipo_equipo_id (tipo_equipo_id);
ALTER TABLE equipos ADD INDEX IF NOT EXISTS idx_area_id (area_id);
ALTER TABLE equipos ADD INDEX IF NOT EXISTS idx_contrato_id (contrato_id);

-- 3. ÍNDICES PARA TABLA SERVICIOS
-- Mejorar búsqueda por equipo y estado (usado en dashboard y filtros)
ALTER TABLE servicios ADD INDEX IF NOT EXISTS idx_equipo_id (equipo_id);
ALTER TABLE servicios ADD INDEX IF NOT EXISTS idx_estado (estado);
ALTER TABLE servicios ADD INDEX IF NOT EXISTS idx_tecnico_id (tecnico_id);
ALTER TABLE servicios ADD INDEX IF NOT EXISTS idx_tecnico_estado (tecnico_id, estado);
ALTER TABLE servicios ADD INDEX IF NOT EXISTS idx_created_at (created_at);

-- 4. ÍNDICES PARA TABLA CONTRATOS
-- Mejorar búsqueda por cliente y estado (usado en dashboard)
ALTER TABLE contratos ADD INDEX IF NOT EXISTS idx_cliente_id (cliente_id);
ALTER TABLE contratos ADD INDEX IF NOT EXISTS idx_estado (estado);
ALTER TABLE contratos ADD INDEX IF NOT EXISTS idx_cliente_estado (cliente_id, estado);
ALTER TABLE contratos ADD INDEX IF NOT EXISTS idx_fecha_vigencia (fecha_inicio, fecha_fin);

-- 5. ÍNDICES PARA TABLA AREAS
-- Mejorar búsqueda por sede
ALTER TABLE areas ADD INDEX IF NOT EXISTS idx_sede_id (sede_id);

-- 6. ÍNDICES PARA TABLA SEDES
-- Mejorar búsqueda por cliente
ALTER TABLE sedes ADD INDEX IF NOT EXISTS idx_cliente_id (cliente_id);

-- 7. ÍNDICES PARA TABLA USUARIOS (roles/autenticación)
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_email (email);
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_estado (estado);

-- 8. ÍNDICES PARA TABLA EMPRESAS
ALTER TABLE empresas ADD INDEX IF NOT EXISTS idx_estado (estado);

-- ============================================================================
-- VERIFICACIÓN DE ÍNDICES CREADOS
-- ============================================================================
-- Ejecutar después para verificar que los índices se crearon correctamente:
-- SHOW INDEXES FROM clientes;
-- SHOW INDEXES FROM equipos;
-- SHOW INDEXES FROM servicios;
-- SHOW INDEXES FROM contratos;

-- ============================================================================
-- INFORMACIÓN ÚTIL
-- ============================================================================
-- Los índices compuestos (ej: idx_empresa_estado) son útiles para queries como:
-- SELECT * FROM clientes WHERE empresa_id = ? AND estado = ?
--
-- Para limpiar índices duplicados, usar:
-- ALTER TABLE tabla DROP INDEX nombre_indice;
-- ============================================================================
