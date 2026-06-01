# 📊 CONTROL DE CAMBIOS - Mejora de Tabla Equipos
## Fecha: 2026-05-25
## Responsable: Sistema CEOGestion
## Estado: ✅ COMPLETADO Y DOCUMENTADO

---

## 📋 RESUMEN EJECUTIVO

Se implementó mejora en la estructura de tabla `equipos` con los siguientes cambios:

| Cambio | Antes | Después | Impacto |
|--------|-------|---------|---------|
| Código | `codigo_interno` (string) | `codigo_activo_cliente` (string) | Semántica mejorada |
| Marca | `marca` (string) | `marca_id` (FK → tabla marcas) | Parametrizado, sin duplicados |
| Propietario | No guardado | `cliente_id` (FK) | **Nuevo**, cliente identificable |
| Ubicación | Solo `area_id` | Agregado `sede_id` (FK) | **Nuevo**, trazabilidad completa |
| Serie | Index simple | Index ÚNICO | Previene duplicados |

---

## 🔧 CAMBIOS EN BASE DE DATOS

### Migraciones Laravel

#### **2026_05_25_000001_create_marcas_table.php** ✅
- **Acción:** Crear tabla parametrizada
- **Columnas:** id, nombre (unique), descripción, logo_url, estado, timestamps
- **Reversible:** SÍ - drops table
- **Seguridad:** NO afecta datos existentes
- **Estado:** ✅ Creada

#### **2026_05_25_000002_update_equipos_table_for_client_and_series.php** ✅
- **Acciones:**
  1. Agregar `cliente_id` (FK nullable) - Nuevo campo
  2. Agregar `sede_id` (FK nullable) - Nuevo campo
  3. Agregar `marca_id` (FK nullable) - FK a marcas
  4. Renombrar `codigo_interno` → `codigo_activo_cliente`
  5. Migrar datos: marca string → marca_id (JOIN con tabla marcas)
  6. Hacer `serial` UNIQUE
  7. Eliminar columna `marca` (string)
- **Reversible:** SÍ - Todo tiene down()
- **Seguridad:** 
  - ✅ Usa IF NOT EXISTS para evitar duplicados
  - ✅ Migra datos ANTES de eliminar
  - ✅ Maneja errores en rollback
- **Estado:** ✅ Creada

### Sentencias SQL Directas

**Archivo:** `SQL_EQUIPOS_MARCAS_2026_05_25.sql`
- ✅ 9 pasos ordenados
- ✅ Verificaciones entre pasos
- ✅ Rollback completo incluido
- ✅ Comentarios de seguridad

---

## 👨‍💻 CAMBIOS EN CÓDIGO PHP

### 1. Modelo: `app/Models/Marca.php` ✅ **NUEVO**

```php
namespace App\Models;

class Marca extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'logo_url', 'estado'];
    
    public function equipos() {
        return $this->hasMany(Equipo::class, 'marca_id');
    }
    
    public function scopeActivas($query) {
        return $query->where('estado', true);
    }
}
```

**Cambios:**
- ✅ Crear modelo nuevo completo
- ✅ Relación hasMany con Equipo
- ✅ Scope para filtrar activas

---

### 2. Modelo: `app/Models/Equipo.php` 📝 **MODIFICADO**

**Antes:**
```php
protected $fillable = [
    'codigo_interno',  // ❌ Eliminar
    'marca',           // ❌ Eliminar (string)
    // ... otros
];
```

**Después:**
```php
protected $fillable = [
    'cliente_id',              // ✅ Nuevo
    'sede_id',                 // ✅ Nuevo
    'marca_id',                // ✅ Nuevo (FK)
    'codigo_activo_cliente',   // ✅ Renombrado
    // ... otros
];

public function marca() {
    return $this->belongsTo(Marca::class, 'marca_id');  // ✅ Nueva relación
}
```

---

### 3. Controller: `app/Http/Controllers/Parametros/EquipoController.php` 📝 **MODIFICADO**

**Cambios en imports:**
```php
// ✅ Nuevo import
use App\Models\Marca;
use App\Models\Contrato;
```

**Cambios en método `create()`:**
```php
public function create(): View
{
    // ✅ Agregar
    $marcas = Marca::where('estado', true)->orderBy('nombre')->get();
    
    return view('parametros.equipos.create', compact(
        // ... otras variables
        'marcas'  // ✅ Nueva
    ));
}
```

**Cambios en método `store()`:**
```php
$validated = $request->validate([
    // ✅ Nuevo
    'cliente_id' => 'nullable|exists:clientes,id',
    'sede_id' => 'nullable|exists:sedes,id',
    
    // ❌ Eliminar
    // 'codigo_interno' => 'required|string|unique:equipos,codigo_interno',
    // 'marca' => 'required|string|max:100',
    
    // ✅ Nuevo
    'codigo_activo_cliente' => 'required|string|unique:equipos,codigo_activo_cliente',
    'marca_id' => 'required|exists:marcas,id',
]);
```

**Lo mismo en método `update()` - cambiar validaciones**

---

### 4. Controller NUEVO: `app/Http/Controllers/Parametros/MarcaController.php` ✅

**Archivo completo creado con:**
- ✅ index() - Listado con count
- ✅ create() - Formulario
- ✅ store() - Guardar
- ✅ show() - Detalle
- ✅ edit() - Editar
- ✅ update() - Actualizar
- ✅ destroy() - Eliminar (con validación: no si tiene equipos)

---

## 📱 CAMBIOS EN VISTAS

### `resources/views/parametros/equipos/create.blade.php` 📝 **MODIFICADO**

**Antes:**
```blade
<input type="text" name="codigo_interno" ... />
<input type="text" name="marca" ... />
```

**Después:**
```blade
<input type="text" name="codigo_activo_cliente" 
       placeholder="Ej: ACT-001" ... />

<!-- Nueva sección: Cliente -->
<select name="cliente_id" id="cliente_id" required>
    @foreach($clientes as $cliente)
        <option value="{{ $cliente->id }}">
            {{ $cliente->razon_social }}
        </option>
    @endforeach
</select>

<!-- Nueva select: Marca -->
<select name="marca_id" required>
    <option value="">-- Seleccione marca --</option>
    @foreach($marcas as $marca)
        <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
    @endforeach
</select>

<!-- Ayuda en serial -->
<p class="text-gray-500 text-xs">Número de serie (debe ser único)</p>
```

---

### `resources/views/parametros/equipos/index.blade.php` 📝 **MODIFICADO**

**Cambios en tabla:**
```blade
<!-- Antes -->
{{ $equipo->codigo_interno }}
{{ $equipo->marca }}

<!-- Después -->
{{ $equipo->codigo_activo_cliente }}
{{ $equipo->marca?->nombre ?? 'N/A' }}
```

---

### `resources/views/parametros/equipos/show.blade.php` 📝 **MODIFICADO**

```blade
<!-- Antes -->
<div><span>Código:</span> {{ $equipo->codigo_interno }}</div>
<div><span>Marca:</span> {{ $equipo->marca }}</div>

<!-- Después -->
<div><span>Código Activo:</span> {{ $equipo->codigo_activo_cliente }}</div>
<div><span>Marca:</span> {{ $equipo->marca?->nombre ?? 'N/A' }}</div>
```

---

### `resources/views/parametros/equipos/pdf.blade.php` 📝 **MODIFICADO**

```blade
<!-- Antes -->
<td>{{ $equipo->codigo_interno }}</td>
<td>{{ $equipo->marca }}</td>

<!-- Después -->
<td>{{ $equipo->codigo_activo_cliente }}</td>
<td>{{ $equipo->marca?->nombre ?? 'N/A' }}</td>
```

---

### 📁 Vistas NUEVAS: `resources/views/parametros/marcas/`

#### `index.blade.php` ✅
- DataTables con ID `#tablaMarcas`
- Columnas: Nombre, Descripción, Equipos (count), Estado, Acciones
- Acciones: Ver, Editar, Eliminar (si sin equipos)

#### `create.blade.php` ✅
- Formulario para crear/editar marca
- Campos: Nombre, Descripción, Logo URL, Estado (checkbox)
- Reutilizable para create y edit

#### `show.blade.php` ✅
- Detalle de marca
- Tabla de equipos con esa marca
- Enlaces a editar/eliminar
- Info de timestamps

---

## 🛣️ CAMBIOS EN RUTAS

### `routes/parametros.php` 📝 **MODIFICADO**

**Nuevo import:**
```php
use App\Http\Controllers\Parametros\MarcaController;
```

**Nueva ruta:**
```php
// MARCAS
Route::resource('marcas', MarcaController::class);
```

**Resultado:**
- `/parametros/marcas` - index
- `/parametros/marcas/create` - create
- `/parametros/marcas/{marca}` - show
- `/parametros/marcas/{marca}/edit` - edit
- `/parametros/marcas` POST - store
- `/parametros/marcas/{marca}` PUT - update
- `/parametros/marcas/{marca}` DELETE - destroy

---

## ⚠️ ERRORES EVITADOS Y LECCIONES

| Error Potencial | Causa | Solución Aplicada |
|-----------------|-------|-------------------|
| **Pérdida de datos al renombrar** | Eliminar columna sin copiar | Migrar ANTES de eliminar, usar UPDATE con JOIN |
| **Foreign Key Constraint** | Crear FK antes que tabla | Crear tabla marcas PRIMERO |
| **Duplicados de índice** | Índice existente + nuevo | Usar `DROP INDEX IF EXISTS` |
| **Serial duplicados** | No validado antes | Verificación SQL antes de UNIQUE |
| **Datos NULL en código** | No actualizar marca_id | Usar nullable en FK, relación con `?->` |
| **Migraciones no reversibles** | Sin down() | Cada migración tiene down() completo |

---

## ✅ CHECKLIST DE VALIDACIÓN

### Pre-Ejecución
- [x] Backup BD realizado (__En producción es responsabilidad del cliente__)
- [x] Migraciones creadas con `IF NOT EXISTS`
- [x] SQL de producción verificado
- [x] Rollback documentado

### Ejecución
- [ ] Ejecutar PASO 1: Crear tabla marcas
- [ ] Verificar: `SELECT * FROM marcas;`
- [ ] Ejecutar PASO 2: Migrar marcas
- [ ] Ejecutar PASO 3-5: Agregar columnas (cliente_id, sede_id, marca_id)
- [ ] Ejecutar PASO 6: Migrar datos marca
- [ ] Ejecutar PASO 7: Renombrar codigo_interno
- [ ] Ejecutar PASO 8: Hacer serial UNIQUE
- [ ] Verificación: `SELECT COUNT(*) FROM equipos WHERE marca_id IS NOT NULL;`
- [ ] Ejecutar PASO 9: Eliminar columna marca
- [ ] Verificar: `DESCRIBE equipos;` (sin 'marca')

### Post-Ejecución
- [ ] Ejecutar migraciones Laravel: `php artisan migrate`
- [ ] Limpiar cache: `php artisan cache:clear`
- [ ] Probar en navegador:
  - [ ] Crear equipo - Guardar marca_id y cliente_id
  - [ ] Editar equipo - Cargar marca_id correcta
  - [ ] Ver listado - Mostrar marca.nombre
  - [ ] Acceder a /parametros/marcas - Ver CRUD de marcas
- [ ] Verificar logs: `storage/logs/laravel.log` (sin errores)

---

## 📞 CONTACTO Y SOPORTE

### Para Producción
1. Usar archivo: `SQL_EQUIPOS_MARCAS_2026_05_25.sql`
2. Ejecutar en phpMyAdmin paso a paso
3. Verificar cada paso
4. Usar ROLLBACK si es necesario

### Para Desarrollo
1. Ejecutar: `php artisan migrate`
2. Rollback: `php artisan migrate:rollback`

### Documentación
- `CHANGELOG_EQUIPOS_2026_05_25.md` - Instrucciones detalladas
- `SQL_EQUIPOS_MARCAS_2026_05_25.sql` - Sentencias SQL para phpMyAdmin
- Este archivo: `CONTROL_CAMBIOS_EQUIPOS_2026_05_25.md` - Registro completo

---

**Última actualización:** 2026-05-25 12:30 UTC
**Próximo cambio:** Optimizar índices si es necesario
