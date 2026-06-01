# 📦 ARCHIVOS Y SENTENCIAS PARA PRODUCCIÓN
## Fecha: 2026-05-27 | BD: ✅ Local actualizada | Producción: ⏳ Pendiente

---

## 📂 ARCHIVOS A COPIAR VIA FTP (11 archivos)

### 🆕 NUEVOS (5 archivos)

```
ORIGEN LOCAL                                      → DESTINO FTP
c:\xampp\htdocs\CEOGestion\app\Models\Marca.php
    → app/Models/Marca.php

c:\xampp\htdocs\CEOGestion\app\Http\Controllers\Parametros\MarcaController.php
    → app/Http/Controllers/Parametros/MarcaController.php

c:\xampp\htdocs\CEOGestion\resources\views\parametros\marcas\index.blade.php
    → resources/views/parametros/marcas/index.blade.php

c:\xampp\htdocs\CEOGestion\resources\views\parametros\marcas\create.blade.php
    → resources/views/parametros/marcas/create.blade.php

c:\xampp\htdocs\CEOGestion\resources\views\parametros\marcas\show.blade.php
    → resources/views/parametros/marcas/show.blade.php
```

---

### 📝 MODIFICADOS (6 archivos)

```
ORIGEN LOCAL                                      → DESTINO FTP (REEMPLAZAR)
c:\xampp\htdocs\CEOGestion\app\Models\Equipo.php
    → app/Models/Equipo.php

c:\xampp\htdocs\CEOGestion\app\Http\Controllers\Parametros\EquipoController.php
    → app/Http/Controllers/Parametros/EquipoController.php

c:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\create.blade.php
    → resources/views/parametros/equipos/create.blade.php

c:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\index.blade.php
    → resources/views/parametros/equipos/index.blade.php

c:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\show.blade.php
    → resources/views/parametros/equipos/show.blade.php

c:\xampp\htdocs\CEOGestion\resources\views\layouts\app.blade.php
    → resources/views/layouts/app.blade.php
```

---

## 🗂️ RESUMEN CARPETA NUEVA

```
Crear en FTP (si no existe):
resources/views/parametros/marcas/

Permisos: 755 (carpeta)
```

---

## 🔐 PERMISOS EN FTP

**Todos los archivos: 644**
**Todas las carpetas: 755**

---

## 💾 SENTENCIAS SQL PARA PRODUCCIÓN

Ejecutar en phpMyAdmin → SQL Tab:

### PASO 1: Agregar Permisos de Marcas
```sql
-- ✅ EJECUTAR PRIMERO
INSERT INTO permissions (name, module, resource, action, description, created_at, updated_at) 
VALUES 
('marcas.ver', 'Parámetros', 'marcas', 'ver', 'Ver marcas', NOW(), NOW()),
('marcas.crear', 'Parámetros', 'marcas', 'crear', 'Crear marca', NOW(), NOW()),
('marcas.editar', 'Parámetros', 'marcas', 'editar', 'Editar marca', NOW(), NOW()),
('marcas.eliminar', 'Parámetros', 'marcas', 'eliminar', 'Eliminar marca', NOW(), NOW());

-- Verificar
SELECT COUNT(*) as permisos_marcas FROM permissions WHERE resource = 'marcas';
```

### PASO 2: Asignar Permisos al Rol Admin
```sql
-- ✅ EJECUTAR SEGUNDO
INSERT INTO role_permissions (role_id, permission_id, created_at, updated_at) 
SELECT 
    (SELECT id FROM roles WHERE name = 'admin' LIMIT 1) as role_id,
    id as permission_id,
    NOW(),
    NOW()
FROM permissions 
WHERE resource = 'marcas';

-- Verificar
SELECT r.name as role, p.name as permission
FROM role_permissions rp
JOIN roles r ON rp.role_id = r.id
JOIN permissions p ON rp.permission_id = p.id
WHERE p.resource = 'marcas'
ORDER BY p.action;
```

---

## 📋 CHECKLIST FTP

### Antes de subir
- [ ] Conectado a FTP producción
- [ ] Backup de archivos antiguos realizado
- [ ] Horario bajo en producción

### Carpetas
- [ ] Crear: resources/views/parametros/marcas/ (755)

### Archivos NUEVOS (5)
- [ ] app/Models/Marca.php (644)
- [ ] app/Http/Controllers/Parametros/MarcaController.php (644)
- [ ] resources/views/parametros/marcas/index.blade.php (644)
- [ ] resources/views/parametros/marcas/create.blade.php (644)
- [ ] resources/views/parametros/marcas/show.blade.php (644)

### Archivos MODIFICADOS (6)
- [ ] app/Models/Equipo.php (644)
- [ ] app/Http/Controllers/Parametros/EquipoController.php (644)
- [ ] resources/views/parametros/equipos/create.blade.php (644)
- [ ] resources/views/parametros/equipos/index.blade.php (644)
- [ ] resources/views/parametros/equipos/show.blade.php (644)
- [ ] resources/views/layouts/app.blade.php (644)

### SQL en phpMyAdmin
- [ ] PASO 1: Permisos agregados
- [ ] PASO 2: Permisos asignados a admin

### Verificación Post-Ejecución
- [ ] Acceso a app OK
- [ ] Link "Marcas" visible en menú
- [ ] Acceso a /parametros/marcas funciona
- [ ] Crear, editar, eliminar marcas OK
- [ ] Logs sin errores

---

## ⏱️ TIEMPO ESTIMADO

```
FTP: 5-10 minutos
SQL: 2 minutos  
Testing: 5-10 minutos
TOTAL: 15-20 minutos
```

---

## 🔄 ORDEN DE EJECUCIÓN

### 1️⃣ BD (SQL) - PRIMERO
```
phpMyAdmin → SQL → Ejecutar PASO 1
phpMyAdmin → SQL → Ejecutar PASO 2
```

### 2️⃣ Código (FTP) - SEGUNDO
```
FTP → Subir 5 archivos NUEVOS
FTP → Reemplazar 6 archivos MODIFICADOS
```

### 3️⃣ Testing - TERCERO
```
Navegador → Recargar cache (Ctrl+Shift+R)
Navegador → Login nuevamente
Navegador → Ir a Parámetros
Navegador → Verificar link "Marcas"
```

---

## ✅ RESULTADO ESPERADO

✅ Base de Datos:
- Tabla marcas con 15+ registros (desde migración anterior)
- 4 permisos agregados
- Permisos asignados al rol admin

✅ Código:
- 5 archivos nuevos en lugar correcto
- 6 archivos modificados reemplazados
- Link "Marcas" visible en menú

✅ Funcionalidad:
- Click "Marcas" → /parametros/marcas
- Ver listado de marcas
- Crear nueva marca
- Editar marca
- Eliminar marca (si sin equipos)
- Equipos muestran marca.nombre correctamente
- Formulario equipos muestra select de marcas

---

## 🚨 SI ALGO FALLA

### Error: "Access denied" para marcas
```
→ Verificar que SQL PASO 2 se ejecutó
→ Reintentar: phpMyAdmin → SQL PASO 2
```

### Error: "Link no aparece en menú"
```
→ Recargar navegador (Ctrl+Shift+R)
→ Logout y Login nuevamente
→ Limpiar cache Laravel (si tienes acceso SSH)
```

### Error: "500 al acceder a /parametros/marcas"
```
→ Verificar logs: storage/logs/laravel.log
→ Verificar que archivos NUEVOS están completos
→ Verificar permisos (644 archivos, 755 carpetas)
```

---

## 📞 ARCHIVOS DE REFERENCIA

**En local (c:\xampp\htdocs\CEOGestion\):**
- ARCHIVOS_FTP_PRODUCCION_2026_05_27.md - Detalles de archivos
- GUIA_RAPIDA_FTP_2026_05_27.md - Tabla rápida
- VERIFICACION_POST_FTP_2026_05_27.md - Tests detallados
- SQL_AGREGAR_PERMISOS_MARCAS.sql - Sentencias originales

---

## 🟢 LISTO PARA ENVIAR A PRODUCCIÓN

✅ BD Local: Actualizada
✅ Código Local: Preparado
✅ Archivos FTP: Listados
✅ SQL: Probado
✅ Documentación: Completa

**Estado: LISTO** 🚀
