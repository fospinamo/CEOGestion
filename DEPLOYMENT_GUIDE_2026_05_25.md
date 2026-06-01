# 🚀 GUÍA DE DEPLOYMENT - Cambios Equipos y Marcas
## Producción: simotec_ceogestion_prod
## Fecha: 2026-05-25

---

## 📌 RESUMEN

Se está implementando:
1. **Nueva tabla `marcas`** - Parametrización de marcas
2. **Nuevas columnas `cliente_id`, `sede_id`, `marca_id`** - Trazabilidad
3. **Renombrar `codigo_interno` → `codigo_activo_cliente`** - Semántica
4. **Serial único** - Prevenir duplicados
5. **Nuevo CRUD de Marcas** - Interface de gestión

---

## ⚠️ REQUISITOS PREVIOS

**OBLIGATORIO:**
- [ ] Backup completo de BD `simotec_ceogestion_prod`
- [ ] Acceso a phpMyAdmin
- [ ] Conexión estable a servidor
- [ ] NO ejecutar durante horas pico
- [ ] Revisor de cambios disponible

**Archivos necesarios:**
- ✅ `SQL_EQUIPOS_MARCAS_2026_05_25.sql` - Sentencias SQL
- ✅ Código PHP actualizado (Controllers, Views, Models)
- ✅ Este documento

---

## 🔄 PASO 1: BACKUP

### Opción A: phpMyAdmin
```
1. Ir a: phpMyAdmin → Base de datos simotec_ceogestion_prod
2. Click en "Exportar"
3. Formato: SQL
4. Click "Ejecutar"
5. Guardar archivo: ceogestion_backup_2026_05_25_ANTES.sql
```

### Opción B: Línea de Comandos (Si tienes acceso)
```bash
mysqldump -u usuario -p simotec_ceogestion_prod > backup_2026_05_25.sql
```

---

## 🔧 PASO 2: EJECUTAR SENTENCIAS SQL

### 2.1 Abrir phpMyAdmin
```
URL: https://[hosting]/phpmyadmin
Usuario: [tu_usuario]
Contraseña: [tu_contraseña]
Seleccionar BD: simotec_ceogestion_prod
```

### 2.2 Copiar y Ejecutar SQL

**Ir a Tab "SQL"**

```
Copy-Paste el contenido de: SQL_EQUIPOS_MARCAS_2026_05_25.sql
Click "Ejecutar"
```

**⚠️ IMPORTANTE: Ejecutar TODOS los pasos en ORDEN**

### 2.3 Verificar Cada Paso

Después de cada sección (PASO 1, PASO 2, etc.), ejecutar verificación:

```sql
-- DESPUÉS DE PASO 1
SELECT * FROM marcas;  -- Debe existir tabla vacía

-- DESPUÉS DE PASO 2
SELECT COUNT(*) FROM marcas;  -- Debe tener marcas

-- DESPUÉS DE PASO 3-5
DESCRIBE equipos;  -- Verificar cliente_id, sede_id, marca_id

-- DESPUÉS DE PASO 6
SELECT COUNT(*) FROM equipos WHERE marca_id IS NOT NULL;

-- DESPUÉS DE PASO 7
DESCRIBE equipos;  -- Debe tener codigo_activo_cliente

-- DESPUÉS DE PASO 9 (FINAL)
SELECT COUNT(*) as total, 
       COUNT(marca_id) as con_marca_id,
       COUNT(cliente_id) as con_cliente_id,
       COUNT(sede_id) as con_sede_id
FROM equipos;
```

---

## 📝 PASO 3: ACTUALIZAR CÓDIGO EN PRODUCCIÓN

### 3.1 Subir Archivos

**Archivos a actualizar en servidor:**

```
app/
  ├── Http/Controllers/Parametros/
  │   ├── EquipoController.php          ✅ MODIFICADO
  │   └── MarcaController.php            ✅ NUEVO
  └── Models/
      ├── Equipo.php                     ✅ MODIFICADO
      └── Marca.php                      ✅ NUEVO

resources/views/parametros/
  ├── equipos/
  │   ├── create.blade.php               ✅ MODIFICADO
  │   ├── index.blade.php                ✅ MODIFICADO
  │   ├── show.blade.php                 ✅ MODIFICADO
  │   └── pdf.blade.php                  ✅ MODIFICADO
  └── marcas/                            ✅ NUEVA CARPETA
      ├── index.blade.php                ✅ NUEVO
      ├── create.blade.php               ✅ NUEVO
      └── show.blade.php                 ✅ NUEVO

routes/
  └── parametros.php                     ✅ MODIFICADO
```

### 3.2 FTP/SFTP - Subir Archivos

**Herramientas:** FileZilla, WinSCP, cPanel File Manager

**Pasos:**
1. Conectar a servidor
2. Navegar a raíz de la aplicación
3. Subir archivos manteniendo estructura
4. Verificar permisos: 644 (archivos), 755 (carpetas)

---

## 🧪 PASO 4: TESTING EN PRODUCCIÓN

### 4.1 Verificar Base de Datos

```
phpMyAdmin → Base de datos → Tablas
✅ Debe existir tabla "marcas"
✅ Tabla "equipos" debe tener:
   - cliente_id
   - sede_id
   - marca_id
   - codigo_activo_cliente
   - serie (con UNIQUE)
   - NO debe tener "marca" (string)
```

### 4.2 Acceder a la Aplicación

```
URL: https://[dominio]/
Login: Admin (si existe)
```

### 4.3 Pruebas Funcionales

#### Test 1: Listar Equipos
```
Ir a: Parámetros → Equipos
✅ Debe mostrar tabla sin errores
✅ Columna "Marca" debe mostrar nombre (marca.nombre)
✅ Columna "Código" debe mostrar codigo_activo_cliente
✅ Editar equipo debe mostrar marca_id en select
```

#### Test 2: Crear Equipo
```
Ir a: Parámetros → Equipos → Nuevo
Llenar:
  - Empresa
  - Cliente ✅ NUEVO CAMPO
  - Sede
  - Área
  - Tipo Equipo
  - Código Activo Cliente (antes "Código Interno")
  - Marca ✅ Debe ser SELECT (no input text)
  - Modelo
  - Serial (debe ser único)
  - Guardar

✅ Debe guardar sin errores
✅ cliente_id debe guardarse
✅ sede_id debe guardarse
✅ marca_id debe guardarse
```

#### Test 3: Editar Equipo
```
Ir a: Parámetros → Equipos → Click en Equipo
✅ Debe cargar datos correctamente
✅ Marca debe mostrar select con opción seleccionada
✅ Código Activo Cliente debe tener valor
✅ Cliente debe estar pre-seleccionado
✅ Actualizar y guardar debe funcionar
```

#### Test 4: CRUD de Marcas ✅ NUEVO
```
Ir a: Parámetros → Marcas
✅ Debe mostrar tabla con marcas
✅ Click "Nueva Marca" debe abrir formulario
✅ Crear marca nueva
✅ Editar marca existente
✅ Ver detalle de marca (con equipos asociados)
✅ Intentar eliminar marca con equipos debe mostrar error
✅ Eliminar marca SIN equipos debe funcionar
```

#### Test 5: Reportes
```
Ir a: Parámetros → Equipos
✅ Descargar PDF debe funcionar
✅ Descargar Excel debe funcionar
✅ Datos deben mostrar codigo_activo_cliente y marca.nombre
```

### 4.4 Revisar Logs

```
SSH/Terminal:
tail -f storage/logs/laravel.log

Buscar ERRORES:
- ErrorException
- QueryException
- ForeignKeyConstraintViolation
```

---

## ✅ CHECKLIST DE VERIFICACIÓN FINAL

### Base de Datos
- [ ] Tabla `marcas` existe y tiene datos
- [ ] Tabla `equipos` tiene columnas:
  - [ ] cliente_id
  - [ ] sede_id
  - [ ] marca_id
  - [ ] codigo_activo_cliente
  - [ ] serial (UNIQUE)
- [ ] NO existe columna `marca` (string)
- [ ] Todas las FK existentes

### Código
- [ ] `MarcaController.php` existe en producción
- [ ] `Marca.php` modelo existe
- [ ] Vistas de marcas existen
- [ ] Rutas `/parametros/marcas` funcionan

### Funcionalidad
- [ ] Listar equipos sin errores
- [ ] Crear equipo - Guardar cliente_id, sede_id, marca_id
- [ ] Editar equipo - Cargar datos correctos
- [ ] Ver equipos - Mostrar marca.nombre
- [ ] CRUD de Marcas - Todas las operaciones
- [ ] Exportar PDF/Excel sin errores
- [ ] Logs sin ErrorException

---

## 🚨 EN CASO DE ERROR

### Error 1: "Constraint violation"

```sql
-- Verificar FK
SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = 'equipos' AND COLUMN_NAME = 'marca_id';

-- Si falla, revertir último paso y verificar datos
```

### Error 2: "Duplicate entry"

```sql
-- Verificar duplicados en serie
SELECT serial, COUNT(*) FROM equipos 
WHERE serial IS NOT NULL 
GROUP BY serial 
HAVING COUNT(*) > 1;

-- Si hay duplicados, resolver manualmente
```

### Error 3: "Column already exists"

**Solución:** La migración ya fue ejecutada
- Verificar: `DESCRIBE equipos;`
- Si columnas existen, ignorar

### Error 4: Rollback Completo

```sql
-- Ejecutar archivo: SQL_EQUIPOS_MARCAS_2026_05_25.sql
-- Sección: "EN CASO DE ERROR: ROLLBACK COMPLETO"
-- EJECUTAR TODOS LOS PASOS EN ORDEN
```

---

## 📞 SOPORTE

### Preguntas Frecuentes

**P: ¿Qué pasa con los equipos existentes?**
R: ✅ Se conservan. Se migran marcas string a FK, se agregan cliente_id y sede_id como NULL (opcionales).

**P: ¿Puedo hacer rollback?**
R: ✅ SÍ. Ejecutar sección "ROLLBACK COMPLETO" en `SQL_EQUIPOS_MARCAS_2026_05_25.sql`

**P: ¿Afecta a usuarios que están usando la aplicación?**
R: ✅ Recomendado ejecutar en horario de bajo uso (madrugada)

**P: ¿Se puede hacer en etapas?**
R: ❌ NO. Deben ejecutarse todos los pasos en orden, sino faltan dependencias.

### Contacto Técnico

- **Documento base:** `CHANGELOG_EQUIPOS_2026_05_25.md`
- **Control de cambios:** `CONTROL_CAMBIOS_EQUIPOS_2026_05_25.md`
- **Sentencias SQL:** `SQL_EQUIPOS_MARCAS_2026_05_25.sql`
- **Responsable:** Sistema CEOGestion
- **Fecha:** 2026-05-25

---

## ✨ BENEFICIOS DESPUÉS DEL DEPLOYMENT

✅ **Cliente identificable** - Cada equipo sabe a quién pertenece
✅ **Marca parametrizada** - Sin duplicados, cambios centralizados
✅ **Mejor trazabilidad** - cliente_id + sede_id + codigo_activo_cliente
✅ **Series únicas** - Previene duplicados
✅ **Gestión de marcas** - CRUD con verificación de integridad
✅ **Semántica mejorada** - Nombres más descriptivos

---

**Estado:** 🟢 Listo para producción
**Última revisión:** 2026-05-25 12:00 UTC
**Aprobado por:** Sistema CEOGestion
