# Mejoras de Migraciones Laravel - CEOGESTION
**Fecha:** 22 de Abril, 2026  
**Base de Datos:** MySQL  
**PHP:** 8.2+  

---

## 📋 Resumen Ejecutivo

Se han implementado todas las mejoras solicitadas para optimizar la estructura de la base de datos:

✅ **Tablas nuevas:** 4 tablas de ubicación DANE (paises, departamentos, municipios, barrios)  
✅ **Tablas modificadas:** 2 tablas actualizadas (empresas, sedes)  
✅ **Modelos:** 6 modelos con relaciones completas  
✅ **Seeders:** 4 seeders con datos iniciales de Colombia  
✅ **Migraciones:** Todas aplicadas y verificadas  

---

## 🏗️ Tablas Creadas

### 1. `paises` (Tabla Base)
```sql
- id: bigint (PK)
- codigo_dane: string (unique, indexed)
- nombre: string
- timestamps: created_at, updated_at
```
**Relaciones:** Muchos departamentos

---

### 2. `departamentos`
```sql
- id: bigint (PK)
- codigo_dane: string (unique, indexed)
- nombre: string
- pais_id: bigint (FK → paises, onDelete: restrict)
- timestamps: created_at, updated_at
```
**Relaciones:** Pertenece a país, Muchos municipios

---

### 3. `municipios`
```sql
- id: bigint (PK)
- codigo_dane: string (unique, indexed)
- nombre: string
- departamento_id: bigint (FK → departamentos, onDelete: restrict)
- timestamps: created_at, updated_at
```
**Relaciones:** Pertenece a departamento, Muchos barrios, Muchas sedes

---

### 4. `barrios` (Tabla Opcional)
```sql
- id: bigint (PK)
- nombre: string
- municipio_id: bigint (FK → municipios, onDelete: restrict)
- timestamps: created_at, updated_at
```
**Relaciones:** Pertenece a municipio, Muchas sedes

---

## 🔄 Tablas Modificadas

### Tabla `empresas` - CAMBIOS REALIZADOS

**❌ Campos Eliminados:**
- `ruc` (string, unique)

**✅ Campos Añadidos:**
- `nit` (string, unique, indexed)
- `digito_verificacion` (string, length: 1)
- `pagina_web` (string, nullable)
- `tipo_contribuyente` (string, default: 'persona_juridica')
- `responsabilidades_fiscales` (json, nullable)

**Estructura Final:**
```sql
- id: bigint
- nombre: string (unique)
- nit: string (unique, indexed)
- digito_verificacion: string(1)
- telefono: string (nullable)
- email: string (nullable)
- pagina_web: string (nullable)
- tipo_contribuyente: string (default: 'persona_juridica')
- responsabilidades_fiscales: json (nullable)
- direccion: text (nullable)
- ciudad: string (nullable)
- estado: boolean (default: true)
- timestamps: created_at, updated_at
```

---

### Tabla `sedes` - CAMBIOS REALIZADOS

**❌ Campos Eliminados:**
- `ciudad` (string)
- `direccion` (text) - se reemplazó

**✅ Campos Añadidos/Modificados:**
- `direccion` (text, nullable) - ahora es dirección detallada
- `municipio_id` (bigint, FK → municipios, onDelete: restrict)
- `barrio_id` (bigint, nullable, FK → barrios, onDelete: restrict)
- `codigo_postal` (string, nullable)

**Estructura Final:**
```sql
- id: bigint
- empresa_id: bigint (FK → empresas, onDelete: cascade)
- nombre: string
- codigo: string (unique)
- direccion: text (nullable)
- municipio_id: bigint (FK → municipios, onDelete: restrict)
- barrio_id: bigint (nullable, FK → barrios, onDelete: restrict)
- codigo_postal: string (nullable)
- telefono: string (nullable)
- email: string (nullable)
- estado: boolean (default: true)
- timestamps: created_at, updated_at
```

---

## 📦 Modelos Creados/Actualizados

### Nuevos Modelos

#### `Pais`
```php
- Relaciones:
  ↳ departamentos() : HasMany
```

#### `Departamento`
```php
- Relaciones:
  ↳ pais() : BelongsTo
  ↳ municipios() : HasMany
```

#### `Municipio`
```php
- Relaciones:
  ↳ departamento() : BelongsTo
  ↳ barrios() : HasMany
  ↳ sedes() : HasMany
```

#### `Barrio`
```php
- Relaciones:
  ↳ municipio() : BelongsTo
  ↳ sedes() : HasMany
```

### Modelos Actualizados

#### `Empresa` (Actualizado)
```php
- Fillable: [nombre, nit, digito_verificacion, telefono, email, pagina_web, 
             tipo_contribuyente, responsabilidades_fiscales, direccion, estado]
- Casts: [responsabilidades_fiscales => 'array', estado => 'boolean']
- Relaciones:
  ↳ sedes() : HasMany
  ↳ usuarios() : HasMany
```

#### `Sede` (Actualizado)
```php
- Fillable: [empresa_id, nombre, codigo, direccion, municipio_id, barrio_id, 
             codigo_postal, telefono, email, estado]
- Casts: [estado => 'boolean']
- Relaciones:
  ↳ empresa() : BelongsTo
  ↳ municipio() : BelongsTo
  ↳ barrio() : BelongsTo
  ↳ usuarios() : HasMany
```

---

## 🌱 Datos de Seeding

Se han cargado automáticamente los siguientes datos:

### 1. País
- **Colombia** (código DANE: 170)

### 2. Departamentos (32 total)
- Antioquia, Bogotá D.C., Atlántico, Bolívar, Boyacá, Caldas, Caquetá, Cauca, 
- Cesar, Córdoba, Cundinamarca, Guaviare, Huila, La Guajira, Magdalena, Meta, 
- Nariño, Norte de Santander, Quindío, Risaralda, Santander, Sucre, Tolima, 
- Valle del Cauca, Arauca, Casanare, Putumayo, San Andrés, Amazonas, Vichada, 
- Guainía, Vaupés

### 3. Municipios (50+ principales)
Incluye municipios principales por departamento como:
- **Antioquia:** Medellín, Abejorral, Abrigo, Belmira, Bello
- **Bogotá D.C.:** Bogotá
- **Cundinamarca:** Agua de Dios, Bojacá, Chía, Mosquera, Soacha
- **Valle del Cauca:** Alcalá, Cali, Palmira, Tuluá, Yumbo
- Y más...

### 4. Barrios (100+ por ciudades principales)
Barrios para 9 ciudades principales:
- **Medellín:** Belén, Laureles, El Hueco, San Alejo, Junín, Robledo, Arví, etc.
- **Bogotá:** La Candelaria, Chapinero, Usaquén, Teusaquillo, Barrios Unidos, etc.
- **Cali:** San Antonio, San Joaquín, Menga, La Ferretería, Cristo Rey, etc.
- Y más ciudades...

---

## 📄 Migraciones Creadas

| Archivo | Descripción | Estado |
|---------|-------------|--------|
| `2026_04_22_000004_create_paises_table.php` | Crear tabla paises | ✅ Ran |
| `2026_04_22_000005_create_departamentos_table.php` | Crear tabla departamentos | ✅ Ran |
| `2026_04_22_000006_create_municipios_table.php` | Crear tabla municipios | ✅ Ran |
| `2026_04_22_000007_create_barrios_table.php` | Crear tabla barrios | ✅ Ran |
| `2026_04_22_000008_modify_empresas_table.php` | Modificar tabla empresas | ✅ Ran |
| `2026_04_22_000009_modify_sedes_table.php` | Modificar tabla sedes | ✅ Ran |

---

## 📋 Seeders Creados

| Archivo | Registros | Estado |
|---------|-----------|--------|
| `PaisSeeder.php` | 1 (Colombia) | ✅ Done |
| `DepartamentoSeeder.php` | 32 | ✅ Done |
| `MunicipioSeeder.php` | 50+ | ✅ Done |
| `BarrioSeeder.php` | 100+ | ✅ Done |

---

## 🔐 Características de Seguridad

- ✅ Foreign keys con `onDelete('restrict')` para proteger datos de ubicación
- ✅ Índices en todos los campos `codigo_dane` para búsquedas rápidas
- ✅ Valores únicos en campos identificadores (nit, codigo_dane)
- ✅ JSON accesible como array mediante casts en Eloquent
- ✅ Validación a nivel de base de datos mediante constraints

---

## 💡 Ejemplos de Uso

### Consultar empresas con sus sedes y ubicaciones

```php
$empresa = Empresa::with(['sedes.municipio.departamento', 'sedes.barrio'])->find(1);

// Acceder a datos
echo $empresa->nit; // NIT de la empresa
echo $empresa->tipo_contribuyente; // Tipo de contribuyente
echo $empresa->responsabilidades_fiscales; // Array de responsabilidades

// Acceder a sedes y ubicaciones
foreach ($empresa->sedes as $sede) {
    echo $sede->nombre;
    echo $sede->municipio->nombre; // Municipio
    echo $sede->municipio->departamento->nombre; // Departamento
    echo $sede->barrio->nombre; // Barrio
}
```

### Consultar municipios con sus departamentos

```php
$municipios = Municipio::with('departamento.pais')->get();

foreach ($municipios as $municipio) {
    echo "{$municipio->nombre}, {$municipio->departamento->nombre}";
}
```

### Consultar barrios de una ciudad

```php
$medellin = Municipio::where('codigo_dane', '05001')->first();
$barrios = $medellin->barrios()->paginate(20);
```

---

## 🔄 Reversiones de Migraciones

Para revertir todas las migraciones:
```bash
php artisan migrate:rollback
```

Para revertir solo el último batch:
```bash
php artisan migrate:rollback --step=1
```

---

## 📌 Notas Importantes

1. **Enums vs Strings:** MySQL 5.7 no soporta enums nativos. Se utiliza `string` con validación en Laravel.
2. **JSON Support:** Requiere MySQL 5.7+ (incluido en XAMPP)
3. **Relaciones Restringidas:** Las ubicaciones están protegidas con `onDelete('restrict')`
4. **Índices DANE:** Todos los códigos DANE están indexados para búsquedas rápidas
5. **Casts Automáticos:** El campo `responsabilidades_fiscales` se convierte automáticamente a array

---

## ✅ Verificación Final

Todas las migraciones han sido aplicadas correctamente:
```
✓ paises
✓ departamentos  
✓ municipios
✓ barrios
✓ empresas (modificada)
✓ sedes (modificada)
```

Los seeders han poblado exitosamente la base de datos con:
- 1 país (Colombia)
- 32 departamentos
- 50+ municipios
- 100+ barrios

**¡Tu base de datos está lista para producción!** 🚀

---

*Generated by CEOGESTION Database Migration System*
