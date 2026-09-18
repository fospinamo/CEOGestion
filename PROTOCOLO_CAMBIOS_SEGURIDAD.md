# 📋 PROTOCOLO DE CAMBIOS Y BITÁCORA DE ERRORES
**Proyecto:** CEOGestion  
**Fecha Inicio:** 7 de Mayo 2026  
**Versión:** 1.0

---

## 1. PROTOCOLO DE CAMBIOS OBLIGATORIO

### Antes de hacer cualquier cambio:

1. **INVESTIGACIÓN** (OBLIGATORIO - no saltar este paso)
   - [ ] **PRIMERO:** Buscar en la bitácora de errores (sección 2 de este archivo) por síntomas similares
   - [ ] Si el error ya está documentado → replicar la solución implementada (NO reinventar)
   - [ ] Si no está en bitácora → buscar en documentación existente
   - [ ] Identificar raíz del problema
   - [ ] Revisar si ya fue corregido antes (puede ser regresión)

### Antes de CERRAR un cambio (VERIFICACIÓN OBLIGATORIA):

2. **IMPACTO EN CADENA** (CADA CAMBIO PUEDE AFECTAR MÚLTIPLES ARCHIVOS)
   - [ ] **MODELO:** ¿Qué campos/relaciones tiene el modelo afectado?
   - [ ] **CONTROLADOR:** ¿Qué métodos del controlador usan ese modelo? (index, show, create, edit, store, update, destroy)
   - [ ] **VISTAS:** ¿Qué vistas Blade muestran los campos del modelo?
   - [ ] **PDF/EXPORTES:** ¿Hay vistas PDF o exportación que usen esos campos?
   - [ ] **AJAX/JS:** ¿Hay llamadas AJAX que retornen datos del modelo?
   - [ ] **EAGER-LOADING:** ¿Todos los `with()` incluyen las relaciones necesarias?

3. **CHECKLIST DE VERIFICACIÓN POR TIPO DE CAMBIO**

   **Si cambias un CAMPO en BD (renombrar, agregar, eliminar):**
   - [ ] `Schema::getColumnListing('tabla')` → verificar columnas reales
   - [ ] Modelo `$fillable` → agregar/quitar campo
   - [ ] Modelo `$casts` → si aplica
   - [ ] Controlador `store()` → actualizar validación
   - [ ] Controlador `update()` → actualizar validación
   - [ ] Controlador `create()` → pasar datos a vista si es catálogo
   - [ ] Controlador `edit()` → pasar datos a vista si es catálogo
   - [ ] TODAS las vistas que muestran el campo → actualizar nombre
   - [ ] TODOS los controladores que eager-load → incluir relación
   - [ ] PDFs y exportes → actualizar si muestran el campo
   - [ ] JavaScript/AJAX → actualizar si mapea el campo

   **Si agregas una RELACIÓN (FK) a un modelo:**
   - [ ] Modelo: agregar método `belongsTo()` / `hasMany()`
   - [ ] Modelo: agregar FK a `$fillable`
   - [ ] Controlador: agregar `with('relacion')` en TODOS los queries
   - [ ] Vistas: cambiar `$modelo->campo_string` → `$modelo->relacion?->campo`
   - [ ] PDFs: idem
   - [ ] AJAX: idem

   **Si creas un CRUD completo:**
   - [ ] SIEMPRE usar `form.blade.php` compartido para create/edit
   - [ ] NUNCA duplicar formularios en create.blade.php y edit.blade.php
   - [ ] Verificar que `store()` y `update()` validen los MISMOS campos que el formulario
   - [ ] Verificar que los `name=""` del HTML coincidan con las reglas `validate()`

4. **COMANDOS DE VERIFICACIÓN RÁPIDA**
   ```bash
   # Verificar columnas reales de una tabla
   php artisan tinker --execute="echo implode(',', Schema::getColumnListing('NOMBRE_TABLA'));"
   
   # Verificar que una relación existe en el modelo
   php artisan tinker --execute="echo method_exists(App\Models\ModeleName::class, 'relacion') ? 'EXISTS' : 'MISSING';"
   
   # Limpiar caches después de cambios
   php artisan view:clear; php artisan cache:clear; php artisan route:clear;
   
   # Verificar rutas
   php artisan route:list --name=NOMBRE_RUTA
   ```

### Después del cambio:

5. **REVISIÓN POST-CAMBIO**
   - [ ] Verificar que no rompe otras funciones
   - [ ] Buscar efectos secundarios (usar grep por campo/relación afectada)
   - [ ] Documentar en bitácora
   - [ ] Commit descriptivo en git

---

## 2. BITÁCORA DE ERRORES

### ERROR #1: Municipios - SyntaxError "JSON inválido" (7 Mayo 2026)

**Estado:** ✅ RESUELTO

**Síntomas:**
- Error: `SyntaxError: Unexpected token '<', "<!DOCTYPE "... is not valid JSON`
- Ubicación: Formulario crear sede (select municipio)
- Afecta: `api/municipios-por-departamento`
- Ambiente: Producción

**Ruta:** `/api/municipios-por-departamento`  
**Método:** GET  
**Parámetros:** `departamento_id` (query)

**Análisis:**
- ✅ Ruta existe en `routes/web.php`
- ✅ Devuelve JSON válido en código
- ✅ Error 404 o error 500 → recibe HTML por excepción sin manejo
- ✅ Modelo Municipio existe y estructura BD correcta

**Causa Raíz:**
- Ruta devuelve arrays en lugar de JSON response
- No hay try/catch → excepciones generan HTML error
- No hay validación de entrada numérica
- No hay logging de errores

**Solución Implementada:**

```php
// ANTES (inseguro - sin manejo de errores)
Route::get('/api/municipios-por-departamento', function () {
    $departamento_id = request()->query('departamento_id');
    if (!$departamento_id) {
        return [];  // ❌ Array, no JSON
    }
    return \App\Models\Municipio::where('departamento_id', $departamento_id)
        ->orderBy('nombre')
        ->get(['id', 'nombre']);
});

// DESPUÉS (seguro - con buenas prácticas)
Route::get('/api/municipios-por-departamento', function () {
    try {
        $departamento_id = request()->query('departamento_id');
        
        // Validación: debe ser numérico
        if (!$departamento_id || !is_numeric($departamento_id)) {
            return response()->json([], 200); // ✅ JSON response
        }
        
        // Query segura
        $municipios = \App\Models\Municipio::where('departamento_id', (int) $departamento_id)
            ->orderBy('nombre')
            ->get(['id', 'nombre']);
        
        return response()->json($municipios, 200);
        
    } catch (\Exception $e) {
        // Log del error
        \Illuminate\Support\Facades\Log::error('API municipios-por-departamento error', [
            'departamento_id' => request()->query('departamento_id'),
            'error' => $e->getMessage(),
            'user_id' => auth()->id(),
        ]);
        
        // Retorna JSON error, nunca HTML
        return response()->json([], 200);
    }
});
```

**Archivos Modificados:**
- `routes/web.php` (líneas 115-142)
  - `/api/municipios-por-departamento` → Con try/catch
  - `/api/sedes-por-empresa` → Con try/catch
  - `/api/sedes-por-cliente` → Con try/catch

**Mejoras de Seguridad Aplicadas:**
- ✅ `response()->json()` en lugar de arrays (siempre retorna JSON)
- ✅ `try/catch` para capturar excepciones
- ✅ Validación numérica: `is_numeric()` + cast `(int)`
- ✅ Logging: `Log::error()` con contexto
- ✅ Previene HTML error responses

**Buenas Prácticas Aplicadas:**
- ✅ Consistencia: todas las APIs usan mismo patrón
- ✅ Seguridad: validación de entrada + error handling
- ✅ Observabilidad: logging en `storage/logs/laravel.log`
- ✅ Documentación inline con comentarios

**Verificación:**
- ✅ Cache limpio
- ✅ Rutas testadas en localhost
- ✅ JSON válido en respuestas
- ✅ Errores logueados correctamente
- ✅ Sin errores en DevTools

---

### ERROR #2: DataTables tablaSedes - "_DT_CellIndex" undefined (7 Mayo 2026)

**Estado:** ✅ RESUELTO (Intento 2)

**Síntomas:**
- Error: `Cannot set properties of undefined (setting '_DT_CellIndex')`
- Ubicación: Página `/parametros/sedes` (lista de sedes)
- Causa anterior (Error #2): Mismatch colspan/columnDefs
- Nueva causa encontrada: Conflicto en inicialización de DataTables

**Análisis:**
- ✅ Colspan y columnDefs estaban correctos (7 columnas)
- ❌ Pero DataTables fallaba al inicializar la tabla
- ❌ Probablemente por conflicto con fila vacía (@empty)
- ❌ O por inicialización múltiple

**Problema Raíz:**
El código anterior:
```javascript
// PROBLEMA: Inicializa sin validar estado
$(document).ready(function() {
    $('#tablaSedes').DataTable({...});  // ❌ Falla si tabla está vacía
});
```

**Solución Implementada:**

```javascript
// ✅ MEJOR: Validar y destruir antes de inicializar
$(document).ready(function() {
    // Destruir tabla anterior si existe (evita conflictos)
    if ($.fn.DataTable.isDataTable('#tablaSedes')) {
        $('#tablaSedes').DataTable().destroy();
    }
    
    // Solo inicializar si hay datos reales
    const tableRows = $('#tablaSedes tbody tr').length;
    const hasData = tableRows > 1 || (tableRows === 1 && !$('#tablaSedes tbody tr').text().includes('No hay sedes'));
    
    if (hasData) {
        $('#tablaSedes').DataTable({
            "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json" },
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": 6 },  // Acciones
                { "width": "10%", "targets": [1, 5] }   // Ancho fijo
            ],
            "order": [[0, "asc"]],
            "pageLength": 10,
            "paging": true,
            "searching": true,
            "info": true
        });
    }
});
```

**Cambios:**
1. ✅ Agregar check: `$.fn.DataTable.isDataTable('#tablaSedes')`
2. ✅ Destruir si existe: `.destroy()`
3. ✅ Validar que hay datos antes de inicializar
4. ✅ Agregar ancho fijo a columnas problemáticas
5. ✅ Documentación inline clara

**Archivo Modificado:**
- `resources/views/parametros/sedes/index.blade.php` (script section)

**Verificación:**
- ✅ Cache limpio
- ✅ Testado en localhost
- ✅ Sin errores en DevTools
- ✅ Tabla visible y funcional
- ✅ Paginación funciona
- ✅ Búsqueda funciona
- ✅ Orden funciona

---

### ERROR #3: Favicon 404 (7 Mayo 2026)

**Estado:** ✅ RESUELTO

**Síntomas:**
- Error: `GET /favicon.ico 404 (Not Found)`
- Ubicación: Browser console

**Solución Implementada:**
- Agregó favicon SVG inline en HEAD
- Archivos modificados:
  - `resources/views/layouts/app.blade.php`
  - `resources/views/auth/login.blade.php`
  - `resources/views/auth/register.blade.php`

---

### ERROR #4: Selects en Cascada - Barrios sin cargar por Municipio (7 Mayo 2026)

**Estado:** ✅ RESUELTO

**Síntomas:**
- Municipios se filtran correctamente por Departamento ✅
- Pero **Barrios NO se cargan** al seleccionar Municipio ❌
- Ubicación: Formularios crear/editar sedes
- Falta: Ruta API `/api/barrios-por-municipio`
- Falta: Evento JavaScript para municipios → barrios

**Análisis:**
- ✅ Ruta para municipios existe
- ✅ JavaScript para municipios existe
- ❌ Ruta para barrios NO existe
- ❌ JavaScript para barrios NO existe

**Solución Implementada:**

#### 1. **Ruta API** (routes/web.php línea ~147)
- ✅ Agregó endpoint `/api/barrios-por-municipio`
- ✅ Con try/catch y logging
- ✅ Validación numérica + cast `(int)`
- ✅ Response siempre JSON

#### 2. **JavaScript en Vistas**
- ✅ `resources/views/parametros/sedes/create.blade.php`: Evento municipio → barrios
- ✅ `resources/views/parametros/sedes/edit.blade.php`: Evento + cargar barrios al init
- ✅ Ids únicos: `municipioSelect`, `barrioSelect`
- ✅ Manejo de errores en fetch

**Funcionalidad Cascada Completa:**

```
CREAR SEDE:
1. Usuario selecciona DEPARTAMENTO
   ↓ API: /api/municipios-por-departamento
2. Se cargan MUNICIPIOS del departamento
   ↓ Usuario selecciona MUNICIPIO
3. API: /api/barrios-por-municipio
4. Se cargan BARRIOS del municipio
   ↓
5. ✅ Listo para guardar

EDITAR SEDE:
1. Página carga con DEPARTAMENTO y MUNICIPIO seleccionados
   ↓
2. JavaScript en DOMContentLoaded dispara evento municipio
   ↓ API: /api/barrios-por-municipio
3. Se cargan BARRIOS existentes del municipio
   ↓
4. ✅ Barrio preseleccionado si existe
```

**Buenas Prácticas Aplicadas:**
- ✅ Try/catch en ruta API
- ✅ Validación: `is_numeric()` + `(int)` cast
- ✅ Logging: `Log::error()` con contexto completo
- ✅ JSON response: Nunca HTML
- ✅ SQL segura: Eloquent ORM
- ✅ Documentación inline
- ✅ Manejo de errores en JavaScript

**Archivos Modificados:**
- ✅ `routes/web.php` (agregar ruta + try/catch)
- ✅ `resources/views/parametros/sedes/create.blade.php` (JavaScript cascada)
- ✅ `resources/views/parametros/sedes/edit.blade.php` (JavaScript cascada + init)

**Verificación:**
- ✅ Cache limpio
- ✅ Rutas testadas en localhost
- ✅ Cascada funciona correctamente
- ✅ JSON válido en todas las respuestas
- ✅ Sin errores en DevTools
- ✅ En edit: barrios se cargan al abrir formulario

---

## 3. BUENAS PRÁCTICAS APLICADAS

### 🔒 Seguridad

- ✅ Rutas API dentro de `middleware(['auth'])`
- ✅ Validación de input: `request()->query()`
- ✅ Uso de Eloquent (previene SQL injection)
- ✅ Retorno JSON directo (previene XSS)

### ⚠️ Estado de Implementación

- ✅ Agregar `try/catch` en rutas API → IMPLEMENTADO (ERROR #1, #4)
- ✅ Logear errores en `storage/logs/laravel.log` → IMPLEMENTADO
- ✅ Validar entrada con `is_numeric()` → IMPLEMENTADO
- ✅ Response siempre JSON → IMPLEMENTADO
- ⏳ Agregar rate limiting en APIs → PENDIENTE
- ⏳ CORS headers si es necesario → PENDIENTE

### 📝 Documentación

- ✅ Protocolo de cambios definido
- ✅ Bitácora centralizada
- ✅ Antes/después de código
- ✅ Razón de cambios documentada

---

## 4. CHECKLIST POST-IMPLEMENTACIÓN

```
ANTES DE PRODUCCIÓN:
- [ ] Todos los errores de bitácora revisados
- [ ] Protocolo seguido para cada cambio
- [ ] Buenas prácticas verificadas
- [ ] Seguridad auditada
- [ ] Testing en desarrollo OK
- [ ] Cache limpio
- [ ] Sin errores en DevTools
- [ ] Documentación actualizada
```

---

## 5. PRÓXIMAS ACCIONES

1. ✅ **COMPLETADO:** Todos los errores críticos resueltos
2. ✅ **COMPLETADO:** Cascada completa Departamento→Municipio→Barrio
3. 🔄 **EN PROGRESO:** Testing en producción
4. ⏳ **PENDIENTE:** Rate limiting en APIs (security)
5. ⏳ **PENDIENTE:** Unit tests para rutas críticas
6. ⏳ **PENDIENTE:** Audit de seguridad completo

---

## 6. BITÁCORA OPERATIVA RECIENTE

### ERROR #6: Editar Servicio no mostraba datos guardados ni mantenía validaciones de cascada (28 Mayo 2026)

**Estado:** ✅ RESUELTO

**Síntoma:**
- En `incidencias/servicios/{id}/edit` no aparecían correctamente equipo/tipo de servicio ya guardados.
- El formulario de edición no conservaba de forma consistente las validaciones de selects dependientes (Cliente → Sede → Área → Equipo → Tipo).
- Los documentos previamente adjuntos no se mostraban en la pantalla de edición.

**Causa raíz:**
- Desalineación entre backend y frontend del flujo de edición:
    - `update()` validaba un contrato de campos distinto al formulario reutilizado de creación.
    - El JS de cascada no rehidrataba estado inicial para editar (equipos/tipo se cargaban por AJAX pero sin preselección).
    - En edición no se cargaban ni se renderizaban adjuntos existentes.

**Archivos corregidos:**
- `app/Http/Controllers/Incidencias/ServicioController.php`
    - `edit()`: ahora carga relaciones necesarias (`equipo.marca`, jerarquía de ubicación, contrato, documentos adjuntos).
    - `update()`: validación alineada al formulario real (mismos campos clave de `store`), mismas validaciones de pertenencia sede/área/equipo y anexado de nuevos archivos.
- `resources/views/incidencias/servicios/create.blade.php`
    - Se agregan valores iniciales para edición (equipo/tipo/contrato/SLA).
    - Selects `equipo_id` y `tipo_servicio` ya no arrancan vacíos en edición.
    - Se muestra listado de archivos ya cargados al editar.
    - Ajuste de nombre de campo en modal rápido de equipo: `codigo_activo_cliente`.
- `public/js/servicios.js`
    - Se agrega hidratación inicial para edición y preselección en cascadas con AJAX (equipo y tipo de servicio).

**Regla preventiva (obligatoria):**
- Si una vista se reutiliza para crear/editar, el `update()` debe validar exactamente los mismos nombres de campo visibles para el usuario.
- En formularios con selects en cascada por AJAX, implementar siempre rutina de "rehidratación de edición" para reconstruir y seleccionar valores persistidos.
- Los adjuntos existentes deben renderizarse explícitamente en modo edición; el input file por sí solo no refleja archivos previos.

### ERROR #5: Crear Servicio falla por valor de prioridad no permitido en BD (28 Mayo 2026)

**Estado:** ✅ RESUELTO

**Síntoma:**
- Al grabar un nuevo servicio se producía error 500.
- Excepción: `SQLSTATE[01000]: Advertencia: 1265 Datos truncados para la columna 'prioridad'`.
- Valor enviado: `CRITICA`.

**Causa raíz:**
- Desalineación entre capas:
    - Migración `create_servicios_table` define enum `prioridad`: `BAJA`, `MEDIA`, `ALTA`, `URGENTE`.
    - `ServicioController::store()` validaba `CRITICA` y la vista `create.blade.php` enviaba `CRITICA`.
- MySQL intentó insertar un valor fuera del enum y lanzó advertencia/exception que terminó en 500.

**Archivos corregidos:**
- `app/Http/Controllers/Incidencias/ServicioController.php`
    - Se normaliza compatibilidad: si llega `CRITICA`, se convierte a `URGENTE` antes de validar/guardar.
    - Regla `prioridad` en `store()` alineada con BD: `BAJA,MEDIA,ALTA,URGENTE`.
- `resources/views/incidencias/servicios/create.blade.php`
    - El selector ahora envía `URGENTE` en lugar de `CRITICA`.
    - Mantiene selección para datos legacy (`URGENTE` o `CRITICA`).
- `resources/views/incidencias/servicios/admin-panel.blade.php`
    - Se agrega mapeo visual para `URGENTE`.
- `resources/views/incidencias/servicios/index.blade.php`
    - Se agrega mapeo visual para `URGENTE`.

**Regla preventiva (obligatoria):**
- Todo valor de `select` en Blade debe coincidir exactamente con `enum`/`check` real de la base de datos.
- Si se cambia un enum en migraciones, actualizar en el mismo cambio:
    - validaciones del controlador,
    - opciones en formularios,
    - mapeos de color/estado en listados.
- No usar alias de negocio (ej. `CRITICA`) como valor persistido si la BD guarda otro literal (`URGENTE`); usar alias solo como etiqueta visible.

### ERROR #4: Columna `codigo_interno` no existe en `equipos` — AJAX 500 (28 Mayo 2026)

**Estado:** ✅ RESUELTO

**Síntoma:**
- Al seleccionar un Área en el formulario "Registrar Nuevo Servicio", el select de Equipo mostraba "Error al cargar equipos".
- DevTools: `GET /incidencias/servicios/equipos-area/38 500 (Internal Server Error)`.
- SQL: `Unknown column 'codigo_interno' in 'field list'`.

**Causa raíz:**
- La migración `2026_05_25_000002_update_equipos_table_for_client_and_series.php` renombró la columna `codigo_interno` → `codigo_activo_cliente`.
- Los métodos `getEquiposByArea()`, `getEquiposByCliente()` y `crearEquipo()` en `ServicioController` seguían referenciando el nombre anterior.
- El modelo `Equipo` ya usaba `codigo_activo_cliente` en `$fillable`.

**Archivos corregidos:**
- `app/Http/Controllers/Incidencias/ServicioController.php`
  - `getEquiposByCliente()`: cambio de `orderBy('codigo_interno')` + `get([...,'codigo_interno',...])` → `codigo_activo_cliente`. Se agrega `with('marca')` y se mapea `marca` como `$e->marca?->nombre`. El campo JSON sigue siendo `codigo_interno` para no romper el JS.
  - `getEquiposByArea()`: mismo cambio.
  - `crearEquipo()`: validación y creación usan `codigo_activo_cliente`.

**Solución implementada:**
- Columna real en BD: `codigo_activo_cliente`.
- Las respuestas JSON retornan la clave como `codigo_interno` (alias) para no cambiar `public/js/servicios.js` que la consume.

**Regla preventiva (obligatoria):**
- Antes de usar nombre de columna en una query manual (`->get([...])`, `->orderBy(...)`, `->where(...)`), verificar el nombre actual en `$fillable` del modelo Y en las migraciones más recientes.
- Una migración de renombrado de columna NO actualiza automáticamente controladores: buscar con grep todas las referencias al nombre antiguo tras ejecutar un `renameColumn`.
- Comando de diagnóstico rápido: `php artisan tinker --execute="echo implode(',', Schema::getColumnListing('equipos'));"`.

### ERROR #3: Informe técnico rechazaba campos con datos (27 Mayo 2026)

**Estado:** ✅ RESUELTO

**Síntoma:**
- En `incidencias/servicios/{id}/informe` aparecían errores:
    - "Debe describir lo realizado"
    - "Debe ingresar nombre y apellido"
- Aun cuando el usuario sí había diligenciado diagnóstico y nombre/apellido.

**Causa raíz:**
- El endpoint `storeAttendance()` esperaba campos de un formulario (`descripcion_atencion`, `persona_receptora_completa`).
- La vista de informe técnico enviaba otra estructura (`diagnostico_validacion`, `persona_receptora_nombre`, `persona_receptora_apellido`).
- Resultado: validación contra nombres de campo no enviados.

**Solución implementada:**
- `storeAttendance()` ahora acepta ambos formatos de formulario:
    - descripción desde `descripcion_atencion` o `diagnostico_validacion`.
    - receptor desde `persona_receptora_completa` o `nombre + apellido`.
- Se conserva la validación de mínimos y se mantienen mensajes de error funcionales.

**Regla preventiva:**
- Si múltiples vistas comparten un mismo endpoint, definir y mantener un contrato de campos único o agregar capa de compatibilidad explícita en el controlador.
- Antes de publicar cambios de formularios, validar nombres `name="..."` vs reglas `validate([...])` campo por campo.

### ERROR #2: Dashboard Técnico - Undefined array key `servicios_completados` (27 Mayo 2026)

**Estado:** ✅ RESUELTO

**Síntoma:**
- Error 500 en perfil técnico al abrir dashboard.
- Mensaje: `Undefined array key "servicios_completados"`.
- Archivo: `resources/views/home/tecnico-dashboard.blade.php`.

**Causa raíz:**
- Desalineación entre vista y controlador:
    - La vista esperaba `$dashboard['servicios_completados']`.
    - `HomeController::dashboardTecnico()` no definía esa clave.

**Solución implementada:**
- Se agregó en `HomeController::dashboardTecnico()`:
    - `servicios_completados = servicios_resueltos + servicios_cerrados`.
- Se mantuvo compatibilidad con la vista existente sin romper métricas previas.

**Regla preventiva (obligatoria):**
- Cuando una vista consume un array tipo `dashboard`, toda clave usada en Blade debe definirse explícitamente en el controlador, incluso en escenarios sin datos.
- Antes de cerrar un cambio de dashboard, verificar contrato vista-controlador (keys esperadas vs keys retornadas).

**REGRESIÓN detectada (13 Junio 2026):**
- Este mismo error volvió a ocurrir porque la corrección previa se perdió (posible revert o migración parcial).
- **Lección:** Cuando un error se repite, verificar en git si la corrección fue eliminada antes de asumir que es un bug nuevo.
- Solución replicada del registro existente: `servicios_completados = whereIn(['RESUELTO', 'CERRADO'])`.

### ACTUALIZACIÓN: Equipos - Mantenimientos, Calibraciones y Documentos (27 Mayo 2026)

**Estado:** 🔄 CÓDIGO IMPLEMENTADO | VALIDACIÓN UI PARCIAL | REQUIERE NORMALIZAR MIGRACIONES

**Solicitud atendida:**
- Mostrar en crear/editar equipo los campos de mantenimiento y calibración.
- Crear gestión documental por equipo para PDF, MP4 y JPG clasificados.
- Crear programación y registro de mantenimientos/calibraciones con soporte de PDF externo.

**Cambios implementados:**
- Formulario de equipos actualizado con:
    - `mantenimientos_anuales`
    - `calibraciones_anuales`
    - `fecha_ultimo_mantenimiento`
    - `fecha_ultima_calibracion`
    - `proxima_fecha_mantenimiento`
    - `proxima_fecha_calibracion`
- `EquipoController` actualizado en `store()` y `update()` para validar y guardar esos campos.
- Nuevas vistas para:
    - Documentos de equipo
    - Carga de documentos
    - Listado de mantenimientos/calibraciones
    - Programación de mantenimiento/calibración
    - Registro de realización con PDF opcional
- Nuevas rutas agregadas en `routes/parametros.php` para documentos y mantenimientos por equipo.
- Vista detalle de equipo actualizada con accesos rápidos a Documentos y Mantenimientos.

**Validación realizada:**
- Login web exitoso en `http://localhost:8000`.
- Validada visualmente la sección `Mantenimiento & Calibración` en la creación de equipos.
- Cachés Laravel limpiados correctamente.

**Incidencia detectada:**
- La base de datos local ya tenía columnas y tablas creadas previamente.
- Existen migraciones antiguas o duplicadas que vuelven a intentar crear estructuras ya existentes, por ejemplo:
    - `mantenimientos_por_ano`
    - tabla `marcas`
    - tabla `equipo_documentos`
- Resultado: `php artisan migrate` no es confiable en este estado sin primero alinear historial de la tabla `migrations` con la estructura real de la BD.

**Causa raíz probable:**
- Ejecuciones manuales previas y/o migraciones duplicadas con nombres nuevos para columnas ya creadas.
- Historial de migraciones desfasado respecto al esquema real local.

**Pendiente inmediato al retomar:**
1. Revisar tabla `migrations` y dejarla consistente con la BD actual.
2. Verificar existencia real de `mantenimiento_calibraciones` y sus columnas.
3. Probar flujo completo: crear equipo, cargar documento, programar mantenimiento, registrar realización.
4. Generar documento final de despliegue/FTP para esta funcionalidad.

**Nuevo hallazgo de implementación (27 Mayo 2026):**
- En la vista `parametros.equipos.edit`, los selects dependientes necesitan precarga completa del contexto padre.
- Solución aplicada:
    - `edit()` ahora carga `area.sede.cliente.empresa` y `contrato.cliente`.
    - Los selects de empresa, cliente, sede, área y contrato usan `old()` con null-safe (`?->`) para marcar el valor correcto.
- Regla para no repetir el error:
    - En vistas con filtros dependientes, siempre verificar que los valores preseleccionados se vean antes de activar el filtrado JS.
    - No asumir que `selected` basta si el select padre no está precargado.

**Nuevo hallazgo de implementación (28 Mayo 2026): Logo de empresa no visible en PDF técnico**
- Síntoma:
    - En el informe técnico PDF no se renderizaba el logo definido en el registro de la empresa.
- Causa raíz:
    - La generación del PDF dependía de una sola ruta de relación (`equipo -> sede -> cliente -> empresa`) y de una ruta de archivo directa que no cubría todos los formatos guardados (`empresas/...`, `storage/...`, ruta absoluta, etc.).
- Solución aplicada:
    - `ServicioController` ahora resuelve el logo desde ambas fuentes posibles:
        - `equipo.area.sede.cliente.empresa.logo`
        - `contrato.cliente.empresa.logo`
    - Se normaliza y valida la ruta física del archivo.
    - Se convierte el logo a base64 (`data:image/...`) para render estable en DomPDF.
    - En la vista PDF se prioriza `empresaLogoBase64` y se deja fallback a ruta local.
- Archivos modificados:
    - `app/Http/Controllers/Incidencias/ServicioController.php`
    - `resources/views/incidencias/servicios/pdf/informe-tecnico-new.blade.php`
- Verificación:
    - Sin errores de sintaxis en archivos modificados.
    - Caché de vistas y aplicación limpiada.

**Nuevo hallazgo de implementación (28 Mayo 2026): Firma del receptor no visible en informe PDF**
- Síntoma:
    - En el informe técnico no aparecía la firma capturada de la persona que recibe.
- Causa raíz:
    - La firma se guardaba según flujo en rutas de discos distintos (`private` y `public`), pero el PDF la intentaba leer desde una sola ruta fija (`storage/app/...`).
- Solución aplicada:
    - Se centralizó la resolución de firma en `resolverFirmaBase64()` en `ServicioController`.
    - La nueva lógica soporta:
        - Firma ya en data URI (`data:image/...`)
        - Archivos en disco `private`
        - Archivos en disco `public`
        - Rutas legadas físicas en `storage`/`public`
    - `downloadInformePDF()` y `viewInformePDF()` ahora consumen esa resolución unificada.
- Archivo modificado:
    - `app/Http/Controllers/Incidencias/ServicioController.php`
- Verificación:
    - Sin errores de sintaxis en controlador.
    - Caché de vistas y aplicación limpiada.

**Nuevo hallazgo de implementación (28 Mayo 2026): Notificación por WhatsApp al asignar servicio**
- Solicitud:
    - Enviar mensaje por WhatsApp al técnico al momento de asignar el servicio.
- Solución aplicada:
    - En formulario de asignación se agregó opción `Enviar notificación por WhatsApp`.
    - Al guardar asignación, si la opción está activa:
        - Se construye mensaje con datos del servicio (id, cliente, equipo, fecha, enlace de detalle).
        - Se normaliza el número del técnico (solo dígitos) y se completa prefijo país por defecto (`WHATSAPP_DEFAULT_COUNTRY_CODE`, default `57`) cuando aplica.
        - Se redirige a `https://wa.me/...` con texto prellenado.
- Archivos modificados:
    - `app/Http/Controllers/Incidencias/ServicioController.php`
    - `resources/views/incidencias/servicios/assign.blade.php`

**Ajuste adicional (28 Mayo 2026): abrir WhatsApp en otra pestaña**
- Solicitud:
    - Al mandar WhatsApp desde asignación, abrir en una pestaña nueva.
- Solución aplicada:
    - En `storeAssign`, cuando `enviar_whatsapp` está activo, se redirige al detalle del servicio con `whatsapp_url` en sesión.
    - En `incidencias/servicios/show`, se ejecuta `window.open(..., '_blank')` al cargar la página para abrir WhatsApp en nueva pestaña.
- Archivos modificados:
    - `app/Http/Controllers/Incidencias/ServicioController.php`
    - `resources/views/incidencias/servicios/show.blade.php`

**Ajuste adicional (28 Mayo 2026): datos de ubicación/contacto y enlace en mensaje WhatsApp**
- Solicitud:
    - Incluir sede, dirección y teléfono de contacto en el mensaje al técnico.
    - Mejorar activación del link enviado por WhatsApp.
- Solución aplicada:
    - Se agregó al mensaje: `Sede`, `Direccion`, `Telefono contacto`.
    - El enlace del servicio ahora se envía en una línea independiente para que WhatsApp lo detecte mejor como URL clickeable.
- Archivo modificado:
    - `app/Http/Controllers/Incidencias/ServicioController.php`

**Ajuste adicional (28 Mayo 2026): nombre del contacto en mensaje WhatsApp**
- Solicitud:
    - Incluir nombre del contacto en la sede dentro del mensaje.
- Solución aplicada:
    - Se añadió `Nombre contacto` al mensaje de asignación WhatsApp con fallback:
        - `servicios.solicitado_por`
        - `sede->cliente->contacto_nombre`
        - `N/A`
- Archivo modificado:
    - `app/Http/Controllers/Incidencias/ServicioController.php`

**Ajuste adicional (28 Mayo 2026): micrófono en móvil para dictado de informe**
- Síntoma:
    - Desde equipo móvil no se activaba el dictado por voz en el informe técnico.
- Causas frecuentes detectadas:
    - Navegador/dispositivo sin soporte de Web Speech API.
    - Contexto no seguro (HTTP sobre IP local sin HTTPS).
    - Permisos de micrófono denegados o ausencia de conexión.
- Solución aplicada:
    - Validación explícita de contexto seguro y soporte de API antes de iniciar dictado.
    - Mensajes de estado detallados por tipo de error (`not-allowed`, `audio-capture`, `network`, etc.).
    - Ajuste de `recognition.continuous` para mejorar compatibilidad en móvil.
    - Mensaje de guía cuando se usa IP local sin HTTPS.
- Archivo modificado:
    - `resources/views/incidencias/servicios/report-technician-v2.blade.php`

**Ajuste adicional (28 Mayo 2026): link de WhatsApp usando APP_URL pública**
- Solicitud:
    - Que el enlace enviado por WhatsApp no use localhost para que abra en móvil.
- Solución aplicada:
    - El enlace del detalle del servicio ahora se construye con la base de `config('app.url')` y ruta relativa.
    - Si `APP_URL` está configurada con HTTPS público (ngrok/dominio), el enlace del mensaje queda accesible para el técnico.
- Archivo modificado:
    - `app/Http/Controllers/Incidencias/ServicioController.php`

**Ajuste adicional (28 Mayo 2026): separar URL pública de WhatsApp del APP_URL general**
- Síntoma:
    - Mezcla de navegación local y HTTPS público podía causar intentos inseguros de carga (`https://IP_LOCAL:8000`).
- Solución aplicada:
    - Se creó `WHATSAPP_PUBLIC_URL` en `.env` para construir enlaces de WhatsApp sin depender estrictamente de `APP_URL`.
    - El mensaje de WhatsApp ahora prioriza `WHATSAPP_PUBLIC_URL` y usa `APP_URL` solo como fallback.
- Archivos modificados:
    - `app/Http/Controllers/Incidencias/ServicioController.php`
    - `.env`

---

### ERROR #7: RelationNotFoundException - Undefined relationship [marca] on model [Equipo] (13 Junio 2026)

**Estado:** ✅ RESUELTO

**Síntoma:**
- Error 500 al acceder a `/incidencias/servicios/{id}/informe`.
- Mensaje: `Call to undefined relationship [marca] on model [App\Models\Equipo]`.
- Archivo: `vendor\laravel\framework\src\Illuminate\Database\Eloquent\RelationNotFoundException.php:35`

**Causa raíz:**
- La BD tiene columna `marca_id` como FK (migración `2026_05_25_000002`), pero el modelo `Equipo` nunca se actualizó:
    - Faltaba método `marca()` (belongsTo Marca).
    - Faltaba `marca_id` y `codigo_activo_cliente` en `$fillable`.
    - `ServicioController` usaba `Equipo::with('marca')` y `$e->marca?->nombre` tratándola como relación.

**Archivos corregidos:**
- `app/Models/Equipo.php`
    - Agregado `marca_id` y `codigo_activo_cliente` a `$fillable`.
    - Agregado método `marca()` → `belongsTo(Marca::class)`.
- `app/Http/Controllers/Incidencias/ServicioController.php`
    - `getEquiposByCliente()`: removido `marca_id` del `get()` (ya no es necesario con la relación).
    - `getEquiposByArea()`: idem.
    - `crearEquipo()`: ahora busca Marca por nombre y guarda `marca_id` + `marca` string.

**Regla preventiva (obligatoria):**
- Cuando una migración agrega una FK (ej. `marca_id`), actualizar TODOS los:modelo (agregar relación + $fillable), controladores y vistas en el mismo cambio.
- No asumir que un campo string (ej. `marca`) seguirá siendo string si existe una migración de normalización.

---

### ERROR #8: Column not found - diagnostico_validacion no existe en servicios (13 Junio 2026)

**Estado:** ✅ RESUELTO

**Síntoma:**
- Error 500 al guardar informe técnico (POST `/incidencias/servicios/{id}/informe`).
- Mensaje: `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'diagnostico_validacion' in 'field list'`.
- Archivo: `app/Http/Controllers/Incidencias/ServicioController.php:905`

**Causa raíz:**
- La BD tiene columna `diagnostico`, NO `diagnostico_validacion`.
- El código enviaba ambos campos al `update()`:
    - `diagnostico_validacion` → falla (columna no existe)
    - `diagnostico` → funciona
- ERROR #3 (27 Mayo) documentó este mismo patrón pero la corrección fue parcial.

**Archivos corregidos:**
- `app/Http/Controllers/Incidencias/ServicioController.php`
    - `storeAttendance()` línea 864: eliminada clave `diagnostico_validacion` del `$updateData` (redundante con `diagnostico`).
    - `storeReport()` línea 1262: cambiado `diagnostico_validacion` → `diagnostico` en el `$updateData`.

**Regla preventiva (obligatoria):**
- El nombre de campo en `validate()` puede ser diferente al columna real en BD (ej. `diagnostico_validacion` → `diagnostico`).
- SIEMPRE verificar columnas reales con `Schema::getColumnListing('tabla')` antes de hacer `update()` o `create()`.
- Si la vista envía un campo con nombre distinto a la columna, mapear explícitamente en el controlador.

---

### ERROR #9: Select de Marca en create equipo era un input text (13 Junio 2026)

**Estado:** ✅ RESUELTO

**Síntoma:**
- En `/parametros/equipos/create`, el campo Marca era un `<input type="text">` en lugar de un `<select>` con datos de la tabla `marcas`.
- La vista `edit.blade.php` (vía `form.blade.php`) ya tenía el select correcto.

**Causa raíz:**
- `create.blade.php` no fue actualizado cuando se implementó el módulo de marcas.
- El controlador `EquipoController::create()` no pasaba `$marcas` a la vista.

**Archivos corregidos:**
- `app/Http/Controllers/Parametros/EquipoController.php`
    - `create()`: agregada consulta `$marcas` y se pasa a la vista.
    - `edit()`: agregada consulta `$marcas` (ya existía pero faltaba).
    - `store()`: cambiado validación `marca` string → `marca_id` exists:marcas,id + se resuelve nombre.
    - `update()`: idem.
- `resources/views/parametros/equipos/create.blade.php`
    - Campo Marca cambiado de `<input type="text">` a `<select name="marca_id">`.

**Regla preventiva (obligatoria):**
- Cuando se crea un CRUD que usa una tabla de catálogo (ej. marcas), verificar que AMBAS vistas (create y edit) usen select.
- Si `form.blade.php` es compartido, verificar que create.blade.php lo incluya o tenga la misma estructura.

---

### ERROR #10: Validación Pedía "codigo_interno" pero el formulario envía "codigo_activo_cliente" (13 Junio 2026)

**Estado:** ✅ RESUELTO

**Síntoma:**
- Error de validación al editar equipo: "The codigo interno field is required."
- El formulario muestra campo "Código Activo Cliente" con datos, pero la validación lo rechaza.

**Causa raíz:**
- Mismatch entre nombre de campo en formulario y regla de validación:
    - Formulario (`form.blade.php`): envía `codigo_activo_cliente`
    - Controlador (`store/update`): validaba `codigo_interno` como required
- La columna real en BD es `codigo_activo_cliente` (renombrada en migración `2026_05_25_000002`).

**Archivos corregidos:**
- `app/Http/Controllers/Parametros/EquipoController.php`
    - `store()`: eliminada validación `codigo_interno`, cambiado `codigo_activo_cliente` de nullable a required.
    - `update()`: idem.

**Regla preventiva (obligatoria):**
- Cuando una migración renombra una columna, actualizar TODAS las referencias: validación, formularios, queries.
- El nombre del campo en `validate()` debe coincidir exactamente con el `name="..."` del formulario HTML.
- Usar `Schema::getColumnListing('tabla')` para verificar nombres reales antes de escribir validaciones.

---

### ERROR #11: Create y Edit de equipos tenían formularios distintos y desorganizados (13 Junio 2026)

**Estado:** ✅ RESUELTO

**Síntoma:**
- Create faltaba campo `cliente_id` (name), usaba `codigo_interno` en vez de `codigo_activo_cliente`.
- Create no tenía sección Mantenimiento & Calibración.
- Create tenía código duplicado y desorganizado vs form.blade.php.

**Causa raíz:**
- `create.blade.php` tenía su propio formulario completo duplicado (~387 líneas) en vez de incluir `form.blade.php`.
- `edit.blade.php` correctamente usaba `@include('parametros.equipos.form')`.
- Resultado: dos formularios desalineados.

**Solución implementada:**
- `create.blade.php` reescrito para usar `@include('parametros.equipos.form')` igual que `edit.blade.php`.
- Ambos formularios ahora son idénticos (mismos campos, misma validación, mismo JS).

**Regla preventiva (obligatoria):**
- Siempre usar un form.blade.php compartido para create/edit del mismo modelo.
- NUNCA duplicar formularios completos en create y edit separados.
- Al agregar un campo, verificar que form.blade.php lo incluya para ambos flujos.

---

### ERROR #12: Column not found 'marca' al actualizar equipo (13 Junio 2026)

**Estado:** ✅ RESUELTO

**Síntoma:**
- Error 500 al actualizar equipo: `Unknown column 'marca' in 'field list'`.
- SQL mostraba: `set marca_id = 1, ..., marca = LENOVO` — la columna `marca` no existe.

**Causa raíz:**
- `EquipoController::store/update()` hacía `$validated['marca'] = $marca->nombre` para guardar el string.
- Pero la BD solo tiene columna `marca_id` (FK), no `marca`.
- El campo `marca` estaba en `$fillable` del modelo pero la columna no existe en la tabla.

**Archivos corregidos:**
- `app/Http/Controllers/Parametros/EquipoController.php`
    - `store()`: eliminada línea `$validated['marca'] = $marca->nombre`.
    - `update()`: idem.
- `app/Models/Equipo.php`
    - Eliminado `'marca'` de `$fillable` (columna no existe en BD).

**Regla preventiva (obligatoria):**
- Antes de agregar un campo a `$fillable`, verificar que la columna exista en la BD con `Schema::getColumnListing('tabla')`.
- No asumir que un campo en `$fillable` = columna existente en la tabla.

---

### ERROR #13: Views mostraban objeto Marca en vez de nombre al listar equipos (13 Junio 2026)

**Estado:** ✅ RESUELTO

**Síntoma:**
- En índice de equipos, columna "Marca / Modelo" mostraba JSON en vez del nombre de la marca.
- Causa: `$equipo->marca` ahora retorna objeto Marca (relación), no string.

**Archivos corregidos:**
- `resources/views/parametros/equipos/index.blade.php` → `$equipo->marca?->nombre`
- `resources/views/parametros/equipos/pdf.blade.php` → `$equipo->marca?->nombre`
- `resources/views/parametros/equipos/show.blade.php` → `$equipo->marca?->nombre` (2 lugares)
- `resources/views/incidencias/servicios/attend.blade.php` → `$equipo->marca?->nombre`
- `app/Http/Controllers/Parametros/EquipoController.php` → agregado `marca` a TODOS los `with()`
- `app/Http/Controllers/Parametros/MantenimientoController.php` → idem
- `app/Http/Controllers/Parametros/EquipoDocumentoController.php` → idem
- `app/Http/Controllers/Parametros/MantenimientoCalibrationController.php` → idem

**Regla preventiva (obligatoria) — NUEVO PROTOCOLO DE VERIFICACIÓN:**
CUANDO SE CAMBIA UNA RELACIÓN O CAMPO EN UN MODELO:
1. Buscar con `grep` TODAS las referencias al campo/relación en TODO el proyecto
2. Verificar CADA vista que lo muestra
3. Verificar CADA controlador que lo eager-loads
4. Verificar PDFs, exportes, AJAX
5. Usar el checklist de verificación de la sección 1

---

*Este documento es el estándar para todos los cambios en CEOGestion*
