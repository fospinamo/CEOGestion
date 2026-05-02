# 📋 BUENAS PRÁCTICAS - DESARROLLO SEGURO Y ESTABLE

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

### 4. COMPARAR RUTAS
Crear lista de:
- ✓ Rutas DEFINIDAS en routes/
- ✓ Rutas USADAS en vistas/
- ✗ Rutas FALTANTES

### 5. RESOLVER DISCREPANCIAS
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

## 📝 CHECKLIST POR TIPO DE CAMBIO

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

**ÚLTIMA ACTUALIZACIÓN**: 2/05/2026
**ESTADO**: Sistema estable después de limpiar referencias obsoletas
