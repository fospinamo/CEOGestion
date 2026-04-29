# 🔄 PLAN DE MIGRACIÓN A ESTRUCTURA MODULAR

## Resumen Ejecutivo

Se ha creado una nueva estructura modular para CEOGestión organizada en 3 módulos:
- **Administrativo:** Tablas básicas (País, Departamento, Municipio)
- **Parámetros:** Configuración general (Empresa, Sedes, Clientes, Áreas, Equipos)
- **Incidencias:** Servicios técnicos

---

## ✅ Estructura Creada (100%)

### Directorios de Controladores
- ✅ `app/Http/Controllers/Administrativo/`
- ✅ `app/Http/Controllers/Parametros/`
- ✅ `app/Http/Controllers/Incidencias/`

### Directorios de Vistas
- ✅ `resources/views/administrativo/`
- ✅ `resources/views/parametros/`
- ✅ `resources/views/incidencias/`

### Archivos de Rutas
- ✅ `routes/administrativo.php`
- ✅ `routes/parametros.php`
- ✅ `routes/incidencias.php`

---

## 📋 Pasos de Migración

### FASE 1: Mover Controladores Existentes (Paso a Paso)

#### 1.1 Módulo Administrativo

**Archivos a mover:**
```
app/Http/Controllers/DepartamentoController.php → app/Http/Controllers/Administrativo/DepartamentoController.php
app/Http/Controllers/MunicipioController.php → app/Http/Controllers/Administrativo/MunicipioController.php
```

**Archivos a crear:**
```
app/Http/Controllers/Administrativo/PaisController.php (nuevo)
```

**Pasos:**
1. Copiar `DepartamentoController.php` a `Administrativo/`
2. Actualizar namespace: `namespace App\Http\Controllers\Administrativo;`
3. Copiar `MunicipioController.php` a `Administrativo/`
4. Actualizar namespace
5. Crear `PaisController.php` nuevo (si no existe)

#### 1.2 Módulo Parámetros

**Archivos a mover:**
```
app/Http/Controllers/EmpresaController.php → app/Http/Controllers/Parametros/EmpresaController.php
app/Http/Controllers/SedeController.php → app/Http/Controllers/Parametros/SedeController.php
app/Http/Controllers/ClienteController.php → app/Http/Controllers/Parametros/ClienteController.php
app/Http/Controllers/AreaController.php → app/Http/Controllers/Parametros/AreaController.php
app/Http/Controllers/EquipoController.php → app/Http/Controllers/Parametros/EquipoController.php
app/Http/Controllers/TipoEquipoController.php → app/Http/Controllers/Parametros/TipoEquipoController.php
```

**Pasos:**
1. Copiar cada controlador al directorio `Parametros/`
2. Actualizar namespace en cada archivo:
   ```php
   namespace App\Http\Controllers\Parametros;
   ```
3. Actualizar imports si usan otros controladores

#### 1.3 Módulo Incidencias

**Archivos a mover:**
```
app/Http/Controllers/ServicioController.php → app/Http/Controllers/Incidencias/ServicioController.php
```

**Pasos:**
1. Copiar `ServicioController.php` a `Incidencias/`
2. Actualizar namespace: `namespace App\Http\Controllers\Incidencias;`

---

### FASE 2: Reorganizar Vistas

#### 2.1 Administrativo
```
resources/views/departamentos/ → resources/views/administrativo/departamentos/
resources/views/municipios/ → resources/views/administrativo/municipios/
```

#### 2.2 Parámetros
```
resources/views/empresas/ → resources/views/parametros/empresas/
resources/views/sedes/ → resources/views/parametros/sedes/
resources/views/clientes/ → resources/views/parametros/clientes/
resources/views/areas/ → resources/views/parametros/areas/
resources/views/equipos/ → resources/views/parametros/equipos/
resources/views/tipos-equipos/ → resources/views/parametros/tipos-equipos/
```

#### 2.3 Incidencias
```
resources/views/servicios/ → resources/views/incidencias/servicios/
```

---

### FASE 3: Actualizar Rutas en web.php

En `routes/web.php`, agregar al final (después de todas las rutas existentes):

```php
// =======================================
// IMPORTAR RUTAS MODULARES
// =======================================

// Administrativo
require __DIR__ . '/administrativo.php';

// Parámetros
require __DIR__ . '/parametros.php';

// Incidencias
require __DIR__ . '/incidencias.php';
```

---

### FASE 4: Actualizar Referencias en Vistas

En todas las vistas, reemplazar nombres de rutas:

**Antes (rutas antiguas):**
```blade
route('equipos.index')
route('clientes.show', $cliente)
route('servicios.create')
```

**Después (rutas modulares):**
```blade
route('parametros.equipos.index')
route('parametros.clientes.show', $cliente)
route('incidencias.servicios.create')
```

---

### FASE 5: Actualizar Seeders

Actualizar `database/seeders/` si hace referencias a controladores

---

### FASE 6: Testing

1. Probar acceso a cada módulo
2. Verificar CRUD completo en cada entidad
3. Verificar relaciones entre módulos
4. Probar generación de PDF
5. Verificar autenticación y permisos

---

## 🎯 Recomendación: Migración Gradual

**NO hacer todo de una vez.** Hacer la migración en fases:

### Semana 1: Administrativo
- Mover controladores
- Mover vistas
- Actualizar rutas
- Testing

### Semana 2: Parámetros
- Mover controladores (6 archivos)
- Mover vistas
- Actualizar rutas
- Testing

### Semana 3: Incidencias
- Mover controlador
- Mover vistas
- Actualizar rutas
- Testing completo

---

## ⚠️ Consideraciones Importantes

1. **Namespace en Importes:** Si hay importes cruzados entre módulos, actualizar:
   ```php
   // Viejo
   use App\Http\Controllers\EquipoController;
   
   // Nuevo
   use App\Http\Controllers\Parametros\EquipoController;
   ```

2. **API Endpoints:** Actualizar URLs en AJAX/Fetch:
   ```javascript
   // Viejo
   fetch('/api/equipos/' + id)
   
   // Nuevo
   fetch('/parametros/api/equipos/' + id)
   ```

3. **Rutas nombradas:** Cambiar todas las referencias:
   ```blade
   <!-- Viejo -->
   route('equipos.index')
   
   <!-- Nuevo -->
   route('parametros.equipos.index')
   ```

4. **Middleware:** Verificar que siga funcionando `auth` en todos los módulos

5. **Permisos:** Si hay sistema de permisos, actualizar para nuevas rutas

---

## 📝 Checklist de Migración

- [ ] FASE 1: Mover controladores Administrativo
- [ ] FASE 1: Mover controladores Parámetros
- [ ] FASE 1: Mover controladores Incidencias
- [ ] FASE 2: Reorganizar vistas
- [ ] FASE 3: Actualizar web.php
- [ ] FASE 4: Actualizar referencias en vistas
- [ ] FASE 5: Actualizar seeders
- [ ] FASE 6: Testing completo
- [ ] Commit a GitHub
- [ ] Actualizar documentación

---

## 🔗 Compatibilidad Temporal

**Opción:** Mantener ambas rutas durante transición:

```php
// Rutas antiguas (deprecar)
Route::resource('equipos', EquipoController::class);

// Rutas nuevas (modular)
require __DIR__ . '/parametros.php';
```

Esto permite transición gradual sin romper existentes.

---

## 📞 Soporte

Si encuentras errores durante migración:
1. Verifica namespaces
2. Revisa imports
3. Confirma rutas en web.php
4. Limpia cache: `php artisan cache:clear`
5. Recarga vistas: `php artisan view:clear`

