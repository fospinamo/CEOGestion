# 📦 ARCHIVOS PARA PRODUCCIÓN - Equipos & Marcas
## Fecha: 2026-05-27 | Total: 4 archivos

---

## ✅ ARCHIVOS A COPIAR A PRODUCCIÓN

### 🆕 ARCHIVO NUEVO (1)

| # | Archivo | Tamaño | Destino Producción |
|---|---------|--------|-------------------|
| 1 | `resources/views/parametros/equipos/form.blade.php` | 13 KB | `/public_html/resources/views/parametros/equipos/form.blade.php` |

---

### 🔄 ARCHIVOS MODIFICADOS (3)

| # | Archivo Local | Tamaño | Destino Producción | Cambios |
|---|---|--------|------------------|---------|
| 2 | `resources/views/parametros/equipos/create.blade.php` | 0.6 KB | `/public_html/resources/views/parametros/equipos/create.blade.php` | ✅ Ahora es wrapper que incluye form.blade.php |
| 3 | `resources/views/parametros/equipos/edit.blade.php` | 0.6 KB | `/public_html/resources/views/parametros/equipos/edit.blade.php` | ✅ Ahora es wrapper que incluye form.blade.php |
| 4 | `resources/views/layouts/app.blade.php` | 28 KB | `/public_html/resources/views/layouts/app.blade.php` | ✅ jQuery movido al <head> |

---

## 📋 RESUMEN RÁPIDO

```
TOTAL: 4 archivos
TAMAÑO: ~42 KB
TIEMPO ESTIMADO: 5-10 minutos
COMPLEJIDAD: BAJA
BASE DE DATOS: NO REQUIERE CAMBIOS ✅
```

---

## 🚀 INSTRUCCIONES POR FTP

### Paso 1: Conectar a FTP
```
Host: [tu-hosting-ftp]
Usuario: [tu-usuario]
Contraseña: [tu-contraseña]
Modo: Pasivo
```

### Paso 2: Crear directorio (si no existe)
```
Navegar a: /public_html/resources/views/parametros/equipos/
Crear si falta: carpeta "equipos"
```

### Paso 3: Subir Archivo Nuevo
```
LOCAL: C:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\form.blade.php
REMOTO: /public_html/resources/views/parametros/equipos/form.blade.php
PERMISOS: 644
```

### Paso 4: Actualizar Archivos Existentes (Sobrescribir)
```
LOCAL: C:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\create.blade.php
REMOTO: /public_html/resources/views/parametros/equipos/create.blade.php
PERMISOS: 644
---
LOCAL: C:\xampp\htdocs\CEOGestion\resources\views\parametros\equipos\edit.blade.php
REMOTO: /public_html/resources/views/parametros/equipos/edit.blade.php
PERMISOS: 644
---
LOCAL: C:\xampp\htdocs\CEOGestion\resources\views\layouts\app.blade.php
REMOTO: /public_html/resources/views/layouts/app.blade.php
PERMISOS: 644
```

---

## ✨ DESPUÉS DE SUBIR ARCHIVOS

### 1. Limpiar Cache (Si tienes acceso a Terminal/SSH)
```bash
cd /public_html
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

### 2. Si NO tienes acceso a terminal
- Esperar 5-10 minutos para limpieza automática
- OU borrar archivo `bootstrap/cache/config.php` por FTP

### 3. Probar en Navegador
```
✓ https://tu-dominio.com/parametros/equipos/create
✓ https://tu-dominio.com/parametros/equipos/[ID]/edit
✓ https://tu-dominio.com/parametros/equipos
```

---

## 🔍 VERIFICACIÓN PRE-SUBIDA

Antes de subir, verifica que tienes estos 4 archivos listos:

```powershell
# Ejecutar en PowerShell desde: C:\xampp\htdocs\CEOGestion

# Verificar archivos existen
Test-Path "resources/views/parametros/equipos/form.blade.php"       # Debe retornar TRUE
Test-Path "resources/views/parametros/equipos/create.blade.php"     # Debe retornar TRUE
Test-Path "resources/views/parametros/equipos/edit.blade.php"       # Debe retornar TRUE
Test-Path "resources/views/layouts/app.blade.php"                    # Debe retornar TRUE

# Verificar tamaños
(Get-Item "resources/views/parametros/equipos/form.blade.php").Length / 1KB      # ~13 KB
(Get-Item "resources/views/parametros/equipos/create.blade.php").Length / 1KB    # ~0.6 KB
(Get-Item "resources/views/parametros/equipos/edit.blade.php").Length / 1KB      # ~0.6 KB
(Get-Item "resources/views/layouts/app.blade.php").Length / 1KB                   # ~28 KB
```

---

## ⚠️ CHECKLIST PRE-DESPLIEGUE

- [ ] Verificar que los 4 archivos existen localmente
- [ ] Conectar a FTP
- [ ] Crear carpeta `/resources/views/parametros/equipos/` si no existe
- [ ] Subir `form.blade.php` (archivo nuevo)
- [ ] Sobrescribir `create.blade.php`
- [ ] Sobrescribir `edit.blade.php`
- [ ] Sobrescribir `app.blade.php`
- [ ] Establecer permisos 644 a todos los archivos
- [ ] Desconectar FTP
- [ ] Limpiar cache (terminal o esperar 5 min)
- [ ] Probar en navegador
- [ ] ✅ Verificar sin errores

---

## 🎯 RESULTADO ESPERADO

✅ Formulario de crear equipo - SIN duplicación
✅ Formulario de editar equipo - SIN duplicación  
✅ Dropdown de Marca - Funciona correctamente
✅ Sin errores de jQuery en console (F12)
✅ Tabla de equipos - Muestra marca.nombre correctamente

---

## 📞 SOPORTE

Si hay problemas después de desplegar:

### Error: "Class not found"
- Solución: Limpiar cache `php artisan cache:clear`

### Error: "$ is not defined"
- Solución: Verificar que app.blade.php tenga jQuery en <head>

### Formulario con panel duplicado
- Solución: Verificar que form.blade.php fue subido correctamente

### Dropdown de Marca vacío
- Solución: Verificar que tabla `marcas` existe en BD

---

**Estado:** ✅ LISTO PARA PRODUCCIÓN
**Verificado en:** Desarrollo Local (localhost:8000)
**Fecha:** 2026-05-27
