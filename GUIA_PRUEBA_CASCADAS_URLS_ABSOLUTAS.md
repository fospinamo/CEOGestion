# 🚀 GUÍA DE PRUEBA - CASCADAS CON URLs ABSOLUTAS
**Fecha:** 7 de mayo de 2026
**Commit:** 623d3fd

---

## ✅ CAMBIOS REALIZADOS

### Archivos Modificados
- `resources/views/parametros/sedes/create.blade.php`
- `resources/views/parametros/sedes/edit.blade.php`

### Qué cambió
```javascript
// ❌ ANTES (URLs relativas - FALLABAN)
const apiUrl = `../../../api/municipios-por-departamento?departamento_id=${id}`;

// ✅ AHORA (URLs absolutas - FUNCIONAN)
const API_BASE = getApiBase(); // Detecta automáticamente
const apiUrl = `${API_BASE}/municipios-por-departamento?departamento_id=${id}`;
```

### Nueva Función: `getApiBase()`
```javascript
function getApiBase() {
    const pathSegments = window.location.pathname.split('/').filter(Boolean);
    const publicIndex = pathSegments.indexOf('public');
    
    if (publicIndex !== -1) {
        const basePath = '/' + pathSegments.slice(0, publicIndex + 1).join('/');
        return window.location.origin + basePath + '/api';
    }
    
    return window.location.origin + '/api'; // Fallback
}
```

**Ventajas:**
- ✅ Detecta automáticamente la estructura del servidor
- ✅ No requiere cambios manuales entre localhost y producción
- ✅ Funciona en cualquier profundidad de carpetas
- ✅ Logs claros en Console para debugging

---

## 🧪 CÓMO PROBAR EN LOCALHOST

### 1. Cargar página de crear sede
```
http://localhost:8000/parametros/sedes/create
```

### 2. Abrir DevTools (F12)
- Ir a pestaña **Console**
- Ver mensaje: `✅ API Base calculado: http://localhost:8000/api`

### 3. Seleccionar un departamento
- Debe cargar municipios automáticamente
- Console debe mostrar:
  ```
  📍 Departamento seleccionado: 5
  🌐 URL del API (absoluta): http://localhost:8000/api/municipios-por-departamento?departamento_id=5
  ✅ Municipios recibidos: [...]
  ```

### 4. Seleccionar un municipio
- Debe cargar barrios automáticamente
- Console debe mostrar:
  ```
  🏙️ Municipio seleccionado: 25
  🌐 URL del API (absoluta): http://localhost:8000/api/barrios-por-municipio?municipio_id=25
  ✅ Barrios recibidos: [...]
  ```

---

## 🌍 CÓMO PROBAR EN PRODUCCIÓN

### 1. Subir cambios a producción
```bash
# En el servidor, dentro de CEOGestion/
git pull origin master
php artisan view:clear
php artisan cache:clear
```

### 2. Abrir página en producción
```
https://gestion.simotec.com.co/CEOGestion/parametros/sedes/create
```
⚠️ **Nota:** NO requiere `/public/` en la URL (si .htaccess funciona)

### 3. Abrir DevTools (F12)
- Ir a pestaña **Console**
- Debe mostrar:
  ```
  📂 Path segments: ['gestion', 'CEOGestion', 'public', 'parametros', 'sedes']
  ✅ API Base calculado: https://gestion.simotec.com.co/CEOGestion/public/api
  ```

### 4. Probar cascada - Departamento
- Seleccionar un departamento
- **Esperado:** Se cargan municipios
- **Console debe mostrar:**
  ```
  📍 Departamento seleccionado: 5
  🌐 URL del API (absoluta): https://gestion.simotec.com.co/CEOGestion/public/api/municipios-por-departamento?departamento_id=5
  📊 Response status: 200
  ✅ Municipios recibidos: [...]
  ```

### 5. Probar cascada - Municipio
- Seleccionar un municipio
- **Esperado:** Se cargan barrios
- **Console debe mostrar:** Response status 200

### 6. Probar página de editar
```
https://gestion.simotec.com.co/CEOGestion/parametros/sedes/1/edit
```
- Debe pre-cargar municipios y barrios
- Debe preservar valores seleccionados
- Cascadas deben funcionar igual

---

## 🐛 QUÉ ESPERAR

### ✅ Si funciona correctamente
- Cascadas cargan sin errores
- Console muestra `Response status: 200`
- Municipios y barrios llenan correctamente
- NO hay errores 404

### ❌ Si algo falla
**Error: `Response status: 404`**
- Probable causa: Ruta API no existe
- Verificar: `php artisan route:list | grep api`

**Error: `Cannot set properties of undefined`**
- Probable causa: Respuesta no es JSON
- Verificar: Response en Console → Network tab

**Error: `path segments no contiene 'public'`**
- Probable causa: URL diferente a esperada
- Verificar: `window.location.pathname` en Console

---

## 📊 FLUJO DE DEBUGGING EN CONSOLE

1. **Verificar ruta detectada:**
   ```javascript
   console.log(window.location.pathname.split('/').filter(Boolean))
   ```

2. **Probar URL manualmente:**
   ```javascript
   fetch('https://gestion.simotec.com.co/CEOGestion/public/api/municipios-por-departamento?departamento_id=1')
       .then(r => r.json())
       .then(d => console.log(d))
   ```

3. **Ver URL exacta que se llamó:**
   - Ir a DevTools → Network
   - Seleccionar departamento
   - Buscar request `municipios-por-departamento`
   - Verificar: URL, Headers, Response

---

## 🎯 CHECKLIST DE TESTING

### Localhost
- [ ] DevTools Console muestra `API Base calculado: http://localhost:8000/api`
- [ ] Cascada Departamento → Municipio funciona
- [ ] Cascada Municipio → Barrio funciona
- [ ] Página de editar precarga correctamente
- [ ] No hay errores en Console

### Producción
- [ ] DevTools Console muestra `API Base calculado: https://gestion.simotec.com.co/CEOGestion/public/api`
- [ ] Cascada Departamento → Municipio funciona
- [ ] Cascada Municipio → Barrio funciona
- [ ] Página de editar precarga correctamente
- [ ] Response status es 200 (no 404)
- [ ] Página accesible sin agregar `/public/` (si .htaccess funciona)

---

## 📝 PRÓXIMOS PASOS

1. ✅ Código actualizado en localhost
2. ⏳ Subir cambios a producción
3. ⏳ Probar en producción con este checklist
4. ⏳ Si falla, capturar screenshot de Console + Network para debugging
5. ⏳ Si funciona, actualizar `SOLUCION_SERVIDOR_COMPARTIDO.md`

---

**Última actualización:** 2026-05-07 22:45:00
