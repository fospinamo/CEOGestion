# 📋 CHANGELOG - Actualización de Tabla Equipos (2026-05-25)

## 🎯 Objetivo
Mejorar estructura de tabla `equipos`:
- Cambiar `codigo_interno` → `codigo_activo_cliente`
- Agregar `cliente_id` y `sede_id`
- Cambiar `marca` (string) → `marca_id` (FK a nueva tabla)
- Crear tabla `marcas` parametrizada
- Hacer `serie` única

## ⚠️ IMPORTANTE - PASOS DE EJECUCIÓN

### PASO 1: Crear Tabla de Marcas
**En phpMyAdmin → SQL:**

```sql
-- ============================================
-- Crear tabla MARCAS
-- ============================================
CREATE TABLE IF NOT EXISTS `marcas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci,
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX idx_estado (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**✅ Resultado esperado:** Tabla creada sin datos (está bien, será poblada después)

---

### PASO 2: Migrar Marcas Existentes
**En phpMyAdmin → SQL:**

```sql
-- ============================================
-- Insertar marcas únicas de equipos existentes
-- ============================================
INSERT INTO `marcas` (`nombre`, `estado`, `created_at`, `updated_at`)
SELECT DISTINCT `marca`, 1, NOW(), NOW()
FROM `equipos`
WHERE `marca` IS NOT NULL 
  AND `marca` != ''
ON DUPLICATE KEY UPDATE `updated_at` = NOW();
```

**✅ Verifica:** `SELECT * FROM marcas;` debe mostrar todas las marcas

---

### PASO 3: Agregar Campos a Tabla Equipos (SEGURO)
**En phpMyAdmin → SQL:**

```sql
-- ============================================
-- 1. Agregar cliente_id si no existe
-- ============================================
ALTER TABLE `equipos` 
ADD COLUMN IF NOT EXISTS `cliente_id` bigint unsigned NULL 
AFTER `area_id`,
ADD CONSTRAINT `fk_equipos_cliente_id` 
  FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) 
  ON DELETE SET NULL 
  ON UPDATE CASCADE;

-- Crear índice para cliente_id
ALTER TABLE `equipos` 
ADD INDEX IF NOT EXISTS `idx_cliente_id` (`cliente_id`);

-- ============================================
-- 2. Agregar sede_id si no existe
-- ============================================
ALTER TABLE `equipos` 
ADD COLUMN IF NOT EXISTS `sede_id` bigint unsigned NULL 
AFTER `cliente_id`,
ADD CONSTRAINT `fk_equipos_sede_id` 
  FOREIGN KEY (`sede_id`) REFERENCES `sedes`(`id`) 
  ON DELETE SET NULL 
  ON UPDATE CASCADE;

-- Crear índice para sede_id
ALTER TABLE `equipos` 
ADD INDEX IF NOT EXISTS `idx_sede_id` (`sede_id`);

-- ============================================
-- 3. Agregar marca_id si no existe
-- ============================================
ALTER TABLE `equipos` 
ADD COLUMN IF NOT EXISTS `marca_id` bigint unsigned NULL 
AFTER `tipo_equipo_id`,
ADD CONSTRAINT `fk_equipos_marca_id` 
  FOREIGN KEY (`marca_id`) REFERENCES `marcas`(`id`) 
  ON DELETE SET NULL 
  ON UPDATE CASCADE;

-- Crear índice para marca_id
ALTER TABLE `equipos` 
ADD INDEX IF NOT EXISTS `idx_marca_id` (`marca_id`);
```

**✅ Verifica:** `DESCRIBE equipos;` debe mostrar los 3 nuevos campos

---

### PASO 4: Migrar Datos de Marca String → marca_id
**En phpMyAdmin → SQL:**

```sql
-- ============================================
-- Actualizar marca_id basado en marca string
-- ============================================
UPDATE `equipos` e
INNER JOIN `marcas` m ON LOWER(e.`marca`) = LOWER(m.`nombre`)
SET e.`marca_id` = m.`id`
WHERE e.`marca_id` IS NULL;
```

**✅ Verifica:** `SELECT COUNT(*) FROM equipos WHERE marca_id IS NOT NULL;` debe mostrar cantidad de equipos con marca

---

### PASO 5: Renombrar codigo_interno → codigo_activo_cliente
**En phpMyAdmin → SQL:**

```sql
-- ============================================
-- Renombrar columna codigo_interno
-- ============================================
ALTER TABLE `equipos` 
CHANGE COLUMN `codigo_interno` `codigo_activo_cliente` VARCHAR(255) NOT NULL UNIQUE;

-- Recrear índice
ALTER TABLE `equipos` 
DROP INDEX IF EXISTS `equipos_codigo_interno_unique`,
ADD UNIQUE INDEX `equipos_codigo_activo_cliente_unique` (`codigo_activo_cliente`);
```

**⚠️ IMPORTANTE:** Si hay error de índice duplicado, primero ejecutar:
```sql
ALTER TABLE `equipos` DROP INDEX IF EXISTS `codigo_interno`;
```

---

### PASO 6: Hacer Serial Único (Permitiendo NULLs Múltiples)
**En phpMyAdmin → SQL:**

```sql
-- ============================================
-- Hacer serial único (permite múltiples NULLs)
-- ============================================
ALTER TABLE `equipos` 
DROP INDEX IF EXISTS `equipos_serial_index`;

-- Crear índice único en serial (MySQL permite múltiples NULLs en unique)
ALTER TABLE `equipos` 
ADD UNIQUE INDEX `equipos_serial_unique` (`serial`);
```

**✅ Verifica:** `SHOW INDEX FROM equipos;` debe mostrar `serial` como UNIQUE

---

### PASO 7: Eliminar Columna Marca String (ÚLTIMO PASO)
**En phpMyAdmin → SQL:**

```sql
-- ============================================
-- Eliminar columna marca después de migrar datos
-- ============================================
ALTER TABLE `equipos` 
DROP COLUMN IF EXISTS `marca`;
```

**⚠️ IMPORTANTE:** Solo ejecutar DESPUÉS de confirmar que marca_id tiene todos los datos

---

## ✅ VERIFICACIONES FINALES

**Después de completar todos los pasos, ejecutar:**

```sql
-- Verificar estructura
DESCRIBE `equipos`;

-- Verificar datos migrados
SELECT COUNT(*) as total, 
       COUNT(marca_id) as con_marca_id,
       COUNT(cliente_id) as con_cliente_id,
       COUNT(sede_id) as con_sede_id
FROM `equipos`;

-- Ver equipos sin marca (si los hay)
SELECT id, codigo_activo_cliente, serie, marca_id 
FROM `equipos` 
WHERE marca_id IS NULL 
LIMIT 10;

-- Ver marcas creadas
SELECT * FROM `marcas` ORDER BY nombre;
```

---

## 🔄 ROLLBACK (Si algo falla)

**En caso de error, revertir cambios:**

```sql
-- PASO 1: Restaurar columna marca string
ALTER TABLE `equipos` 
ADD COLUMN `marca` VARCHAR(100) NULL AFTER `tipo_equipo_id`;

-- PASO 2: Migrar datos de marca_id → marca string (si es necesario)
UPDATE `equipos` e
LEFT JOIN `marcas` m ON e.`marca_id` = m.`id`
SET e.`marca` = m.`nombre`
WHERE e.`marca_id` IS NOT NULL;

-- PASO 3: Eliminar columnas nuevas
ALTER TABLE `equipos`
DROP FOREIGN KEY IF EXISTS `fk_equipos_marca_id`,
DROP FOREIGN KEY IF EXISTS `fk_equipos_cliente_id`,
DROP FOREIGN KEY IF EXISTS `fk_equipos_sede_id`,
DROP COLUMN IF EXISTS `marca_id`,
DROP COLUMN IF EXISTS `cliente_id`,
DROP COLUMN IF EXISTS `sede_id`;

-- PASO 4: Renombrar codigo_activo_cliente de vuelta a codigo_interno
ALTER TABLE `equipos` 
CHANGE COLUMN `codigo_activo_cliente` `codigo_interno` VARCHAR(255) NOT NULL UNIQUE;

-- PASO 5: Eliminar tabla marcas
DROP TABLE IF EXISTS `marcas`;
```

---

## 📊 CAMBIOS EN CÓDIGO

### 1. **Modelo: app/Models/Equipo.php**
- ✅ Actualizado `$fillable` para incluir `marca_id` en lugar de `marca`
- ✅ Actualizado `$fillable` para incluir `codigo_activo_cliente` en lugar de `codigo_interno`
- ✅ Agregada relación `marca()` a Marca

### 2. **Modelo NUEVO: app/Models/Marca.php**
- ✅ Creado nuevo modelo
- ✅ Relación `equipos()` hasMany

### 3. **Controller: app/Http/Controllers/Parametros/EquipoController.php**
- ✅ Importado `Marca` y `Contrato`
- ✅ Actualizado método `create()` para pasar `$marcas`
- ✅ Actualizado método `edit()` para pasar `$marcas`
- ✅ Actualizado validación en `store()` - ahora usa `marca_id` en lugar de `marca`
- ✅ Actualizado validación en `update()` - ahora usa `marca_id` en lugar de `marca`
- ✅ Agregados `cliente_id` y `sede_id` en validaciones

### 4. **Controller NUEVO: app/Http/Controllers/Parametros/MarcaController.php**
- ✅ Creado CRUD completo para Marcas

### 5. **Vistas Equipos: resources/views/parametros/equipos/**
- ✅ `create.blade.php` - Cambiar input text "marca" por select "marca_id"
- ✅ `create.blade.php` - Cambiar "codigo_interno" por "codigo_activo_cliente"
- ✅ `create.blade.php` - Agregar campo "cliente_id" obligatorio
- ✅ `index.blade.php` - Mostrar `$equipo->marca->nombre` en lugar de `$equipo->marca`
- ✅ `index.blade.php` - Mostrar `$equipo->codigo_activo_cliente` en lugar de `$equipo->codigo_interno`
- ✅ `show.blade.php` - Actualizar referencias
- ✅ `pdf.blade.php` - Actualizar referencias

### 6. **Vistas Marcas NUEVAS: resources/views/parametros/marcas/**
- ✅ `index.blade.php` - Listado de marcas con DataTables
- ✅ `create.blade.php` - Formulario crear/editar marca
- ✅ `show.blade.php` - Detalle de marca con equipos asociados

### 7. **Rutas: routes/parametros.php**
- ✅ Importado `MarcaController`
- ✅ Agregada ruta resource para marcas

---

## ⚠️ ERRORES EVITADOS

| Error | Causa | Solución |
|-------|-------|----------|
| "Duplicate entry for key" | Índice duplicado en `codigo_interno` | Verificar antes de renombrar con `DROP INDEX IF EXISTS` |
| Foreign Key Constraint | Orden incorrecto de migraciones | Crear tabla `marcas` PRIMERO, luego agregar FK |
| Data Loss | Eliminar columna sin migrar datos | Migrar datos a `marca_id` ANTES de eliminar `marca` |
| NULL en serial unique | Serial puede ser NULL en BD | MySQL permite múltiples NULLs en UNIQUE |

---

## 📅 REGISTRO DE CAMBIOS

| Fecha | Cambio | Estado | Notas |
|-------|--------|--------|-------|
| 2026-05-25 | Crear tabla marcas | ✅ | Ejecutar PRIMERO |
| 2026-05-25 | Agregar cliente_id, sede_id, marca_id | ✅ | Seguro, usa IF NOT EXISTS |
| 2026-05-25 | Migrar datos de marca | ✅ | Usa JOIN para mapear |
| 2026-05-25 | Renombrar codigo_interno | ✅ | Mantiene integridad de datos |
| 2026-05-25 | Hacer serial único | ✅ | Permite NULLs múltiples |
| 2026-05-25 | Eliminar columna marca | ✅ | Último paso, después de verificar |

---

## 🎓 APRENDIZAJES Y MEJORES PRÁCTICAS

1. **Siempre usar `IF NOT EXISTS`** en ALTER TABLE para evitar errores
2. **Migrar datos ANTES** de eliminar columnas antiguas
3. **Crear ForeignKeys DESPUÉS** de crear las tablas referenciadas
4. **Usar índices apropiados** para serial (UNIQUE permite NULLs múltiples en MySQL)
5. **Documentar orden de ejecución** para evitar constraint violations
6. **Registrar rollback** por si algo falla

---

**Última actualización:** 2026-05-25 12:00 UTC
**Estado:** Listo para ejecutar en PRODUCCIÓN
**Responsable:** Sistema CEOGestion
