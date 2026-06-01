# 📝 SQL - Agregar Permisos de Marcas
## 2026-05-27

```sql
-- ================================
-- PASO 1: Insertar permisos de Marcas
-- ================================

INSERT INTO permissions (name, guard_name, module, resource, action, description, created_at, updated_at) VALUES
('marcas.ver', 'web', 'Parámetros', 'marcas', 'ver', 'Ver marcas', NOW(), NOW()),
('marcas.crear', 'web', 'Parámetros', 'marcas', 'crear', 'Crear marca', NOW(), NOW()),
('marcas.editar', 'web', 'Parámetros', 'marcas', 'editar', 'Editar marca', NOW(), NOW()),
('marcas.eliminar', 'web', 'Parámetros', 'marcas', 'eliminar', 'Eliminar marca', NOW(), NOW());

-- Verificar que se insertaron
SELECT * FROM permissions WHERE resource = 'marcas';

-- ================================
-- PASO 2: Asignar permisos al rol ADMIN
-- ================================

-- Obtener el ID del rol admin
SELECT id FROM roles WHERE name = 'admin';

-- Asignar permisos (reemplazar 1 por el ID del rol admin si es diferente)
INSERT INTO role_has_permissions (role_id, permission_id)
SELECT 
    (SELECT id FROM roles WHERE name = 'admin' LIMIT 1) as role_id,
    id as permission_id
FROM permissions
WHERE resource = 'marcas'
ON DUPLICATE KEY UPDATE role_id = role_id;

-- Verificar asignaciones
SELECT r.name as role, p.name as permission
FROM role_has_permissions rhp
JOIN roles r ON rhp.role_id = r.id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.resource = 'marcas';

-- ================================
-- VERIFICACIÓN FINAL
-- ================================

-- Ver todos los permisos de marcas
SELECT name, module, resource, action, description FROM permissions WHERE resource = 'marcas' ORDER BY action;

-- Contar permisos
SELECT COUNT(*) as total_marcas_permissions FROM permissions WHERE resource = 'marcas';
```

---

## 🚀 EJECUCIÓN

### Opción 1: Terminal (PHP Artisan)
```bash
php artisan tinker
>>> DB::insert("INSERT INTO permissions (name, guard_name, module, resource, action, description, created_at, updated_at) VALUES ('marcas.ver', 'web', 'Parámetros', 'marcas', 'ver', 'Ver marcas', NOW(), NOW()), ('marcas.crear', 'web', 'Parámetros', 'marcas', 'crear', 'Crear marca', NOW(), NOW()), ('marcas.editar', 'web', 'Parámetros', 'marcas', 'editar', 'Editar marca', NOW(), NOW()), ('marcas.eliminar', 'web', 'Parámetros', 'marcas', 'eliminar', 'Eliminar marca', NOW(), NOW())");
>>> exit
```

### Opción 2: phpMyAdmin SQL Tab
1. Abrir phpMyAdmin
2. Seleccionar BD: simotec_ceogestion
3. Tab: SQL
4. Copy-Paste todo el código
5. Click: Ejecutar

### Opción 3: Seeder (Recomendado)
```bash
php artisan db:seed --class=RoleAndPermissionSeeder
```

---

## ✅ VERIFICACIÓN

Después de ejecutar, en el panel debe aparecer el link "Marcas" en Parámetros → Gestión TI

Si no aparece:
1. Limpiar cache: `php artisan cache:clear`
2. Recargar navegador: Ctrl+Shift+R
3. Logout y login nuevamente
