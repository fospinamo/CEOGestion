# ✅ CHECKLIST RÁPIDO - Ejecución en Producción
## 2026-05-25 | BD: simotec_ceogestion_prod

---

## 🟢 ANTES DE EMPEZAR

- [ ] Backup de BD realizado: `ceogestion_backup_2026_05_25_ANTES.sql`
- [ ] Acceso a phpMyAdmin ✅
- [ ] Acceso FTP/SFTP al servidor ✅
- [ ] Este archivo a mano
- [ ] `SQL_EQUIPOS_MARCAS_2026_05_25.sql` descargado
- [ ] Horario bajo en producción ✅

---

## 📊 FASE 1: BASE DE DATOS (phpMyAdmin)

### 🔶 PASO 1: Crear tabla MARCAS
```
phpMyAdmin → SQL
Copy-Paste líneas 1-25 (CREATE TABLE marcas)
Ejecutar
Verificar: SELECT * FROM marcas;  → Debe estar vacía ✅
```
- [ ] Tabla creada

### 🔶 PASO 2: Migrar MARCAS existentes
```
Copy-Paste líneas 33-40 (INSERT INTO marcas)
Ejecutar
Verificar: SELECT COUNT(*) FROM marcas;  → Debe mostrar número > 0 ✅
```
- [ ] Marcas migradas

### 🔶 PASO 3: Agregar CLIENTE_ID
```
Copy-Paste líneas 48-67 (ALTER TABLE equipos ADD cliente_id...)
Ejecutar
Verificar: DESCRIBE equipos;  → Buscar cliente_id ✅
```
- [ ] Cliente_id agregado

### 🔶 PASO 4: Agregar SEDE_ID
```
Copy-Paste líneas 70-89 (ALTER TABLE equipos ADD sede_id...)
Ejecutar
Verificar: DESCRIBE equipos;  → Buscar sede_id ✅
```
- [ ] Sede_id agregado

### 🔶 PASO 5: Agregar MARCA_ID
```
Copy-Paste líneas 92-111 (ALTER TABLE equipos ADD marca_id...)
Ejecutar
Verificar: DESCRIBE equipos;  → Buscar marca_id ✅
```
- [ ] Marca_id agregado

### 🔶 PASO 6: Migrar DATOS marca string → marca_id
```
Copy-Paste líneas 114-121 (UPDATE equipos JOIN marcas...)
Ejecutar
Verificar: SELECT COUNT(*) FROM equipos WHERE marca_id IS NOT NULL;
          → Debe ser número de equipos con marca ✅
```
- [ ] Datos migrados

### 🔶 PASO 7: Renombrar CODIGO_INTERNO
```
Copy-Paste líneas 124-137 (ALTER TABLE equipos CHANGE...)
Ejecutar
Verificar: DESCRIBE equipos;  → Buscar codigo_activo_cliente ✅
```
- [ ] Código renombrado

### 🔶 PASO 8: Serial UNIQUE
```
Copy-Paste líneas 140-150 (ALTER TABLE equipos DROP INDEX...)
Ejecutar
Verificar: SHOW INDEX FROM equipos;  → Serial debe ser Non_unique=0 ✅
```
- [ ] Serial único

### 🔶 PASO 9: Eliminar MARCA (string)
```
⚠️ VERIFICACIÓN CRÍTICA PRIMERO:
SELECT COUNT(*) as total, COUNT(marca_id) as con_marca_id 
FROM equipos;
→ total DEBE = con_marca_id ✅

Copy-Paste línea 157 (ALTER TABLE equipos DROP COLUMN marca)
Ejecutar
Verificar: DESCRIBE equipos;  → NO debe aparecer "marca" ✅
```
- [ ] Columna marca eliminada

### 🔶 VERIFICACIONES FINALES (BD)
```
Copy-Paste líneas 162-187 (Sección de verificaciones)
Ejecutar TODOS y guardar resultados
```
- [ ] Estructura correcta
- [ ] Datos completos
- [ ] Marcas creadas
- [ ] Sin equipos sin marca (si debería tenerla)

---

## 💻 FASE 2: CÓDIGO EN PRODUCCIÓN (FTP)

### 📂 Subir Archivos

**Usar:** FileZilla, WinSCP, o cPanel File Manager

```
Destino: Raíz de CEOGestion

CARPETAS A ACTUALIZAR:
  ✅ app/Http/Controllers/Parametros/
     - EquipoController.php (MODIFICADO)
     - MarcaController.php (NUEVO) ← Crear
  
  ✅ app/Models/
     - Equipo.php (MODIFICADO)
     - Marca.php (NUEVO) ← Crear
  
  ✅ resources/views/parametros/equipos/
     - create.blade.php (MODIFICADO)
     - index.blade.php (MODIFICADO)
     - show.blade.php (MODIFICADO)
     - pdf.blade.php (MODIFICADO)
  
  ✅ resources/views/parametros/marcas/ (CREAR CARPETA)
     - index.blade.php (NUEVO)
     - create.blade.php (NUEVO)
     - show.blade.php (NUEVO)
  
  ✅ routes/
     - parametros.php (MODIFICADO)
```

- [ ] Archivos subidos
- [ ] Permisos correctos (644 archivos, 755 carpetas)

---

## 🧪 FASE 3: TESTING (Navegador)

### 1️⃣ Acceder a Aplicación
```
URL: https://[tu_dominio]/
Login con credenciales admin
```
- [ ] Acceso exitoso

### 2️⃣ Test: Listar Equipos
```
Ir a: Parámetros → Equipos
✅ No debe haber errores 500
✅ Tabla debe mostrar datos
✅ Columna "Código" = codigo_activo_cliente
✅ Columna "Marca" = nombre de marca (no NULL)
```
- [ ] Listado funciona

### 3️⃣ Test: Ver Equipo
```
Click en un equipo existente
✅ Debe mostrar:
   - Código Activo Cliente
   - Serial
   - Marca (nombre)
   - Cliente (si tiene)
   - Sede (si tiene)
✅ Sin errores
```
- [ ] Detalle funciona

### 4️⃣ Test: Crear Equipo
```
Click "Nuevo Equipo"
Llenar todos los campos:
  - Empresa
  - Cliente ← NUEVO
  - Sede
  - Área
  - Tipo
  - Código Activo Cliente ← Antes "Código Interno"
  - Serial (único)
  - Marca ← SELECT (no input text)
  - Modelo
  - Otros...
Click "Registrar"
✅ Debe guardar sin errores
✅ Redirigir a listado
✅ Equipo debe aparecer con marca y cliente
```
- [ ] Crear funciona

### 5️⃣ Test: Editar Equipo
```
Click editar en equipo existente
✅ Debe cargar todos los datos
✅ Marca debe mostrar SELECT con opción seleccionada
✅ Cliente debe estar pre-cargado
✅ Cambiar marca y guardar
✅ Actualización debe funcionar
```
- [ ] Editar funciona

### 6️⃣ Test: CRUD de Marcas ✅ NUEVO
```
Click en menú: Parámetros → Marcas
✅ Página debe cargar (nueva)
✅ Mostrar tabla con marcas
✅ Click "Nueva Marca" → Formulario
  - Llenar nombre, descripción, estado
  - Click "Registrar"
  - Debe aparecer en listado
✅ Click editar marca
  - Cambiar datos
  - Guardar
  - Actualizar OK
✅ Click ver marca
  - Mostrar detalle
  - Listar equipos con esa marca
  - Ver timestamp de creación
✅ Intentar eliminar marca con equipos
  - Debe mostrar error "No se puede eliminar"
✅ Crear marca sin equipos
  - Eliminar
  - Debe funcionar
```
- [ ] Marcas funcionan

### 7️⃣ Test: Exportar
```
En Equipos, click "Descargar PDF"
✅ PDF debe generar correctamente
✅ Mostrar codigo_activo_cliente
✅ Mostrar marca.nombre
✅ Descargar sin errores

Click "Descargar Excel"
✅ Excel debe generar
✅ Mismas columnas
✅ Descargar OK
```
- [ ] Exportar funciona

### 8️⃣ Test: Filtros
```
En Equipos, aplicar filtros:
  - Por Cliente (NUEVO)
  - Por Estado
  - Por Tipo
Click Filtrar
✅ Resultados correctos
✅ Sin errores
```
- [ ] Filtros funcionan

---

## 🔍 FASE 4: VERIFICACIÓN DE LOGS

```
SSH / Terminal:
tail -f storage/logs/laravel.log

Buscar PROBLEMAS:
❌ ErrorException
❌ QueryException
❌ ForeignKeyConstraintViolation
❌ Method does not exist

✅ Si ve solo [200] → Excelente
```

- [ ] Logs sin errores

---

## 🎉 FASE 5: FINALIZACIÓN

### Checklist Final
- [ ] BD actualizada correctamente
- [ ] Código en producción
- [ ] Todas las pruebas OK
- [ ] Logs sin errores
- [ ] Backup anterior guardado (rollback plan)

### Documento de Cierre
```
Guardar este checklist completo con:
- Fecha/hora de inicio
- Fecha/hora de fin
- Nombre de usuario que ejecutó
- Resultados de cada fase
- Cualquier problema encontrado
```

### Notificación
```
📧 Mensaje a equipo de soporte:
"✅ Actualización de Equipos y Marcas completada exitosamente
Cambios principales:
- Tabla 'marcas' parametrizada
- Campos cliente_id, sede_id en equipos
- Serie única
- Nuevo CRUD de Marcas
Todas las pruebas OK. Sistema operativo."
```

---

## 🚨 SI ALGO FALLA

### Plan de Rollback Inmediato

**Opción A: Rollback Total (30 min)**
```
1. En phpMyAdmin → SQL
2. Copy-Paste líneas 165-200 del archivo SQL
3. Ejecutar TODOS los pasos en orden (ROLLBACK COMPLETO)
4. Esperar confirmación
5. Restaurar código anterior desde backup
6. Sistema vuelve a estado anterior
```

**Opción B: Restaurar BD desde Backup (10 min)**
```
1. phpMyAdmin → Base de datos → Importar
2. Seleccionar: ceogestion_backup_2026_05_25_ANTES.sql
3. Click Ejecutar
4. Confirmar
5. Restaurar código anterior
```

---

## 📝 REGISTRO DE EJECUCIÓN

```
FECHA: ____________
HORA INICIO: ____________
HORA FIN: ____________

EJECUTADO POR: ____________
VERIFICADO POR: ____________

FASE 1 (BD):        ✅ OK ❌ ERROR ⏸️ EN PROGRESO
FASE 2 (Código):    ✅ OK ❌ ERROR ⏸️ EN PROGRESO
FASE 3 (Testing):   ✅ OK ❌ ERROR ⏸️ EN PROGRESO
FASE 4 (Logs):      ✅ OK ❌ ERROR ⏸️ EN PROGRESO

PROBLEMAS ENCONTRADOS:
_________________________________
_________________________________

NOTAS:
_________________________________
_________________________________
_________________________________

ESTADO FINAL: ✅ COMPLETADO ❌ CON ERRORES
```

---

**Documentos de referencia:**
- 📋 `CHANGELOG_EQUIPOS_2026_05_25.md` - Detalles completos
- 🔍 `CONTROL_CAMBIOS_EQUIPOS_2026_05_25.md` - Registro de cambios
- 🚀 `DEPLOYMENT_GUIDE_2026_05_25.md` - Guía detallada
- 💾 `SQL_EQUIPOS_MARCAS_2026_05_25.sql` - Sentencias SQL

**¡Éxito en el deployment!** 🚀
