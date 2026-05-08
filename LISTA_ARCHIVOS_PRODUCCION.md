# 📦 LISTA DE ARCHIVOS PARA DESPLIEGUE A PRODUCCIÓN

**Fecha:** 6 de Mayo 2026  
**Total:** 12 archivos  
**Tiempo estimado:** 30 minutos

---

## 📋 RESUMEN RÁPIDO

### ✅ 8 Archivos NUEVOS (CREAR/SUBIR)
```
1. app/Models/Theme.php
2. app/Models/EmpresaThemeSetting.php
3. database/migrations/2026_05_06_000002_create_themes_table.php
4. database/migrations/2026_05_06_000003_create_empresa_theme_settings_table.php
5. database/seeders/ThemeSeeder.php
6. resources/css/login-modern.css
7. deploy.sh (script de despliegue bash)
8. public/deploy-web.php (script de despliegue web)
```

### ✏️ 4 Archivos MODIFICADOS (REEMPLAZAR)
```
1. app/Http/Controllers/AuthController.php
2. app/Models/Empresa.php
3. database/seeders/DatabaseSeeder.php
4. resources/views/auth/login.blade.php
```

---

## 🚀 OPCIÓN 1: DESPLIEGUE CON SCRIPT (RECOMENDADO)

### Si tienes acceso a SSH Terminal:

```bash
cd /public_html
bash deploy.sh
```

El script ejecutará automáticamente:
- Limpiar caches
- Ejecutar migraciones
- Sembrear temas
- Crear symlink de storage
- Optimizar la aplicación

### Si NO tienes SSH pero tienes cPanel Terminal:

1. En cPanel, ir a **Advanced** > **Terminal**
2. Ejecutar: `bash ~/public_html/deploy.sh`

---

## 🚀 OPCIÓN 2: DESPLIEGUE POR WEB

### Si no tienes acceso a terminal:

1. Asegúrate de haber subido `public/deploy-web.php`
2. Acceder a: `https://tudominio.com/public/deploy-web.php?token=deploy2026ceogestion`
3. Esperar a que se complete
4. **MUY IMPORTANTE:** Eliminar el archivo `deploy-web.php` después de usarlo

---

## 🖥️ OPCIÓN 3: DESPLIEGUE MANUAL

Si prefieres hacerlo paso a paso:

```bash
cd /public_html

# 1. Limpiar caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# 2. Ejecutar migraciones
php artisan migrate

# 3. Sembrear temas
php artisan db:seed --class=ThemeSeeder

# 4. Crear symlink
php artisan storage:link

# 5. Optimizar (opcional)
php artisan optimize
```

---

## ✅ VERIFICACIÓN POSTERIOR

### Acceder a login:
```
https://tudominio.com/login
```

**Verificar:**
- ✓ Logo de empresa visible
- ✓ Página carga sin errores
- ✓ Sin errores 403 en consola

### Probar login:
```
Email: admin@ceogestion.com
Contraseña: password123
```

### En phpMyAdmin verificar:
```sql
-- Tabla temas debe existir con 5 registros
SELECT COUNT(*) FROM themes;
-- Resultado: 5

-- Tabla empresa_theme_settings debe tener registros
SELECT COUNT(*) FROM empresa_theme_settings;
-- Resultado: >= 1
```

---

## 🔥 PASO A PASO CON File Manager (Si no tienes terminal)

### 1. Conectar a cPanel File Manager
- URL: `https://[tudominio.com]:2083/`
- Ingresar credenciales

### 2. Navegar a public_html

### 3. Crear carpetas si no existen:
- `app/Models/` 
- `database/migrations/`
- `database/seeders/`
- `resources/css/`

### 4. Subir archivos nuevos (8 archivos)
```
Origen (local) → Destino (cPanel)

app/Models/Theme.php → public_html/app/Models/Theme.php
app/Models/EmpresaThemeSetting.php → public_html/app/Models/EmpresaThemeSetting.php

database/migrations/2026_05_06_000002_create_themes_table.php → ...
database/migrations/2026_05_06_000003_create_empresa_theme_settings_table.php → ...

database/seeders/ThemeSeeder.php → public_html/database/seeders/ThemeSeeder.php

resources/css/login-modern.css → public_html/resources/css/login-modern.css

deploy.sh → public_html/deploy.sh
public/deploy-web.php → public_html/public/deploy-web.php
```

### 5. Reemplazar archivos modificados (4 archivos)
```
Buscar en cPanel:
- app/Http/Controllers/AuthController.php
- app/Models/Empresa.php
- database/seeders/DatabaseSeeder.php
- resources/views/auth/login.blade.php

Descargar locales desde c:\xampp\htdocs\CEOGestion\
Subir nuevas versiones a cPanel (Replace)
```

### 6. Ejecutar despliegue
- Acceder a: `https://tudominio.com/public/deploy-web.php?token=deploy2026ceogestion`
- Esperar a completarse
- Eliminar archivo `deploy-web.php`

---

## 🆘 SOLUCIÓN RÁPIDA DE PROBLEMAS

| Problema | Solución |
|----------|----------|
| "Class Theme not found" | Ejecutar: `php artisan dump-autoload` |
| "Tabla themes no existe" | Ejecutar: `php artisan migrate` |
| Logo muestra 403 | Ejecutar: `php artisan storage:link` |
| CSS no se ve | Ejecutar: `php artisan view:clear` |
| Error al seedear | Ejecutar: `php artisan db:seed --class=ThemeSeeder` |

---

## 📝 CHECKLIST FINAL

- [ ] 8 archivos nuevos subidos
- [ ] 4 archivos modificados reemplazados
- [ ] `deploy.sh` o `deploy-web.php` ejecutado
- [ ] Login page carga sin errores
- [ ] Logo empresa visible en login
- [ ] Base de datos tiene 5 temas
- [ ] Test login exitoso
- [ ] No hay errores 403 en storage

---

**Estado:** ✅ Listo para producción  
**Probado:** ✅ Localmente verificado  
**Soporte:** Ver GUIA_PRODUCCION_2026_05_06.md para detalles completos
