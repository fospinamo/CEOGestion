# 🚀 GUÍA DE DESPLIEGUE A PRODUCCIÓN - CEOGestion
**Fecha:** 6 de Mayo 2026  
**Ambiente Local:** ✅ Funcionando  
**Versión:** v1.0 Sistema de Temas + Logo Empresa

---

## 📋 RESUMEN DE CAMBIOS

### Nuevos Archivos (8 archivos)
```
✓ app/Models/Theme.php
✓ app/Models/EmpresaThemeSetting.php
✓ database/migrations/2026_05_06_000002_create_themes_table.php
✓ database/migrations/2026_05_06_000003_create_empresa_theme_settings_table.php
✓ database/seeders/ThemeSeeder.php
✓ resources/css/login-modern.css
✓ database/migrations/2026_05_06_000001_add_logo_to_empresas_table.php (ya existía)
```

### Archivos Modificados (4 archivos)
```
✓ app/Http/Controllers/AuthController.php - showLogin() method actualizado
✓ app/Models/Empresa.php - Relación themeSetting() agregada
✓ database/seeders/DatabaseSeeder.php - ThemeSeeder integrado
✓ resources/views/auth/login.blade.php - Integración con temas
```

---

## 🔧 PASOS DE DESPLIEGUE

### PASO 1: ACCEDER A cPanel (5 minutos)

1. Acceder a: `https://[tudominio.com]:2083/` o `https://[IP]:2083/`
2. Ingresar credenciales cPanel
3. Seleccionar **File Manager** en el panel

### PASO 2: SUBIR ARCHIVOS NUEVOS (15 minutos)

#### 2.1 Crear estructura de carpetas (si no existen)
En cPanel File Manager, navegar a `public_html/app/Models/` y crear:
- `Theme.php` ← subir archivo
- `EmpresaThemeSetting.php` ← subir archivo

#### 2.2 Subir modelo Theme.php
```
Origen: c:\xampp\htdocs\CEOGestion\app\Models\Theme.php
Destino: /public_html/app/Models/Theme.php
```

#### 2.3 Subir modelo EmpresaThemeSetting.php
```
Origen: c:\xampp\htdocs\CEOGestion\app\Models\EmpresaThemeSetting.php
Destino: /public_html/app/Models/EmpresaThemeSetting.php
```

#### 2.4 Crear carpeta migraciones y subir 2 archivos
Navegar a `public_html/database/migrations/` y crear:
- `2026_05_06_000002_create_themes_table.php` ← subir
- `2026_05_06_000003_create_empresa_theme_settings_table.php` ← subir

#### 2.5 Subir seeder
```
Destino: /public_html/database/seeders/ThemeSeeder.php
```

#### 2.6 Subir CSS
```
Destino: /public_html/resources/css/login-modern.css
```

---

### PASO 3: ACTUALIZAR ARCHIVOS MODIFICADOS (10 minutos)

#### 3.1 Reemplazar AuthController.php
```
Destino: /public_html/app/Http/Controllers/AuthController.php

CAMBIO: El método showLogin() ahora incluye:
    public function showLogin()
    {
        $empresa = Empresa::where('estado', true)->first() ?? Empresa::first();
        $theme = $empresa?->themeSetting()->first();
        
        return view('auth.login', [
            'empresa' => $empresa,
            'theme' => $theme,
        ]);
    }
```

#### 3.2 Actualizar Empresa.php
```
Destino: /public_html/app/Models/Empresa.php

AGREGAR relación al final de la clase:
    public function themeSetting()
    {
        return $this->hasOne(EmpresaThemeSetting::class);
    }
```

#### 3.3 Actualizar DatabaseSeeder.php
```
Destino: /public_html/database/seeders/DatabaseSeeder.php

CAMBIO: Insertar ThemeSeeder después de RoleAndPermissionSeeder
    $this->call(RoleAndPermissionSeeder::class);
    $this->call(ThemeSeeder::class); // ← NUEVA LÍNEA
    $this->call(CategoriaSeeder::class);
```

#### 3.4 Actualizar login.blade.php
```
Destino: /public_html/resources/views/auth/login.blade.php

CAMBIO: Asegurarse que incluya:
    - Script de tema dinámico al final
    - Logo de empresa con fallback
    - Estilos CSS del nuevo theme
```

---

### PASO 4: EJECUTAR COMANDOS EN TERMINAL (VÍA SSH O CPANEL TERMINAL)

**OPCIÓN A: Si tienes acceso a SSH (Terminal)**

```bash
cd /public_html
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan migrate
php artisan db:seed --class=ThemeSeeder
php artisan storage:link
```

**OPCIÓN B: Si NO tienes SSH (usar cPanel Terminal o ejecutar vía browser)**

1. En cPanel, buscar **Terminal** o **Advanced** > **Terminal**
2. Copiar y ejecutar cada comando arriba

**OPCIÓN C: Crear archivo de ejecución (último recurso)**

Si no puedes ejecutar comandos, crear archivo:
```
Archivo: /public_html/deploy.php
```

```php
<?php
// Script de despliegue automático
system('php artisan cache:clear');
system('php artisan view:clear');
system('php artisan config:clear');
system('php artisan migrate');
system('php artisan db:seed --class=ThemeSeeder');
system('php artisan storage:link');
echo 'Despliegue completado';
?>
```

Luego acceder a: `https://tudominio.com/deploy.php`

---

## ✅ VERIFICACIÓN POST-DESPLIEGUE

### 1. Verificar Acceso al Login
```
URL: https://tudominio.com/login
Esperado: Página login cargue sin errores, logo visible
```

### 2. Verificar Logo de Empresa
```
- Logo debe verse en el login
- Comprobar que no haya errores 403 en DevTools
- URL del logo debe ser: https://tudominio.com/storage/empresas/[nombre_archivo].png
```

### 3. Verificar Base de Datos
En phpMyAdmin:
```sql
-- Verificar tablas creadas
SHOW TABLES LIKE 'theme%';

-- Debe retornar:
-- themes
-- empresa_theme_settings

-- Ver temas creados
SELECT id, name, label, color_primary FROM themes LIMIT 5;

-- Debe retornar 5 temas:
-- 1 | corporate-blue | Corporativo Azul | #0066CC
-- 2 | elegant-black | Elegante Negro | #1A1A1A
-- 3 | modern-green | Moderno Verde | #10B981
-- 4 | tech-dark | Tech Oscuro | #0F172A
-- 5 | warm-orange | Cálido Naranja | #EA580C
```

### 4. Verificar Configuración de Empresa
```sql
SELECT id, empresa_id, theme_id FROM empresa_theme_settings;

-- Debe retornar 1 registro por cada empresa
```

### 5. Probar Login
```
Email: admin@ceogestion.com
Contraseña: password123
Esperado: Login exitoso, redirige al dashboard
```

---

## 🔒 CONFIGURACIÓN DE PERMISOS

Después de subir archivos, asegurar permisos correctos en cPanel:

```bash
# En terminal SSH
chmod 755 app/Models
chmod 755 database/migrations
chmod 755 database/seeders
chmod 755 resources/css
chmod 644 app/Models/Theme.php
chmod 644 app/Models/EmpresaThemeSetting.php
chmod 644 database/migrations/2026_05_06_000002_create_themes_table.php
chmod 644 database/migrations/2026_05_06_000003_create_empresa_theme_settings_table.php
chmod 644 database/seeders/ThemeSeeder.php
chmod 644 resources/css/login-modern.css
```

---

## 🚨 SOLUCIÓN DE PROBLEMAS

### Problema 1: "Class Theme not found"
**Solución:** Asegurar que `app/Models/Theme.php` se subió correctamente
```bash
php artisan dump-autoload
composer dump-autoload
```

### Problema 2: "Tabla themes no existe"
**Solución:** Ejecutar migraciones
```bash
php artisan migrate
```

### Problema 3: Seeders no ejecutados
**Solución:** Ejecutar manualmente
```bash
php artisan db:seed --class=ThemeSeeder
```

### Problema 4: Logo muestra error 403
**Solución:** Regenerar symlink de storage
```bash
php artisan storage:link
```

### Problema 5: CSS no aplica
**Solución:** Limpiar cache y compilar assets
```bash
php artisan view:clear
php artisan cache:clear
npm run build  # Si usas assets compilation
```

---

## 📝 LISTA DE VERIFICACIÓN FINAL

- [ ] Todos los archivos nuevos (8) subidos a cPanel
- [ ] Todos los archivos modificados (4) reemplazados
- [ ] Comandos artisan ejecutados (`migrate`, `seed`, `storage:link`)
- [ ] Permisos configurados correctamente (755, 644)
- [ ] Base de datos verifica tablas theme exist
- [ ] Base de datos verifica 5 temas seeded
- [ ] Login page carga sin errores
- [ ] Logo empresa visible en login
- [ ] Test login con admin@ceogestion.com
- [ ] Storage link funciona (no errores 403)
- [ ] Caches limpiados

---

## 🆘 CONTACTO DE SOPORTE

Si encuentras problemas:

1. **Verificar logs:** `/public_html/storage/logs/laravel.log`
2. **Usar phpMyAdmin:** Ejecutar queries SQL para verificar datos
3. **Revisar permisos:** Asegurar carpetas tengan permisos 755

---

## 📊 INFORMACIÓN TÉCNICA

| Item | Valor |
|------|-------|
| Framework | Laravel 11 |
| PHP Requerido | 8.2+ |
| Base de Datos | MySQL 5.7+ |
| Nuevas Tablas | 2 (themes, empresa_theme_settings) |
| Nuevas Migraciones | 3 |
| Nuevos Seeders | 1 |
| Archivos Nuevos | 8 |
| Archivos Modificados | 4 |
| Temas Predefinidos | 5 |
| Tiempo Total Despliegue | ~30 minutos |

---

**Completado:** ✅ Listo para producción  
**Fecha:** 6 de Mayo 2026  
**Estado:** Probado localmente ✓
