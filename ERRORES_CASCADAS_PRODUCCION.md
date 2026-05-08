# 📋 REGISTRO DE ERRORES - CASCADAS SEDES EN PRODUCCIÓN
**Fecha:** 7 de mayo de 2026
**Backup:** CEOGestion_backup_cascadas_error_2026-05-07_22-39-43

---

## ❌ ERRORES IDENTIFICADOS

### 1. **Acceso a rutas anidadas en producción**
**Estado:** 🔴 CRÍTICO
- **En Chrome:** Funciona solo agregando `/public/` manualmente
  - ✅ `https://gestion.simotec.com.co/CEOGestion/public/parametros/sedes` → Funciona
  - ❌ `https://gestion.simotec.com.co/CEOGestion/parametros/sedes` → 404 (Edge)
  
**Causa:** `.htaccess` no está siendo interpretado. `mod_rewrite` probablemente deshabilitado en cPanel.

**Evidencia:**
- Browser Edge: Error 404 Not Found
- Browser Chrome: Dashboard funciona, pero cascadas fallan
- `index.php` en raíz NO se ejecuta automáticamente

---

### 2. **Cascadas (AJAX) no cargan municipios**
**Estado:** 🔴 CRÍTICO
- **Síntoma:** Al seleccionar departamento, el select de municipios permanece vacío
- **URLs intentadas:**
  - `../../../api/municipios-por-departamento?departamento_id=2` → 404
  - `/api/municipios-por-departamento?departamento_id=2` → 404
  
**Causa:** Las URLs relativas (`../../../api/...`) se resuelven a rutas incorrectas en producción

**Console (F12) muestra:**
- Network → `municipios-por-departamento` → Status **404** o **Not Found**
- Response vacía o error HTML

---

### 3. **Problemas con rutas base en servidor compartido**
**Estado:** 🔴 CRÍTICO
- **Estructura:** `/public_html/gestion/CEOGestion/`
- **URL:** `https://gestion.simotec.com.co/CEOGestion/`
- **Profundidad de carpetas:** 3 niveles (`resources/views/parametros/sedes/`)
- **Rutas relativas:** `../../../api/...` pueden no resolver correctamente

---

## 🔧 INTENTOS REALIZADOS

| # | Solución | Resultado | Estado |
|---|----------|-----------|--------|
| 1 | `.htaccess` con `mod_rewrite` | No funciona | ❌ mod_rewrite deshabilitado |
| 2 | `index.php` en raíz con lógica PHP | No se ejecuta | ❌ DirectoryIndex no funciona |
| 3 | URLs con `{{ url('/') }}` Blade | Genera URLs incorrectas | ❌ No va a `/public/` |
| 4 | URLs relativas `../../../api/...` | Resuelve a 404 | ❌ Ruta incorrecta |

---

## 🎯 SOLUCIONES A PROBAR

### **Opción A: Usar window.location.origin (Recomendado)**
```javascript
// En lugar de ../../../api/...
const baseApi = window.location.origin + '/gestion/CEOGestion/public/api';
const apiUrl = `${baseApi}/municipios-por-departamento?departamento_id=${id}`;
```

**Ventaja:** No depende de capas de carpetas
**Desventaja:** Duro con `/public/` en la URL

### **Opción B: Obtener base desde data attribute**
```html
<div data-api-base="{{ url('api') }}">
    <!-- Contenido -->
</div>
```
```javascript
const apiBase = document.body.dataset.apiBase;
const apiUrl = `${apiBase}/municipios-por-departamento?departamento_id=${id}`;
```

### **Opción C: Contactar hosting para habilitar mod_rewrite**
- Solicitar a cPanel que habilite `mod_rewrite`
- Solicitar que `index.php` sea `DirectoryIndex` por defecto

---

## 📊 ESTADO ACTUAL DEL SISTEMA

| Componente | Estado | Notas |
|-----------|--------|-------|
| Dashboard | ✅ Funciona | Se carga correctamente |
| Rutas anidadas (sin /public/) | ❌ 404 | Requiere /public/ para funcionar |
| Cascadas (API) | ❌ Error 404 | AJAX devuelve 404 |
| Assets (CSS/JS) | ✅ Funciona | Se cargan correctamente |
| Autenticación | ✅ Funciona | Login OK |
| Base de datos | ✅ Funciona | Conexión OK |

---

## 📝 PRÓXIMOS PASOS

1. **Revisar la URL base:** Crear un script que valide la URL correcta
2. **Probar Opción A o B:** Implementar solución alternativa sin /public/
3. **Contactar hosting:** Si persiste, pedir habilitar mod_rewrite
4. **Verificar configuración Laravel:**
   - `APP_URL` en `.env`
   - `config/app.php` 

---

## 🔗 ARCHIVOS RELACIONADOS

- `.htaccess` - Intentó reescritura (no funciona)
- `index.php` - PHP-based routing (no se ejecuta)
- `resources/views/parametros/sedes/create.blade.php` - Cascadas con URLs relativas
- `resources/views/parametros/sedes/edit.blade.php` - Cascadas con URLs relativas

---

**Última actualización:** 2026-05-07 22:39:43
**Status:** ⏳ Pendiente de solución
