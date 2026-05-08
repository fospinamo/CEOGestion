# 📤 INSTRUCCIONES DE DESPLIEGUE - CASCADAS AJAX (7 de mayo 2026)

**Commit:** `623d3fd`  
**Archivos modificados:** 2
**Caché:** ✅ Limpiado localmente

---

## 🚀 PASOS DE DESPLIEGUE A PRODUCCIÓN

### PASO 1: Conectar al servidor por File Manager (cPanel)

```
Ir a: cPanel → File Manager
Navegue a: public_html/gestion/CEOGestion/
```

### PASO 2: Actualizar archivos

Descargar/overwrite los siguientes archivos desde Git:

```
resources/views/parametros/sedes/create.blade.php
resources/views/parametros/sedes/edit.blade.php
```

**O por terminal (si tiene acceso SSH):**
```bash
cd ~/public_html/gestion/CEOGestion/
git pull origin master
php artisan view:clear
php artisan cache:clear
```

### PASO 3: Verificar cambios

En File Manager, abrir:
```
/resources/views/parametros/sedes/create.blade.php
```

Buscar texto: `getApiBase()`

**Si aparece**, cambio fue exitoso ✅

---

## 🧪 PRUEBA INMEDIATA

### Test 1: Abrir página (sin /public/)
```
https://gestion.simotec.com.co/CEOGestion/parametros/sedes/create
```

**Resultado esperado:**
- ✅ Página carga correctamente
- ✅ Sin error 404

**Si falla con 404:**
- Agregue `/public/` manualmente:
  ```
  https://gestion.simotec.com.co/CEOGestion/public/parametros/sedes/create
  ```

### Test 2: Verificar Console (F12)

Presionar `F12` → Pestaña `Console`

**Debería mostrar:**
```
🔍 Inicializando cascada de sedes...
📂 Path segments: (5) ['gestion', 'CEOGestion', 'public', 'parametros', 'sedes']
✅ API Base calculado: https://gestion.simotec.com.co/gestion/CEOGestion/public/api
✅ Script de cascada cargado correctamente
```

**Si no ve estos logs:**
- Actualizar página (Ctrl+F5)
- Vaciar caché del navegador

### Test 3: Probar cascada - Departamento

Seleccionar un departamento en el formulario

**Console debe mostrar:**
```
📍 Departamento seleccionado: 5
🌐 URL del API (absoluta): https://gestion.simotec.com.co/gestion/CEOGestion/public/api/municipios-por-departamento?departamento_id=5
📊 Response status: 200
✅ Municipios recibidos: [{...}, {...}]
```

**Si ve `Response status: 404`:**
- La ruta de API no está siendo encontrada
- Verificar rutas: `php artisan route:list | grep api`

**Si no ve respuesta:**
- Ir a DevTools → Network tab
- Buscar request `municipios-por-departamento`
- Revisar si está en rojo (error)

### Test 4: Probar cascada - Municipio

Seleccionar un municipio

**Esperado:** Se cargan barrios (parecido a Test 3)

### Test 5: Probar página de editar

```
https://gestion.simotec.com.co/CEOGestion/parametros/sedes/1/edit
```

**Esperado:**
- ✅ Pre-carga municipios y barrios
- ✅ Preserva valores seleccionados
- ✅ Cascadas funcionan igual

---

## ❌ SOLUCIÓN DE PROBLEMAS

### Problema 1: Cascadas cargan vacías (sin error)
**Causa probable:** Response es vacía o inválida
```javascript
// En Console, ejecutar:
fetch('https://gestion.simotec.com.co/gestion/CEOGestion/public/api/municipios-por-departamento?departamento_id=1')
  .then(r => r.json())
  .then(d => console.log(d))
  .catch(e => console.error(e))
```
**Si error:** API no está funcionando

### Problema 2: Error 404 en cascadas
**Causa:** Ruta de API no existe
```bash
# Verificar rutas registradas:
php artisan route:list | grep "api/"
```
Debe mostrar:
```
GET|HEAD  api/municipios-por-departamento
GET|HEAD  api/barrios-por-municipio
```

### Problema 3: Página no carga (error 404 general)
**Causa:** .htaccess no funciona o mod_rewrite deshabilitado
```
URL requerida: https://gestion.simotec.com.co/CEOGestion/public/parametros/sedes/create
```

---

## ✅ CHECKLIST FINAL

- [ ] Archivos subidos (`create.blade.php`, `edit.blade.php`)
- [ ] Console muestra `API Base calculado` correctamente
- [ ] Cascada Departamento → Municipio funciona
- [ ] Cascada Municipio → Barrio funciona
- [ ] Página de editar pre-carga correctamente
- [ ] Response status es 200 (no 404)
- [ ] No hay errores en Console

---

## 📞 SOPORTE

### Si cascadas siguen sin funcionar después de probar todo:

1. **Capturar screenshot de:**
   - Console completa (Ctrl+Shift+K en Firefox)
   - Network tab → Request `municipios-por-departamento`

2. **Enviar información:**
   - URL exacta que intentó
   - Error exacto de Console
   - Response status (200, 404, 500, etc.)
   - Response body (si es visible)

3. **Contactar hosting:**
   - Preguntar si mod_rewrite está habilitado
   - Pedir que habiliten mod_rewrite en .htaccess
   - Verificar que rutas API sean accesibles

---

## 🎯 RESULTADO ESPERADO

Una vez completado:
- ✅ Cascadas funcionan en producción
- ✅ Sin requerimiento de `/public/` en URL (si mod_rewrite funciona)
- ✅ Usuarios pueden crear/editar sedes sin problemas
- ✅ Municipios se cargan al seleccionar departamento
- ✅ Barrios se cargan al seleccionar municipio

---

**Próximo paso:** Ejecutar pruebas y reportar resultado

**Documentación creada:** 2026-05-07 22:50:00
