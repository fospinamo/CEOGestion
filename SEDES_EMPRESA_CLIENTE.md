# Restructuración: Sedes con Propietario Empresa O Cliente

## Resumen Ejecutivo

Se ha completado la **reestructuración del sistema de Sedes** para permitir que pertenezcan a:

1. **Empresa (CEOGestion)**: Sedes propias, oficinas, data centers
2. **Cliente**: Ubicaciones/sucursales del cliente donde se instalan equipos

**Validación crítica**: Una sede pertenece a **UNA Y SOLO UNA** entidad (empresa O cliente, nunca ambas).

```
ESTRUCTURA:
Empresa (CEOGestion)
  ├─ Clientes (6)
  │   └─ Sedes de Cliente (8) ← ubicaciones del cliente
  └─ Sedes de Empresa (0+) ← oficinas propias de CEOGestion

Cada Sede:
  ├─ Áreas
  │   └─ Equipos (con serial como llave única)
  └─ Servicios (mantenimiento)
```

---

## Base de Datos

### Tabla: sedes

```sql
CREATE TABLE sedes (
  id BIGINT PRIMARY KEY,
  empresa_id BIGINT NULLABLE -- NULL si es sede de cliente
  cliente_id BIGINT NULLABLE -- NULL si es sede de empresa
  nombre VARCHAR(255) NOT NULL,
  codigo VARCHAR(20) UNIQUE NOT NULL,
  direccion TEXT,
  municipio_id BIGINT NOT NULL,
  barrio_id BIGINT NULLABLE,
  codigo_postal VARCHAR(10),
  telefono VARCHAR(20),
  email VARCHAR(255),
  estado BOOLEAN DEFAULT true,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

-- VALIDACIÓN: (empresa_id IS NOT NULL AND cliente_id IS NULL) 
--          OR (empresa_id IS NULL AND cliente_id IS NOT NULL)
```

### Migración

**Archivo**: `database/migrations/2026_04_23_000026_restructure_sedes_empresa_cliente.php`

- Restaura `empresa_id` como nullable (fue eliminado en migración anterior)
- Mantiene `cliente_id` como nullable
- Aplica validación en modelo (no en BD para mayor flexibilidad)

---

## Modelo Sede

### Archivo: `app/Models/Sede.php`

#### Propiedades

```php
protected $fillable = [
    'empresa_id',    // Foreign key a empresas (nullable)
    'cliente_id',    // Foreign key a clientes (nullable)
    'nombre',
    'codigo',        // Código único de la sede
    'direccion',
    'municipio_id',  // Ubicación DANE
    'barrio_id',
    'codigo_postal',
    'telefono',
    'email',
    'estado'         // Boolean
];
```

#### Relaciones

```php
// Pertenece a Empresa
public function empresa(): BelongsTo {
    return $this->belongsTo(Empresa::class);
}

// Pertenece a Cliente
public function cliente(): BelongsTo {
    return $this->belongsTo(Cliente::class);
}

// Pertenece a Municipio
public function municipio(): BelongsTo {
    return $this->belongsTo(Municipio::class);
}

// Tiene Áreas
public function areas(): HasMany {
    return $this->hasMany(Area::class);
}

// Tiene Usuarios
public function usuarios(): HasMany {
    return $this->hasMany(User::class);
}
```

#### Métodos de Validación

```php
/**
 * Verifica si es sede de empresa
 */
public function esDeEmpresa(): bool {
    return !is_null($this->empresa_id) && is_null($this->cliente_id);
}

/**
 * Verifica si es sede de cliente
 */
public function esDeCliente(): bool {
    return !is_null($this->cliente_id) && is_null($this->empresa_id);
}

/**
 * Obtiene el propietario (empresa o cliente)
 */
public function propietario() {
    return $this->empresa ?? $this->cliente;
}

/**
 * Valida que pertenezca a UNA entidad
 */
public function validarPropietario(): bool {
    $tieneEmpresa = !is_null($this->empresa_id);
    $tieneCliente = !is_null($this->cliente_id);

    // Ambas null: error
    if (!$tieneEmpresa && !$tieneCliente) {
        throw new \Exception('La sede debe pertenecer a empresa O cliente');
    }

    // Ambas seteadas: error
    if ($tieneEmpresa && $tieneCliente) {
        throw new \Exception('La sede NO puede pertenecer a ambas');
    }

    return true;
}
```

#### Scopes

```php
/**
 * Sedes de empresa
 * Uso: Sede::deEmpresa()->get()
 * Uso: Sede::deEmpresa($empresaId)->get()
 */
public function scopeDeEmpresa($query, $empresaId = null) {
    $query->whereNotNull('empresa_id')->whereNull('cliente_id');
    if ($empresaId) $query->where('empresa_id', $empresaId);
    return $query;
}

/**
 * Sedes de cliente
 * Uso: Sede::deCliente()->get()
 * Uso: Sede::deCliente($clienteId)->get()
 */
public function scopeDeCliente($query, $clienteId = null) {
    $query->whereNotNull('cliente_id')->whereNull('empresa_id');
    if ($clienteId) $query->where('cliente_id', $clienteId);
    return $query;
}

/**
 * Sedes activas
 */
public function scopeActivas($query) {
    return $query->where('estado', true);
}
```

---

## Controlador

### Archivo: `app/Http/Controllers/SedeController.php`

#### index()

```php
public function index() {
    $sedes = Sede::with(['empresa', 'cliente', 'municipio', 'barrio'])->get();
    return view('sedes.index', compact('sedes'));
}
```

#### create()

```php
public function create() {
    $sede = null;
    $empresas = Empresa::where('estado', true)->get();
    $clientes = Cliente::where('estado', true)->get();
    $municipios = Municipio::with('departamento')->get();
    $barrios = Barrio::orderBy('nombre')->get();
    return view('sedes.create', compact('sede', 'empresas', 'clientes', 'municipios', 'barrios'));
}
```

#### store()

```php
public function store(Request $request) {
    // Validación
    $request->validate([
        'empresa_id' => 'nullable|exists:empresas,id',
        'cliente_id' => 'nullable|exists:clientes,id',
        'nombre' => 'required|string|max:255',
        'codigo' => 'required|string|unique:sedes|max:20',
        'municipio_id' => 'required|exists:municipios,id',
        // ... otros campos ...
    ]);

    // Validar que UNA de las dos sea NULL
    if (is_null($request->empresa_id) && is_null($request->cliente_id)) {
        return back()->withErrors(['propietario' => 'Debe pertenecer a empresa O cliente']);
    }

    if (!is_null($request->empresa_id) && !is_null($request->cliente_id)) {
        return back()->withErrors(['propietario' => 'NO puede pertenecer a ambas']);
    }

    Sede::create($request->except(['_token', '_method']));
    return redirect()->route('sedes.index')->with('success', 'Sede creada');
}
```

#### update()

Idéntica a store() pero con validación de código único excluyendo el registro actual.

---

## Vistas

### index.blade.php

Muestra tabla con columnas:
- Nombre
- Código
- **Propietario** (empresa en azul / cliente en verde)
- **Tipo** (Empresa / Cliente, con badge de color)
- Municipio
- Estado
- Acciones

```blade
@if($sede->esDeEmpresa())
    <span class="font-semibold text-blue-600">
        <i class="fas fa-building"></i> {{ $sede->empresa->nombre }}
    </span>
    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Empresa</span>
@else
    <span class="font-semibold text-green-600">
        <i class="fas fa-user"></i> {{ $sede->cliente->razon_social }}
    </span>
    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Cliente</span>
@endif
```

### show.blade.php

Muestra información detallada con indicador claro del propietario:

```blade
<p class="text-sm text-gray-600">Propietario</p>
@if($sede->esDeEmpresa())
    <p class="text-lg font-semibold text-blue-600">
        <i class="fas fa-building"></i> {{ $sede->empresa->nombre }}
    </p>
    <p class="text-xs text-gray-500">Sede de la empresa</p>
@else
    <p class="text-lg font-semibold text-green-600">
        <i class="fas fa-user"></i> {{ $sede->cliente->razon_social }}
    </p>
    <p class="text-xs text-gray-500">Sede del cliente</p>
@endif
```

### create.blade.php / edit.blade.php

**Sección con recuadro azul informativo:**

```blade
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <p class="text-sm text-blue-700 font-semibold mb-4">
        <i class="fas fa-info-circle"></i> 
        Selecciona el propietario: empresa O cliente (pero no ambos)
    </p>
    
    <div class="grid grid-cols-2 gap-6">
        <div>
            <label>Empresa</label>
            <select name="empresa_id">
                <option value="">Selecciona empresa (deja vacío si es de cliente)</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id }}" ...>
                        {{ $empresa->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Cliente</label>
            <select name="cliente_id">
                <option value="">Selecciona cliente (deja vacío si es de empresa)</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" ...>
                        {{ $cliente->razon_social }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    @error('propietario')
        <p class="text-red-600 text-sm mt-3">
            <i class="fas fa-exclamation-circle"></i> {{ $message }}
        </p>
    @enderror
</div>
```

---

## Flujo de Uso

### Caso 1: Crear Sede de Empresa (CEOGestion)

1. Usuario entra a `sedes.create`
2. Selecciona: **Empresa = "CEOGestion"**, Cliente = vacío
3. Completa nombre, código, ubicación
4. Guarda
5. Resultado: `empresa_id = 1, cliente_id = NULL`
6. Vista muestra: "CEOGestion" (azul) con badge "Empresa"

### Caso 2: Crear Sede de Cliente

1. Usuario entra a `sedes.create`
2. Selecciona: Empresa = vacío, **Cliente = "Acme Corp"**
3. Completa nombre, código, ubicación
4. Guarda
5. Resultado: `empresa_id = NULL, cliente_id = 2`
6. Vista muestra: "Acme Corp" (verde) con badge "Cliente"

### Caso 3: Error de Validación

- Si ambas están vacías: Error "La sede debe pertenecer a empresa O cliente"
- Si ambas están seteadas: Error "La sede NO puede pertenecer a ambas"

---

## Validación de Datos

### Tests en Tinker

```php
// Verificar counts
$sedesEmpresa = Sede::deEmpresa()->count();        // 0 (o el count correcto)
$sedesCliente = Sede::deCliente()->count();         // 8 (o el count correcto)

// Verificar métodos
$sede = Sede::deCliente()->first();
$sede->esDeCliente();                               // true
$sede->esDeEmpresa();                               // false
$sede->propietario()->razon_social;                 // Nombre del cliente

// Verificar by ID
Sede::deCliente(2)->count();                        // Sedes del cliente 2
Sede::deEmpresa(1)->count();                        // Sedes de empresa 1
```

### Output Esperado

```
Sedes de empresa: 0
Sedes de cliente: 8

Sede: Sede Barranquilla
EsDeCliente: SÍ
Cliente: Jacobs, Kihn and Kuhic
```

---

## Equipos y Serial

### Tabla equipos

**Campo crítico**: `serial` (VARCHAR UNIQUE)

```php
// Crear equipo con serial único
$equipo = Equipo::create([
    'sede_id' => $sede->id,
    'serial' => 'SERIAL-12345',      // ← Llave única por equipo
    'codigo_interno' => 'EQUIPO-001',
    'marca' => 'Dell',
    'modelo' => 'Optiplex 7090',
    'cliente_id' => $sede->cliente_id,  // Heredado de sede
    'area_id' => $area->id,
    'estado_operativo' => 'OPERATIVO'
]);

// Verificar por serial
$equipo = Equipo::where('serial', 'SERIAL-12345')->first();
```

**Validación**: Serial único a nivel de BD + validación en modelo.

---

## Relaciones Heredadas

### Equipo → Sede → Cliente/Empresa

```php
// Obtener cliente o empresa del equipo
$equipo = Equipo::with(['sede', 'sede.empresa', 'sede.cliente'])->first();

if ($equipo->sede->esDeCliente()) {
    echo "Cliente: " . $equipo->sede->cliente->razon_social;
} else {
    echo "Empresa: " . $equipo->sede->empresa->nombre;
}
```

---

## Archivos Modificados

✅ **Modelos (1)**:
- `app/Models/Sede.php` - Relaciones, validadores, scopes, métodos helper

✅ **Controladores (1)**:
- `app/Http/Controllers/SedeController.php` - Validación de propietario

✅ **Migraciones (1)**:
- `database/migrations/2026_04_23_000026_restructure_sedes_empresa_cliente.php`

✅ **Vistas (4)**:
- `resources/views/sedes/index.blade.php` - Tabla con propietario y tipo
- `resources/views/sedes/show.blade.php` - Detalles con indicador visual
- `resources/views/sedes/create.blade.php` - Formulario con ambas opciones
- `resources/views/sedes/edit.blade.php` - Formulario con ambas opciones

---

## Estado de Producción

✅ **Base de datos**: Migración aplicada exitosamente
✅ **Modelo**: Validaciones implementadas
✅ **Controlador**: Validación de propietario en store/update
✅ **Vistas**: Interfaz clara con indicadores visuales
✅ **Datos**: 8 sedes de cliente, 0 sedes de empresa (puede cambiar)
✅ **Tests**: Validados en Tinker

**Status**: 🟢 **LISTO PARA PRODUCCIÓN**
