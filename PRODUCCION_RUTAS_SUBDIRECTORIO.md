# ⚠️ PROBLEMA CRÍTICO: Rutas en Producción con Subdirectorio

## 🔴 LA SITUACIÓN

### Local vs Producción

```
LOCAL
- Ruta: C:\xampp\htdocs\CEOGestion
- URL: http://localhost:8000/
- APP_URL: http://localhost:8000

PRODUCCIÓN
- Ruta: /home/simotec/public_html/gestion/CEOGestion
- URL: https://gestion.simotec.com.co/CEOGestion/public/login
- APP_URL: ??? (PROBLEMA)
```

**El problema:** Laravel genera URLs basadas en `APP_URL`. Si está mal configurado:
- ❌ `route()` genera URLs incorrectas
- ❌ Los assets (CSS, JS) no cargan
- ❌ Las redirecciones fallan
- ❌ Los formularios POST van a URLs inválidas

---

## 🔧 SOLUCIÓN

### 1. En Producción - Configurar .env

Edita `/home/simotec/public_html/gestion/CEOGestion/.env`:

```bash
# ANTES (incorrecto)
APP_URL=https://gestion.simotec.com.co

# DESPUÉS (correcto)
APP_URL=https://gestion.simotec.com.co/CEOGestion
```

**IMPORTANTE:** El APP_URL debe terminar en `/CEOGestion` pero SIN `/public`

---

### 2. En .htaccess - Verificar Configuración

Asegúrate que `public/.htaccess` existe y contiene:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect to front controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

### 3. En routes/web.php - EVITAR route() con parámetros

**❌ EVITAR (causa problemas en producción):**
```blade
{{ route('parametros.contratos.index') }}
{{ route('login') }}
```

**✅ USAR (siempre seguro):**
```blade
{{ url('parametros/contratos') }}
{{ url('login') }}
```

---

## 🛠️ VERIFICACIÓN EN PRODUCCIÓN

Ejecuta estos comandos en SSH:

```bash
cd /home/simotec/public_html/gestion/CEOGestion

# 1. Verificar APP_URL
grep "^APP_URL" .env

# 2. Limpiar cache de rutas
php artisan route:clear

# 3. Generar cache de rutas (valida que todas funcionen)
php artisan route:cache

# 4. Verificar URLs generadas
php artisan tinker
>>> url('login')
>>> url('parametros/contratos')
```

**Debería mostrar:**
```
"https://gestion.simotec.com.co/CEOGestion/login"
"https://gestion.simotec.com.co/CEOGestion/parametros/contratos"
```

---

## 📋 CHECKLIST DE RUTAS

- [ ] APP_URL en .env = `https://gestion.simotec.com.co/CEOGestion`
- [ ] public/.htaccess existe y es correcto
- [ ] Cambiar `route()` a `url()` en formularios (ya hecho)
- [ ] Verificar enlaces en vistas (buscar `route(`)
- [ ] Limpiar cache: `php artisan route:clear`
- [ ] Probar login: https://gestion.simotec.com.co/CEOGestion/public/login
- [ ] Probar dashboard: https://gestion.simotec.com.co/CEOGestion/public/dashboard

---

## 🔍 BUSCAR PROBLEMAS REMANENTES

Buscar todas las instancias de `route(` en vistas:

```bash
grep -r "route(" resources/views --include="*.blade.php" | head -20
```

Algunos pueden estar bien, pero revisar especialmente:
- Formularios (action)
- Enlaces a índices (index)
- Redirecciones

---

## 📝 NOTA IMPORTANTE

**¿Por qué route() falla en producción pero url() funciona?**

```php
// route('parametros.contratos.index')
// Laravel intenta generar basado en app('url')
// Si APP_URL está mal, genera URL incorrecta

// url('parametros/contratos')
// Simplemente concatena APP_URL + ruta
// Mucho más robusto con subdirectorios
```

---

## ✅ ESTADO

- ⏳ Espera confirmación de APP_URL en producción
- ⏳ Necesita ejecutar route:clear en servidor
- ✅ Ya se usó url() en dashboard
- ✅ Ya se documentó protocolo

