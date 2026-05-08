# 📋 BUENAS PRÁCTICAS - DESARROLLO SEGURO Y ESTABLE

## 🎨 PROTOCOLO: CAMBIOS EN VISTAS/UI/CSS (NUEVO - CRÍTICO)

⚠️ **Problema:** Cambios en vistas pueden causar layouts desbordados, campos fuera de lugar, o desalineación sin generar errores de compilación.

**Caso Real:** Al actualizar `auth/login.blade.php` con nuevos logos dinámicos, los campos de email y contraseña se desbordaban a la derecha sin errores visibles en el código.

### ✅ PROTOCOLO COMPLETO: "VALIDACIÓN 360° DE VISTAS"

#### PASO 1: AUDITORÍA PRE-CAMBIOS (ANTES de tocar el archivo)

```
Archivo a cambiar: resources/views/auth/login.blade.php

Checklist:
- [ ] ¿Está actualmente funcional sin errores?
- [ ] ¿Dónde se usa esta vista? (login, rutas, controlador)
- [ ] ¿Qué pantallas debo probar? (desktop, tablet, móvil)
- [ ] ¿Hay CSS externo que afecta? (Tailwind, Bootstrap, custom)
- [ ] ¿Hay JavaScript que manipula el DOM?
```

#### PASO 2: AUDITORÍA DE CSS Y LAYOUT (CRÍTICO)

**Antes de cambios:**
```
✓ Inspeccionar elemento en navegador (F12)
✓ Revisar el ancho del contenedor principal
✓ Verificar que NO hay overflow-x o overflow-y activado
✓ Buscar elementos con width: 100% sin box-sizing: border-box
✓ Verificar padding/margin que no resten al ancho
```

**Checklist CSS:**
```bash
# Buscar en el archivo CSS/estilos:
- [ ] ¿Hay estilos conflictivos sin !important?
- [ ] ¿El contenedor padre tiene max-width definido?
- [ ] ¿Se usa flex correctamente con flex-wrap?
- [ ] ¿Hay elementos con width: auto que no tienen restricción?
- [ ] ¿Hay elementos con overflow que podrían desbordar?
- [ ] ¿Se aplica box-sizing: border-box a TODO?
- [ ] ¿Hay padding que suma al ancho total?
```

**Checklist HTML/Estructura:**
```
- [ ] ¿El contenedor tiene max-width definido?
- [ ] ¿Todos los elementos hijo respetar el ancho del padre?
- [ ] ¿Hay elementos con inline styles que podrían conflictuar?
- [ ] ¿Se usan clases de frameworks sin conflicto?
```

#### PASO 3: HACER CAMBIOS EN PEQUEÑOS INCREMENTOS

❌ **MALO:** Cambiar 50 cosas en 1 commit
✅ **BUENO:** Cambiar 5 cosas, probar, después las siguientes

```
Cambio 1: Agregar nuevo div con logos
  → Probar en navegador
  → Verificar layout
  → Commit

Cambio 2: Ajustar CSS del nuevo div
  → Probar en navegador
  → Verificar responsive
  → Commit

Cambio 3: Ajustar espaciado
  → Probar en navegador
  → Verificar alineación
  → Commit
```

#### PASO 4: VERIFICACIÓN EN NAVEGADOR (OBLIGATORIO)

**Desktop:**
```
[ ] Abrir http://localhost:3000/login
[ ] Verificar que nada se desborda a la derecha
[ ] Scroll horizontal NO debe aparecer
[ ] Todos los campos están dentro de la caja
[ ] Inspeccionar con F12 y revisar estilos computados
```

**Tablet (iPad - 768px):**
```
[ ] Abrir dev tools → Tamaño 768x1024
[ ] Verificar que layout es responsive
[ ] Campos no se desbordan
[ ] Logos se ajustan correctamente
```

**Móvil (iPhone - 375px):**
```
[ ] Abrir dev tools → Tamaño 375x812
[ ] Verificar que layout es responsive
[ ] Campos legibles sin scroll horizontal
[ ] Botones clickeables
```

#### PASO 5: VALIDACIÓN ESPECÍFICA PARA CAMBIOS CRÍTICOS

**Si cambias CSS de contenedor:**
```css
/* ANTES: Revisar esto */
.container {
    width: 100%;              /* ← Revisar si tiene padding */
    padding: 2rem;            /* ← Esto SUMA al ancho! */
    /* Resultado: 100% + 4rem overflow */
}

/* DESPUÉS: Usar box-sizing: border-box */
.container {
    width: 100% !important;
    box-sizing: border-box !important;   /* ← IMPORTANTE */
    padding: 2rem !important;
    max-width: 448px !important;         /* ← Limitar ancho */
    overflow-x: hidden !important;       /* ← Prevenir desbordes */
}
```

**Si cambias estructura HTML:**
```blade
<!-- ANTES: Revisar que el padre tiene restricción -->
<div class="form-container">  <!-- ← ¿Tiene max-width? -->
    <input class="form-input"> <!-- ← ¿Respeta ancho padre? -->
</div>

<!-- DESPUÉS: Asegurar restricciones -->
<div class="form-container" style="max-width: 448px; width: 100%;">
    <input class="form-input" style="width: 100%;"> <!-- ← Explícito -->
</div>
```

#### PASO 6: TESTING AUTOMATIZADO (Opcional pero recomendado)

```bash
# Verificar que el archivo no tiene errores de sintaxis
php artisan view:cache

# Verificar que hay rutas() válidas
grep -o "route('[^']*'" resources/views/auth/login.blade.php | sort -u

# Para cada route(), verificar que existe
php artisan route:list | grep login
```

#### PASO 7: CLEAN UP Y COMMIT

```bash
# Limpiar caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Ver cambios
git diff resources/views/auth/login.blade.php

# Commit con descripción clara
git commit -m "Fix: Corregir layout desbordado en login

CAMBIOS:
- Agregadas restricciones max-width: 448px a contenedores
- Agregado overflow-x: hidden al body
- Reforzados estilos con !important para evitar conflictos
- Agregado flex-shrink: 0 a logos para evitar compresión
- Probado en desktop (1920x1080), tablet (768x1024), móvil (375x812)

VALIDACIONES:
✓ Nada se desborda a la derecha
✓ Campos dentro del contenedor
✓ Responsive en todos los tamaños
✓ Sin scroll horizontal"
```

---

### 📋 CHECKLIST COMPLETO: "CAMBIOS EN VISTAS/UI/CSS"

```
ANTES DE CAMBIOS:
[ ] ¿La vista está funcionando sin errores actuales?
[ ] ¿Está correctamente responsive en mobile/tablet/desktop?
[ ] ¿He documentado qué voy a cambiar?

DURANTE CAMBIOS:
[ ] Cambios pequeños e incrementales (no todo de una vez)
[ ] Testeo en navegador después de cada cambio
[ ] Inspeccionar con F12 los estilos aplicados
[ ] Verificar que no hay conflictos de CSS

DESPUÉS DE CAMBIOS:
[ ] Desktop (1920x1080): Sin desbordes ✓
[ ] Tablet (768x1024): Responsive ✓
[ ] Móvil (375x812): Legible y funcional ✓
[ ] Sin scroll horizontal ✓
[ ] Todos los elementos dentro de contenedor ✓
[ ] Caches limpios (cache:clear, view:clear) ✓
[ ] Sintaxis válida (view:cache) ✓
[ ] Commit descriptivo ✓

ANTES DE SUBIR A PRODUCCIÓN:
[ ] Revisar en navegador nuevamente
[ ] Probar en múltiples navegadores (Chrome, Firefox, Safari)
[ ] Verificar en móvil real si es posible
[ ] NO cambiar archivos no relacionados en el mismo commit
```

---

### 🚨 PROBLEMAS COMUNES Y SOLUCIONES

| Problema | Causa | Solución |
|----------|-------|----------|
| Campos desbordados a derecha | Sin `max-width` o `overflow-x: hidden` | Agregar restricciones CSS con `!important` |
| Layout desalineado en móvil | `box-sizing` diferente o padding sumando | Usar `box-sizing: border-box !important` |
| Scroll horizontal aparece | Elemento más ancho que viewport | Usar `overflow-x: hidden` en body/contenedor |
| Flex items no se ajustan | `flex-shrink` no configurado | Agregar `flex-shrink: 0` a items |
| Estilos no se aplican | CSS con conflictos o sin precedencia | Usar `!important` solo cuando sea necesario |
| Responsive no funciona | Falta `<meta name="viewport">` | Asegurar que está en `<head>` |
| Cambios no se ven | Caches de Laravel activos | Ejecutar `cache:clear` + `view:clear` |

---

### 💡 TIPS PRÁCTICOS

**Para evitar desbordes:**
```css
/* Universal fix */
* { box-sizing: border-box !important; }
html, body { width: 100% !important; overflow-x: hidden !important; }

/* Contenedores principales */
.container-main {
    width: 100% !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
}

/* Inputs y forms */
input, textarea, select {
    width: 100% !important;
    max-width: 100% !important;
}
```

**Para testing rápido:**
```bash
# Terminal 1: Dev server
php artisan serve

# Terminal 2: Limpiar caches en cada cambio
watch -n 1 'php artisan cache:clear && php artisan view:clear'
```

**Comandos útiles:**
```bash
# Ver cambios antes de commit
git diff resources/views/auth/login.blade.php

# Ver solo los cambios de CSS
git diff resources/views/auth/login.blade.php | grep "style\|class"

# Deshacer cambios si algo va mal
git checkout resources/views/auth/login.blade.php
```

---

## ⚠️ PROBLEMA ENCONTRADO

Cuando se hacen cambios en vistas sin verificar que TODAS las rutas referenciadas existen, se generan errores como:
```
Symfony\Component\Routing\Exception\RouteNotFoundException
Ruta [parametros.contratos.index] no definida
```

---

## 📌 CASO DE ESTUDIO: Error de Ruta incidencias.servicios.estadisticas

### ❌ Problema
La vista `layouts/app.blade.php` línea 210 hacía referencia a:
```blade
<a href="{{ route('incidencias.servicios.estadisticas') }}" ...>
```

Pero la ruta **NO estaba definida** en `routes/incidencias.php`.

### ✅ Solución Aplicada

**Paso 1:** Verificar que el permiso existe en BD
```
✓ Permiso 'servicios.estadisticas' existe en RoleAndPermissionSeeder
✓ Asignado a Admin y Agente
✓ Usado en menú (layouts/app.blade.php)
```

**Paso 2:** Crear la ruta en `routes/incidencias.php`
```php
Route::get('servicios/estadisticas', [ServicioController::class, 'estadisticas'])
    ->name('servicios.estadisticas')
    ->middleware('can:servicios.estadisticas');
```

**Paso 3:** Crear método `estadisticas()` en `ServicioController`
```php
public function estadisticas(): View
{
    // Calcular métricas
    $totalServicios = Servicio::count();
    $serviciosPorEstado = Servicio::select('estado_servicio_id')...
    // ... más métricas
    
    return view('incidencias.servicios.estadisticas', [...]);
}
```

**Paso 4:** Crear vista `resources/views/incidencias/servicios/estadisticas.blade.php`
- Dashboard con 4 KPIs principales
- Tabla de clientes por servicios
- Gráfico de técnicos activos

**Paso 5:** Validar y comprometer
```bash
php artisan view:cache  # Validar sintaxis
php artisan cache:clear
git commit -m "Feat: Implementar Estadísticas de Servicios con protocolo"
```

### 🎯 Lecciones Aprendidas

1. **Permiso sin implementación = Error garantizado**
   - Si se crea un permiso en seeder, debe haber:
     - ✓ Ruta en routes/[modulo].php
     - ✓ Método en Controlador
     - ✓ Vista correspondiente
     - ✓ Link en menú si es UI

2. **Checklist Pre-Desarrollo**
   - [ ] ¿Existe la ruta?
   - [ ] ¿Existe el método?
   - [ ] ¿Existe la vista?
   - [ ] ¿El permiso está asignado a roles?
   - [ ] ¿Se agregó el link en menú?

---

## 📌 CASO DE ESTUDIO 2: Filtro Dinámico - Sedes por Empresa

### ✅ Problema Resuelto
En formulario de usuarios (`seguridad/usuarios/edit`), el dropdown de Sedes mostraba TODAS las sedes sin filtrar por la empresa seleccionada.

### ✅ Solución Aplicada

**Verificación Pre-Desarrollo:**
```
✓ API endpoint /api/sedes-por-empresa EXISTÍA en routes/web.php (línea 118)
✓ Relación BD correcta: Sede.empresa_id → Empresa
✓ Validación servidor: sede_id verificado en UsuarioController
✓ Modelos correctos: User.empresa() y User.sede()
```

**Paso 1:** Agregar script JavaScript en vistas
- Ubicación: `resources/views/seguridad/usuarios/edit.blade.php`
- Ubicación: `resources/views/seguridad/usuarios/create.blade.php`

**JavaScript implementado:**
```javascript
// Escuchar cambios en dropdown #empresa_id
empresaSelect.addEventListener('change', function() {
    // Obtener sedes de empresa seleccionada
    fetch(`/api/sedes-por-empresa?empresa_id=${this.value}`)
        .then(response => response.json())
        .then(sedes => {
            // Actualizar dropdown de sedes dinámicamente
            sedeSelect.innerHTML = '<option value="">Seleccionar sede...</option>';
            sedes.forEach(sede => {
                const option = document.createElement('option');
                option.value = sede.id;
                option.textContent = sede.nombre;
                sedeSelect.appendChild(option);
            });
        });
});
```

**Paso 2:** Validar y comprometer
```bash
php artisan view:cache  # Validar sintaxis
php artisan cache:clear
git commit -m "Feat: Implementar filtro dinámico de Sedes por Empresa"
```

### 🎯 Ventajas de esta Implementación

1. **Sin cambios en BD:** Solo JavaScript, datos intactos
2. **Reutiliza API existente:** No fue necesario crear nuevas rutas
3. **UX mejorada:** Usuario solo ve sedes de su empresa
4. **Validación servidor intacta:** Código backend no cambió
5. **Compatible ambas vistas:** Funciona en create y edit

### 📋 Checklist Usado

```
✅ 1. API endpoint ya existía → /api/sedes-por-empresa
✅ 2. Relaciones BD correctas → Sede.empresa_id
✅ 3. Validación servidor → UsuarioController update()
✅ 4. Únicamente cambio frontend → JavaScript
✅ 5. Sin efectos secundarios → Otras formas intactas
✅ 6. Caches limpios → view:cache OK
✅ 7. Commit descriptivo → Protocolo aplicado
```

---

### 1. ANTES DE MODIFICAR VISTAS
Siempre verificar:
- [ ] ¿Existen TODAS las rutas referenciadas en routes/*.php?
- [ ] ¿Los controladores están asignados a esas rutas?
- [ ] ¿El middleware está configurado correctamente?

### 2. AUDITORÍA DE RUTAS
Ejecutar:
```bash
php artisan route:list  # Ver todas las rutas definidas
```

O revisar manualmente los archivos:
```
routes/
├── web.php
├── seguridad.php
├── administrativo.php
├── parametros.php
└── incidencias.php
```

### 3. VERIFICAR REFERENCIAS EN VISTAS
Buscar todas las referencias a rutas en templates:
```bash
# Buscar route(...)
grep -r "route(" resources/views/
```

### 3.5 🆕 AUDITORÍA DE RUTAS EN VISTAS (NUEVO - CRÍTICO)

⚠️ **Paso que falta cuando solo se hacen cambios en vistas**

**Problema:** Una vista puede hacer `route()` a una ruta que NO existe
- Ejemplo: `route('parametros.equipos.exportar.pdf')` pero no existe la ruta
- El error solo se ve cuando un usuario accede a esa vista
- Afecta toda la página (error 500)

**Solución (Auditoría completa):**

**1. Listar TODAS las rutas usadas en vistas**
```bash
grep -rho "route('[^']*'" resources/views/ | sort -u
```
Ejemplo output:
```
route('seguridad.usuarios.index'
route('parametros.equipos.exportar.pdf'
route('incidencias.servicios.estadisticas'
...
```

**2. Para cada ruta, verificar que existe**
```bash
php artisan route:list | grep "equipos.exportar.pdf"
```
Debe mostrar algo como:
```
equipos.exportar.pdf    GET|HEAD   /parametros/equipos/exportar/pdf
```

**3. Si NO existe:**
```
❌ BLOQUEAR el cambio
❌ NO hacer commit
❌ NO subir a producción
```

**4. Acciones si ruta NO existe:**
- **Opción A**: Crear la ruta en routes/[modulo].php
- **Opción B**: Remover `route()` de la vista
- **Opción C**: Cambiar la referencia por otra ruta que SÍ exista

**5. Validar después de resolver:**
```bash
php artisan route:list | grep "nombre-ruta"
```

**Ejemplos de errores que PREVIENE:**
- ✅ `route('parametros.equipos.exportar.pdf')` pero no existe → ❌ Error 500
- ✅ `route('incidencias.servicios.estadisticas')` pero no existe → ❌ Error 500
- ✅ `route('parametros.contratos.index')` pero no existe → ❌ Error 500

---

### 5. COMPARAR RUTAS
Crear lista de:
- ✓ Rutas DEFINIDAS en routes/
- ✓ Rutas USADAS en vistas/
- ✗ Rutas FALTANTES

### 6. RESOLVER DISCREPANCIAS
Para cada ruta faltante:
- **Opción A**: Crear la ruta si es necesaria
- **Opción B**: Remover la referencia en la vista si no es necesaria
- **Opción C**: Mover a carpeta `_deprecated/` si será usado después

---

## 📦 ESTRUCTURA ACTUAL (VALIDADA)

### Rutas Disponibles
```
✓ administrativo.paises.*
✓ administrativo.departamentos.*
✓ administrativo.municipios.*
✓ parametros.empresas.*
✓ parametros.sedes.*
✓ parametros.clientes.*
✓ parametros.areas.*
✓ parametros.equipos.*
✓ parametros.tipos-equipos.*
✓ incidencias.servicios.*
✓ seguridad.usuarios.*
✓ seguridad.roles.*
✓ seguridad.permissions.*
```

### Rutas NO Disponibles (Removidas de menús)
```
✗ parametros.contratos.*  → Movido a _deprecated/
✗ parametros.documentos.* → Movido a _deprecated/
✗ categorias.*            → Movido a _deprecated/
```

---

## 🔍 VALIDACIÓN PRE-CAMBIOS

Antes de cualquier modificación:

1. **Listar todas las rutas a usar**
   ```php
   route('seguridad.usuarios.index')  ✓ Definida
   route('parametros.empresas.show')  ✓ Definida
   route('contratos.index')           ✗ NO DEFINIDA
   ```

2. **Verificar en el código**
   ```bash
   # En routes/seguridad.php
   Route::resource('usuarios', UsuarioController::class);  ✓
   
   # En routes/parametros.php
   Route::resource('empresas', EmpresaController::class);  ✓
   ```

3. **Si no existe la ruta:**
   - OPCIÓN 1: No usarla en vistas
   - OPCIÓN 2: Crearla en routes/
   - OPCIÓN 3: Documentar como "TODO" para después

---

## 🛡️ PROCESO DE CAMBIO SEGURO

### Paso 1: Preparar cambios
```bash
# Crear rama o checkpoint
git status
git diff  # Ver qué va a cambiar
```

### Paso 2: Documentar cambios
```
- Cambio 1: Descripción
- Cambio 2: Descripción
- Verificaciones: ✓ Ruta X existe, ✓ Ruta Y definida
```

### Paso 3: Hacer cambios pequeños
```bash
# NO: Hacer 50 cambios a la vez
# SÍ: Hacer 5 cambios y verificar
```

### Paso 4: Limpiar caches
```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

### Paso 5: Verificar en navegador
```
- Ir a http://localhost:8000/dashboard
- Revisar que NO haya errores
- Clic en menús para verificar rutas
```

### Paso 6: Commit
```bash
git commit -m "Descripción clara de qué cambió y por qué"
```

---

## � CASO DE ESTUDIO 3: Ruta Faltante - equipos.exportar.pdf

### ❌ Problema
Vista `parametros/equipos/index.blade.php` línea 99 usaba:
```blade
{{ route('parametros.equipos.exportar.pdf', request()->query()) }}
```

Pero la ruta **NO existía** → Error 500 al acceder a la página

### ✅ Solución con nuevo protocolo PASO 3.5

**1. Auditar rutas usadas:**
```bash
grep -rho "route('[^']*'" resources/views/ | grep equipos.exportar
```

**Resultado:**
```
✅ equipos.exportar.excel  (EXISTE)
❌ equipos.exportar.pdf    (NO EXISTE)
```

**2. Crear la ruta faltante:**
```php
// routes/parametros.php
Route::get('equipos/exportar/pdf', [EquipoController::class, 'exportarPdf'])
    ->name('equipos.exportar.pdf');
```

**3. Implementar el método:**
```php
public function exportarPdf()
{
    $equipos = Equipo::with(['area.sede.cliente', 'area.sede.empresa', 'tipoEquipo'])
        ->orderBy('codigo_interno')
        ->get();
    
    $data = [
        'titulo' => 'Listado de Equipos',
        'fecha_reporte' => now()->format('d/m/Y H:i:s'),
        'total_equipos' => $equipos->count(),
        'equipos' => $equipos,
    ];
    
    $pdf = \PDF::loadView('parametros.equipos.pdf', $data);
    $pdf->setPaper('A4', 'landscape');
    
    return $pdf->download('equipos_' . date('Y-m-d_His') . '.pdf');
}
```

**4. Crear vista PDF:**
- Tabla profesional con 10 columnas
- Estilos para impresión
- Badges de color por estado
- Metadatos del reporte

### 🎯 Impacto del protocolo

| Aspecto | Sin PASO 3.5 | Con PASO 3.5 |
|---------|-------------|------------|
| Detección | En producción (usuario) | Pre-commit (desarrollador) |
| Tiempo de fix | Horas/días | Minutos |
| Impacto | Error 500 (sitio caído) | Prevención |
| Calidad | Baja (errores en vivo) | Alta (validado) |

---



### Si cambio rutas en views/layouts/app.blade.php
- [ ] Verificar que la ruta exista en routes/
- [ ] Verificar que el permiso corresponda
- [ ] Probar clic en menú
- [ ] Verificar que carga la página sin errores

### Si creo una nueva vista
- [ ] ¿Existe la ruta correspondiente en routes/?
- [ ] ¿Está asignado el controlador correcto?
- [ ] ¿El middleware está bien configurado?
- [ ] ¿No hace referencia a rutas que no existen?

### Si elimino una ruta
- [ ] ¿He removido todas las referencias a esa ruta?
- [ ] ¿He buscado en todas las vistas?
- [ ] ¿He actualizado los menús?

---

## 🔧 HERRAMIENTAS ÚTILES

```bash
# Ver todas las rutas
php artisan route:list

# Buscar referencias a una ruta
grep -r "route('contratos" resources/views/

# Verificar sintaxis de Blade
php artisan view:cache

# Encontrar todos los route() calls
grep -r "route(" resources/views/ | wc -l
```

---

## ✨ RESUMEN

**ANTES DE HACER CAMBIOS:**
1. Verificar que TODAS las rutas existen
2. Buscar referencias a rutas no-existentes
3. Resolver discrepancias
4. DESPUÉS: Hacer los cambios

**DESPUÉS DE HACER CAMBIOS:**
1. Limpiar caches
2. Probar en navegador
3. Verificar sin errores
4. Hacer commit con descripción clara

---

## 📋 CASO DE ESTUDIO 4: Orden de Rutas y Model Binding

### ❌ Problema
Botón "Descargar Excel" en `parametros/equipos/index.blade.php` estaba en blanco.

Error: Ruta `parametros.equipos.exportar.excel` no funcionaba aunque estaba definida.

**Archivo `routes/parametros.php` (INCORRECTO):**
```php
Route::resource('equipos', EquipoController::class);  // ← PRIMERO
Route::get('equipos/exportar/excel', [EquipoController::class, 'exportarExcel'])
    ->name('equipos.exportar.excel');  // ← DESPUÉS
Route::get('equipos/exportar/pdf', [EquipoController::class, 'exportarPdf'])
    ->name('equipos.exportar.pdf');
```

### 🔍 Root Cause

`Route::resource()` registra TODAS las rutas CRUD incluida:
```
GET /equipos/{equipo}  ← Model Binding
```

Laravel intenta matching de rutas en ORDEN:
1. `/equipos/exportar/excel` → ¿Es equipos/{id}? → "exportar" es el ID
2. Laravel intenta: `Equipo::findOrFail('exportar')` → 404
3. Nunca llega a la ruta específica (está después)

### ✅ Solución

**Archivo `routes/parametros.php` (CORRECTO):**
```php
// ✅ RUTAS ESPECÍFICAS PRIMERO
Route::get('equipos/exportar/excel', [EquipoController::class, 'exportarExcel'])
    ->name('equipos.exportar.excel');
Route::get('equipos/exportar/pdf', [EquipoController::class, 'exportarPdf'])
    ->name('equipos.exportar.pdf');

// ✅ RESOURCE DESPUÉS (model binding ya no interfiere)
Route::resource('equipos', EquipoController::class);
```

Ahora Laravel intenta matching así:
1. `/equipos/exportar/excel` → ✅ Coincide ruta específica
2. `/equipos/{equipo}` → ✅ Si no es exportar/excel ni pdf

### 📚 Regla General

**Cuando uses rutas específicas CON Route::resource():**

```php
// ❌ INCORRECTO (specific route DESPUÉS de resource)
Route::resource('items', ItemController::class);
Route::get('items/export/pdf', ...);  // NUNCA se ejecutará

// ✅ CORRECTO (specific routes ANTES de resource)
Route::get('items/export/pdf', ...);
Route::get('items/export/excel', ...);
Route::resource('items', ItemController::class);  // Resource al final
```

### 🎯 Validación

Después de ordenar las rutas:
```bash
php artisan route:list | Select-String "equipos.exportar"
```

Debe mostrar:
```
GET|HEAD   parametros/equipos/exportar/excel    parametros.equipos.exportarExcel
GET|HEAD   parametros/equipos/exportar/pdf      parametros.equipos.exportarPdf
```

✅ Si las rutas aparecen, los botones funcionarán correctamente.

---

**ÚLTIMA ACTUALIZACIÓN**: 2/05/2026
**ESTADO**: Sistema estable después de limpiar referencias obsoletas
