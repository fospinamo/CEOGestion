# SOLUCIÓN COMPLETA: LARAVEL 11 EN SERVIDOR COMPARTIDO

## PROBLEMA ACTUAL
- ✅ Dashboard funciona: `https://gestion.simotec.com.co/CEOGestion/`
- ❌ Rutas anidadas fallan: `https://gestion.simotec.com.co/CEOGestion/parametros/sedes` → 404
- ❌ AJAX a `/api/...` devuelve 404

## SOLUCIÓN: 4 PASOS

### PASO 1: Verificar `.env` en Producción
El `.env` en `/public_html/gestion/CEOGestion/.env` debe tener:
```
APP_URL=https://gestion.simotec.com.co/CEOGestion
APP_DEBUG=false
APP_ENV=production
```

**IMPORTANTE:** Si cambiaste algo en `.env`, ejecuta en local primero:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

### PASO 2: Subir Archivos Actualizados
Subir a `/public_html/gestion/CEOGestion/`:

| Archivo | Local Path | Servidor Path | Permiso |
|---------|-----------|--------------|---------|
| **.htaccess (raíz)** | `c:\xampp\htdocs\CEOGestion\.htaccess` | `/public_html/gestion/CEOGestion/.htaccess` | 644 |
| **index.php (raíz)** | `c:\xampp\htdocs\CEOGestion\index.php` | `/public_html/gestion/CEOGestion/index.php` | 644 |
| **public/.htaccess** | `c:\xampp\htdocs\CEOGestion\public\.htaccess` | `/public_html/gestion/CEOGestion/public/.htaccess` | 644 |

---

### PASO 3: Actualizar JavaScript de Cascadas

**En `resources/views/parametros/sedes/create.blade.php`:**

Cambiar:
```javascript
const apiUrl = `/api/municipios-por-departamento?departamento_id=${departamento_id}`;
```

Por:
```javascript
// Obtener la URL base de Laravel
const baseUrl = '{{ url('/') }}';
const apiUrl = `${baseUrl}/api/municipios-por-departamento?departamento_id=${departamento_id}`;
```

**Mismo cambio en `edit.blade.php`:**
```javascript
const baseUrl = '{{ url('/') }}';
const apiUrl = `${baseUrl}/api/barrios-por-municipio?municipio_id=${municipio_id}`;
```

---

### PASO 4: Verificar `public/.htaccess`

El archivo `public/.htaccess` debe tener el contenido estándar de Laravel 11:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## EXPLICACIÓN DE CÓMO FUNCIONA

1. **Request llega a:** `https://gestion.simotec.com.co/CEOGestion/parametros/sedes`
2. **.htaccess (raíz) reescribe a:** `public/parametros/sedes`
3. **public/.htaccess procesa:** Como no es archivo real, redirige a `public/index.php`
4. **Laravel recibe:** Request a `/parametros/sedes` via `public/index.php`
5. **Route matching:** `routes/web.php` → `parametros.sedes.index` ✅

---

## PRUEBAS DESPUÉS DE SUBIR

1. **Test 1: Dashboard**
   ```
   https://gestion.simotec.com.co/CEOGestion/
   ```
   Debe mostrar el dashboard (sin 404) ✅

2. **Test 2: Rutas Anidadas**
   ```
   https://gestion.simotec.com.co/CEOGestion/parametros/sedes
   ```
   Debe mostrar la lista de sedes (sin 404) ✅

3. **Test 3: AJAX (F12 → Console → Network)**
   - Ir a crear sede
   - Seleccionar un departamento
   - F12 → Network → buscar `municipios-por-departamento`
   - Status debe ser **200** (no 404) ✅
   - Response debe tener JSON con municipios ✅

4. **Test 4: Assets**
   - Inspeccionador → F12 → verificar que CSS/JS se cargan (Network → sin 404 rojo) ✅

---

## SI AÚN HAY PROBLEMAS

### Problema: 404 en rutas anidadas
- Verificar que `.htaccess` tiene permiso **644**
- Verificar que `RewriteEngine On` está habilitado (hosting debe tener `mod_rewrite`)
- Si no funciona, contactar hosting para verificar que `mod_rewrite` esté habilitado

### Problema: AJAX devuelve 404
- Verificar que `{{ url('/') }}` genera: `https://gestion.simotec.com.co/CEOGestion`
- Agregar console.log para verificar: `console.log('Base URL:', baseUrl);`
- URL final debe ser: `https://gestion.simotec.com.co/CEOGestion/api/municipios-por-departamento`

### Problema: Assets (CSS/JS) no cargan
- Ejecutar en local: `php artisan storage:link`
- Ejecutar: `php artisan optimize:clear`
- Subir carpeta `public/` completa

---

## CHECKLIST FINAL

- [ ] `.env` tiene `APP_URL=https://gestion.simotec.com.co/CEOGestion`
- [ ] `.htaccess` (raíz) subido con permiso 644
- [ ] `index.php` (raíz) subido con permiso 644
- [ ] `public/.htaccess` existe y tiene contenido estándar
- [ ] `public/index.php` existe
- [ ] JavaScript actualizado con `{{ url('/') }}`
- [ ] Dashboard carga sin 404
- [ ] Rutas anidadas cargan sin 404
- [ ] AJAX devuelve status 200
- [ ] Assets (CSS/JS) cargan correctamente

