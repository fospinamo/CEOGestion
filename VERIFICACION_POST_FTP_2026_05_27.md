# ✔️ VERIFICACIÓN POST-FTP (Script de Testing)
## 2026-05-27 | Después de copiar archivos a producción

---

## 🎯 OBJETIVO

Validar que todos los archivos están en lugar correcto y el sistema funciona después de la subida FTP.

---

## 📋 PASO 1: Verificar Archivos en FTP

### Via SSH/Terminal (si tienes acceso)
```bash
# Verificar archivos NUEVOS
ls -la app/Models/Marca.php
ls -la app/Http/Controllers/Parametros/MarcaController.php
ls -la resources/views/parametros/marcas/

# Verificar archivos MODIFICADOS
ls -la app/Models/Equipo.php
ls -la app/Http/Controllers/Parametros/EquipoController.php
ls -la resources/views/parametros/equipos/
ls -la routes/parametros.php

# Verificar permisos
find app/Models -type f -name "*.php" -exec ls -l {} \;
find resources/views/parametros -type f -exec ls -l {} \;
```

### Via FTP Manager (cPanel/FileZilla)
```
Navegar a cada carpeta y verificar:
✅ Archivos presentes
✅ Tamaño > 0 KB
✅ Fecha de modificación = hoy
✅ Permisos: 644 (archivos), 755 (carpetas)
```

---

## 🧪 PASO 2: Testing en Navegador

### Test 1: Acceso a Aplicación
```
URL: https://[tu_dominio]/
✅ Carga sin errores 500
✅ Login funciona
```

### Test 2: Listar Equipos
```
Ir a: Parámetros → Equipos
✅ Página carga sin errores
✅ Tabla con datos visible
✅ Columna "Código" = codigo_activo_cliente (no codigo_interno)
✅ Columna "Marca" = nombre de marca (ej: "DELL", "HP")
✅ Sin valor NULL en marca
```

**Si hay ERROR:**
```
❌ Error 500 o "Undefined column"
→ Verificar que equipos/index.blade.php fue actualizado
→ Revisar logs: storage/logs/laravel.log

❌ Columna "Marca" muestra NULL
→ Verificar que migración SQL paso 6 se ejecutó
→ Ver: SELECT COUNT(*) FROM equipos WHERE marca_id IS NOT NULL;
```

### Test 3: Ver un Equipo
```
Click en cualquier equipo del listado
✅ Detalle carga sin errores
✅ Mostrar Código: codigo_activo_cliente
✅ Mostrar Marca: nombre (relación OK)
✅ Si tiene Cliente: mostrar
✅ Si tiene Sede: mostrar
```

**Si hay ERROR:**
```
❌ "Undefined method marca()" 
→ Verificar que app/Models/Equipo.php tiene la relación:
   public function marca() { return $this->belongsTo(Marca::class); }

❌ "Call to undefined method on null"
→ Datos relacionados NULL, verificar integridad BD
```

### Test 4: Crear Equipo NUEVO
```
Click: Parámetros → Equipos → Nuevo

Llenar formulario:
  - Empresa: Seleccionar
  - Cliente: Seleccionar ✅ NUEVO CAMPO
  - Sede: Seleccionar ✅ NUEVO CAMPO
  - Área: Seleccionar
  - Tipo Equipo: Seleccionar
  - Código Activo Cliente: "TEST-001" (antes "Código Interno")
  - Serial: "SN12345678" (único)
  - Marca: Seleccionar ✅ DEBE SER SELECT (no input text)
  - Modelo: "ProDesk"
  - Estado: OPERATIVO

Click: Registrar

✅ Guardado sin errores
✅ Redirige a listado
✅ Equipo nuevo aparece
✅ Marca muestra nombre
✅ Cliente visible (si se llenó)
✅ Sede visible (si se llenó)
```

**Si hay ERROR:**
```
❌ Error "marca" en validación
→ Verificar EquipoController.php - store() validación:
   'marca_id' => 'required|exists:marcas,id'

❌ "UNIQUE constraint failed on serial"
→ Serial repetido, intentar con otro
→ Miración SQL paso 8 creó índice UNIQUE

❌ Cliente_id no se guarda
→ Verificar fillable en app/Models/Equipo.php:
   protected $fillable = [..., 'cliente_id', 'sede_id', ...]
```

### Test 5: Ver nueva página MARCAS
```
Ir a: Parámetros → Marcas

✅ Página existe (no 404)
✅ Carga tabla de marcas
✅ Mostrar columnas: Nombre, Descripción, Cantidad Equipos, Estado
✅ Botón "Nueva Marca" visible
✅ Botones de acción: Ver, Editar, Eliminar
```

**Si hay ERROR:**
```
❌ Página no existe (404)
→ Verificar que routes/parametros.php tiene:
   Route::resource('marcas', MarcaController::class);

❌ "Class MarcaController not found"
→ Verificar archivo:
   app/Http/Controllers/Parametros/MarcaController.php existe
→ Verificar namespace está correcto en el archivo
```

### Test 6: Crear MARCA nueva
```
Click: Parámetros → Marcas → Nueva Marca

Llenar:
  - Nombre: "Apple"
  - Descripción: "Computadoras Apple"
  - Logo URL: [opcional]
  - Estado: Activo (checkbox)

Click: Registrar

✅ Guardado sin errores
✅ Aparece en listado
✅ Contador de equipos = 0
```

### Test 7: Editar MARCA
```
Click en una marca existente → Editar

Cambiar nombre: "Dell Inc" → "Dell Computers"
Click: Guardar

✅ Actualizado sin errores
✅ Vuelve a listado
✅ Nombre cambiado visible
```

### Test 8: Integridad Referencial
```
Click: Parámetros → Marcas

Intentar eliminar marca con equipos:
  ❌ DEBE MOSTRAR ERROR: "No se puede eliminar marca con equipos"

Crear marca nueva (sin equipos):
  ✅ Debe permitir eliminar
  ✅ Desaparece de listado
```

---

## 🔍 PASO 3: Revisar Logs

### Ver Errores Laravel
```
Via SSH:
  tail -f storage/logs/laravel.log

Via FTP/cPanel:
  Descargar: storage/logs/laravel.log
  Buscar: ERROR, ErrorException, QueryException

Debe estar VACÍO de errores o mostrar solo INFO
```

### Verificar BD
```
phpMyAdmin → Base de datos simotec_ceogestion_prod

SELECT 
  COUNT(*) as total,
  COUNT(marca_id) as con_marca,
  COUNT(cliente_id) as con_cliente,
  COUNT(codigo_activo_cliente) as tiene_codigo
FROM equipos;

Resultado esperado:
  - total: 85+ (todos los equipos)
  - con_marca: 85+ (100% con FK)
  - con_cliente: 0-5 (datos nuevos, NULL permitido)
  - tiene_codigo: 85+ (todos renombrados)
```

---

## 📊 RESUMEN VERIFICACIÓN

| Test | Resultado | Acción Si Falla |
|------|-----------|-----------------|
| Acceso app | ✅ OK | Revisar permisos FTP |
| Listar equipos | ✅ OK | Verificar create.blade.php |
| Ver equipo | ✅ OK | Verificar Equipo.php relación |
| Crear equipo | ✅ OK | Verificar EquipoController validación |
| Página Marcas | ✅ OK | Verificar routes/parametros.php |
| Crear marca | ✅ OK | Verificar MarcaController.php |
| Editar marca | ✅ OK | Idem |
| Integridad | ✅ OK | Revisar BD e índices |
| Logs | ✅ Sin errores | Revisar storage/logs/ |

---

## ⚠️ ROLLBACK RÁPIDO (Si falla todo)

### Opción A: Restaurar BD
```
phpMyAdmin → Importar:
  Seleccionar: ceogestion_backup_2026_05_27_ANTES.sql
  Click: Ejecutar
  
BD vuelve a estado anterior ✅
```

### Opción B: Restaurar Archivos PHP
```
FTP Manager:
  1. Eliminar archivos NUEVOS (carpeta marcas/)
  2. Reemplazar archivos MODIFICADOS desde backup
  
Alternativa: Subir versión anterior de archivos
```

---

## 🎯 CHECKLIST FINAL

```
✅ Archivos en FTP (10 archivos)
✅ Permisos correctos (644/755)
✅ Acceso a aplicación
✅ Listar equipos funciona
✅ Detalle equipos OK
✅ Crear equipo OK (con cliente_id, sede_id)
✅ Página Marcas existe
✅ CRUD Marcas funciona
✅ Integridad BD verificada
✅ Logs sin errores
✅ Tests completados exitosamente

ESTADO: 🟢 IMPLEMENTACIÓN EXITOSA
```

---

## 📝 REGISTRO DE VERIFICACIÓN

```
Fecha: _______________
Hora: _______________
Ejecutado por: _______________

TESTS PASADOS:
  [ ] Acceso aplicación
  [ ] Listar equipos
  [ ] Ver equipo
  [ ] Crear equipo
  [ ] Página Marcas
  [ ] CRUD Marcas
  [ ] Integridad BD
  [ ] Logs OK

PROBLEMAS ENCONTRADOS:
_________________________________
_________________________________

ACCIONES TOMADAS:
_________________________________
_________________________________

ESTADO FINAL:
  [ ] ✅ TODO OK - Implementación completada
  [ ] ⚠️ ALERTAS - Ver detalles arriba
  [ ] ❌ ERROR - Hacer rollback

FIRMA: _______________
FECHA: _______________
```

---

**Documento:** Verificación Post-FTP
**Creado:** 2026-05-27
**Estado:** Listo para usar
**Próximo:** Ejecutar tests según pasos arriba

✨ **¡A verificar!** ✨
