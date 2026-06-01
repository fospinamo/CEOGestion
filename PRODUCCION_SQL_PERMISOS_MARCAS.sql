-- =================================================================
-- 📝 SENTENCIAS SQL PARA PRODUCCIÓN - Marcas
-- =================================================================
-- Fecha: 2026-05-27
-- BD: simotec_ceogestion_prod
-- Pasos: 2 (Permisos + Asignación)
-- =================================================================

-- =================================================================
-- ✅ PASO 1: AGREGAR PERMISOS DE MARCAS
-- =================================================================
-- Ejecutar PRIMERO este bloque

INSERT INTO permissions (name, module, resource, action, description, created_at, updated_at) 
VALUES 
('marcas.ver', 'Parámetros', 'marcas', 'ver', 'Ver marcas', NOW(), NOW()),
('marcas.crear', 'Parámetros', 'marcas', 'crear', 'Crear marca', NOW(), NOW()),
('marcas.editar', 'Parámetros', 'marcas', 'editar', 'Editar marca', NOW(), NOW()),
('marcas.eliminar', 'Parámetros', 'marcas', 'eliminar', 'Eliminar marca', NOW(), NOW());

-- Verificación PASO 1
SELECT COUNT(*) as permisos_agregados FROM permissions WHERE resource = 'marcas';
-- Resultado esperado: 4

-- =================================================================
-- ✅ PASO 2: ASIGNAR PERMISOS AL ROL ADMIN
-- =================================================================
-- Ejecutar SEGUNDO este bloque (DESPUÉS de Paso 1)

INSERT INTO role_permissions (role_id, permission_id, created_at, updated_at) 
SELECT 
    (SELECT id FROM roles WHERE name = 'admin' LIMIT 1) as role_id,
    id as permission_id,
    NOW(),
    NOW()
FROM permissions 
WHERE resource = 'marcas';

-- Verificación PASO 2
SELECT COUNT(*) as permisos_asignados
FROM role_permissions rp
JOIN permissions p ON rp.permission_id = p.id
WHERE p.resource = 'marcas';
-- Resultado esperado: 4

-- =================================================================
-- 🔍 VERIFICACIÓN COMPLETA
-- =================================================================

-- Ver todos los permisos de marcas
SELECT 
    p.id,
    p.name,
    p.module,
    p.resource,
    p.action,
    p.description
FROM permissions p
WHERE p.resource = 'marcas'
ORDER BY p.action;

-- Ver permisos asignados al rol admin
SELECT 
    r.name as rol,
    p.name as permiso,
    p.action as accion
FROM role_permissions rp
JOIN roles r ON rp.role_id = r.id
JOIN permissions p ON rp.permission_id = p.id
WHERE p.resource = 'marcas' AND r.name = 'admin'
ORDER BY p.action;

-- Ver ID del rol admin (para referencia)
SELECT id, name FROM roles WHERE name = 'admin';

-- =================================================================
-- ✅ LISTO PARA PRODUCCIÓN
-- =================================================================
