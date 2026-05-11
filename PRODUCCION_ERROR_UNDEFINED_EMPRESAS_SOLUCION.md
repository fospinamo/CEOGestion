# ⚠️ ERROR EN PRODUCCIÓN: Undefined variable $empresas

## 🔴 EL PROBLEMA

```
Undefined variable $empresas (View: /home/simotec/public_html/gestion/CEOGestion/resources/views/home.blade.php)
```

La vista compilada en **producción está desincronizada** con el código actual.

---

## ✅ LA SOLUCIÓN

### **Opción 1: VÍA SSH (RECOMENDADO)**

Si tienes acceso SSH en tu servidor Colombia Hosting:

```bash
# Conecta al servidor
ssh tu_usuario@tu_dominio.com

# Ve al proyecto
cd /home/simotec/public_html/gestion/CEOGestion

# Ejecuta estos comandos en orden:
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Elimina vistas compiladas
rm -rf storage/framework/views/*
```

Luego **recarga la página** en el navegador.

---

### **Opción 2: VÍA cPanel File Manager (SIN SSH)**

Si **no tienes SSH**, usa el File Manager de cPanel:

#### Paso 1: Entra a cPanel
- Abre `cPanel` → **File Manager**
- Navega a `/public_html/gestion/CEOGestion/`

#### Paso 2: Elimina carpetas de cache

Busca y **ELIMINA** estas carpetas:
```
storage/framework/views/
storage/framework/cache/
storage/logs/
```

Luego crea carpetas vacías con los mismos nombres:
1. Click derecho → **Create Folder**
2. Nombre: `views`
3. Ubica: `storage/framework/`

#### Paso 3: Usa Terminal de cPanel (si está disponible)

En cPanel, busca **"Terminal"** o **"SSH"**:

```bash
cd /home/simotec/public_html/gestion/CEOGestion
php artisan cache:clear
php artisan view:clear
```

---

### **Opción 3: VÍA CURL Script (MÁS FÁCIL)**

Si tienes una ruta de migración/limpieza, crea un archivo:

**En `/public/clear-cache.php`:**

```php
<?php
// ADVERTENCIA: Borrar este archivo después de usarlo
system('php artisan cache:clear');
system('php artisan view:clear');
system('php artisan config:clear');
system('php artisan route:clear');
echo "✅ Cache limpiado";
?>
```

Luego abre en el navegador:
```
https://tu-dominio.com/clear-cache.php
```

**⚠️ IMPORTANTE: Borra el archivo `clear-cache.php` después de usarlo**

---

## 🔍 VERIFICACIÓN

Después de limpiar el cache, abre:
```
https://tu-dominio.com/dashboard
```

Deberías ver el dashboard con:
- ✅ Selector de empresa (dropdown)
- ✅ Estadísticas mostrando correctamente
- ✅ Sin errores

---

## 📝 NOTA TÉCNICA

El problema ocurrió porque:

1. **Cambio en local**: Se agregó la variable `$empresas` al HomeController
2. **Nuevo commit**: Se empujó a GitHub
3. **Pull en producción**: Se bajó el código nuevo
4. **Vistas compiladas antiguas**: Pero el cache tenía vistas compiladas del código anterior
5. **Resultado**: Laravel intentó usar `$empresas` pero no estaba en la vista compilada

**La solución**: Limpiar el cache fuerza a Laravel a recompilar las vistas con el código nuevo.

---

## 🚀 PARA EVITAR ESTO EN EL FUTURO

Cada vez que hagas `git pull` en producción, siempre ejecuta:

```bash
git pull origin master
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

O usa el script `COMANDO_LIMPIAR_PRODUCCION.sh` que está en el repositorio.

---

## 🆘 SI AÚN NO FUNCIONA

Contacta al soporte de Colombia Hosting:
- Especifica el error exacto
- Menciona que es un error de vistas compiladas
- Pide que ejecuten: `php artisan cache:clear && php artisan view:clear`

---

**Status**: 🟢 Resolvible en ~2 minutos

