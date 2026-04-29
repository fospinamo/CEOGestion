# Categorías Paramétricas - Documentación de Implementación

## Resumen
Las categorías han sido convertidas de un **enum hardcodeado** a un **sistema parametrizable en base de datos**. Esto permite que los administradores creen, editen y eliminen categorías sin necesidad de modificar código.

## Cambios Realizados

### 1. Base de Datos

#### Migración: `2026_04_24_000027_create_categorias_table.php`
- **Tabla**: `categorias`
- **Campos**:
  - `id` (PK)
  - `nombre` (VARCHAR 100, UNIQUE) - Nombre de la categoría
  - `slug` (VARCHAR 100, UNIQUE) - Slug URL-friendly
  - `descripcion` (TEXT, nullable) - Descripción detallada
  - `icono` (VARCHAR 50, nullable) - Icono Font Awesome (ej: fa-desktop)
  - `color` (VARCHAR 7, default #3b82f6) - Color hexadecimal
  - `estado` (BOOLEAN, default true) - Indica si está activa
  - `created_at`, `updated_at` (timestamps)

#### Migración: `2026_04_24_000028_add_categoria_id_to_tipos_equipos.php`
- Agrega `categoria_id` FK a la tabla `tipos_equipos`
- Relación: `tipos_equipos → categorias`
- Constraint: `onDelete('restrict')` - Previene eliminar categorías en uso
- Campo `categoria` se mantiene como nullable para compatibilidad

### 2. Modelos

#### Categoría (Nueva)
```php
// app/Models/Categoria.php
class Categoria extends Model {
    // Relaciones
    public function tiposEquipos(): HasMany
    
    // Scopes
    public function scopeActivas($query)
    
    // Helpers
    public static function generarSlug(string $nombre): string
    public function cantidadTipos(): int
    public function getRouteKeyName() // Usa 'slug' como ruta
}
```

**Categorías Iniciales (Seeder)**:
- HARDWARE - Icono: fa-microchip - Color: #3b82f6 (Azul)
- SOFTWARE - Icono: fa-code - Color: #10b981 (Verde)
- RED - Icono: fa-network-wired - Color: #f59e0b (Ámbar)
- PERIFERICO - Icono: fa-print - Color: #ef4444 (Rojo)
- OTRO - Icono: fa-cubes - Color: #8b5cf6 (Púrpura)

#### TipoEquipo (Actualizado)
```php
// app/Models/TipoEquipo.php
class TipoEquipo extends Model {
    // Nueva relación
    public function categoriaObj(): BelongsTo
    
    // Scopes nuevos
    public function scopePorCategoriaId($query, $categoriaId)
    
    // Métodos heredados (deprecados)
    public static function categorias() // Legacy enum
    public function scopePorCategoria($query, $categoria)
}
```

### 3. Controladores

#### CategoriaController (Nuevo)
- **Métodos**: index, create, store, show, edit, update, destroy
- **Validaciones**:
  - `nombre`: required, unique, max 100
  - `slug`: auto-generado desde nombre
  - `color`: formato hexadecimal (#RRGGBB)
- **Restricciones**:
  - No se puede eliminar categoría con tipos de equipos asociados

#### TipoEquipoController (Actualizado)
- **Cambio Principal**: `categoria` enum → `categoria_id` FK
- **Validación**: `categoria_id|required|exists:categorias,id`
- **Datos Pasados a Vistas**: `$categorias = Categoria::activas()`
- **Relación**: Carga con `categoriaObj` en lugar de campo legacy

### 4. Rutas

```php
// routes/web.php
Route::resource('categorias', CategoriaController::class);
Route::resource('tipos-equipos', TipoEquipoController::class);
```

- **Categorías**: Acceso en `/categorias`
- **Tipos de Equipos**: Acceso en `/tipos-equipos`

### 5. Vistas

#### Categorías
- **index.blade.php**: Tabla DataTables con nombre, slug, icono, color, cantidad de tipos
- **create.blade.php**: Formulario con color picker, Font Awesome preview
- **edit.blade.php**: Edición con slug readonly (se regenera automáticamente)
- **show.blade.php**: Vista completa con tipos de equipos asociados

#### Tipos de Equipos (Actualizadas)
- **index.blade.php**: 
  - Muestra categoría con icono, color, y nombre
  - Eliminado enum HARDWARE/SOFTWARE/etc
- **create.blade.php**: 
  - Select dropdown con categorías activas
  - Link para crear nueva categoría
- **edit.blade.php**: 
  - Similar a create pero con datos precargados
- **show.blade.php**: 
  - Muestra categoría con diseño mejorado
  - Lista de equipos asociados

### 6. Seeders

#### CategoriaSeeder (Nuevo)
```php
// database/seeders/CategoriaSeeder.php
// Crea 5 categorías iniciales
```

#### DatabaseSeeder (Actualizado)
- **Orden de Ejecución**:
  1. Ubicación DANE
  2. Empresa
  3. **CategoriaSeeder** ← NUEVO (requerido por TipoEquipo)
  4. TipoEquipoSeeder

#### TipoEquipoSeeder (Actualizado)
- Ahora busca `categoria_id` desde tabla `categorias`
- Mapea nombres de categoría legacy (HARDWARE, SOFTWARE, etc) a IDs
- Rellena `categoria_id` FK en lugar de enum

## Flujo de Uso

### Crear Nueva Categoría
1. Navegar a `/categorias`
2. Clic en "Nueva Categoría"
3. Rellenar formulario:
   - Nombre (ej: "Servidores")
   - Slug (auto-generado: "servidores")
   - Descripción (opcional)
   - Icono Font Awesome (opcional)
   - Color (selector visual)
   - Estado (checkbox)
4. Guardar

### Crear Tipo de Equipo
1. Navegar a `/tipos-equipos`
2. Clic en "Nuevo Tipo"
3. Seleccionar **Categoría** del dropdown
4. Rellenar nombre, descripción, icono
5. Guardar

### Gestionar Categorías
- **Ver**: Click en ícono ojo en tabla
- **Editar**: Click en lápiz (slug se regenera automáticamente)
- **Eliminar**: Click en basura (solo si no tiene tipos asociados)
- **Bloquear**: Categoría con tipos asociados no se puede eliminar

## Compatibilidad y Migración

### Campo Legacy `categoria`
- Mantiene los valores enum antiguos (HARDWARE, SOFTWARE, RED, PERIFERICO, OTRO)
- Declarado como `nullable` en migración
- Útil para migración gradual de datos
- Se puede deprecar en futuras versiones

### Relación FK `categoria_id`
- Nuevo campo principal
- Todos los nuevos tipos de equipos usan `categoria_id`
- Constrain `onDelete('restrict')` protege integridad

### Transición
```sql
-- Query para migrar datos legacy a nuevo sistema
UPDATE tipos_equipos 
SET categoria_id = (
    SELECT id FROM categorias 
    WHERE categorias.nombre = tipos_equipos.categoria
)
WHERE categoria_id IS NULL AND categoria IS NOT NULL
```

## Características

### Categorías
✓ Crear, editar, eliminar categorías  
✓ Slug auto-generado  
✓ Color personalizado (color picker)  
✓ Icono Font Awesome (25,000+ opciones)  
✓ Estado activa/inactiva  
✓ Contador de tipos de equipos  
✓ Protección FK (no eliminar si hay tipos)  

### Tipos de Equipos
✓ Seleccionar categoría en formulario  
✓ Ver categoría con icono y color  
✓ Validación FK en backend  
✓ Link para crear categoría desde formulario  

## Archivos Creados/Modificados

### Nuevos
- `app/Models/Categoria.php`
- `app/Http/Controllers/CategoriaController.php`
- `database/seeders/CategoriaSeeder.php`
- `database/migrations/2026_04_24_000027_create_categorias_table.php`
- `database/migrations/2026_04_24_000028_add_categoria_id_to_tipos_equipos.php`
- `resources/views/categorias/index.blade.php`
- `resources/views/categorias/create.blade.php`
- `resources/views/categorias/edit.blade.php`
- `resources/views/categorias/show.blade.php`

### Modificados
- `app/Models/TipoEquipo.php` - Agregada relación `categoriaObj()`
- `app/Http/Controllers/TipoEquipoController.php` - Actualizado para usar FK
- `database/seeders/DatabaseSeeder.php` - Agregado CategoriaSeeder
- `database/seeders/TipoEquipoSeeder.php` - Actualizado para mapear categorías
- `resources/views/tipos-equipos/index.blade.php` - Muestra categoría con estilo
- `resources/views/tipos-equipos/create.blade.php` - Select de categorías
- `resources/views/tipos-equipos/edit.blade.php` - Select de categorías
- `resources/views/tipos-equipos/show.blade.php` - Muestra categoría
- `routes/web.php` - Agregada ruta de categorías

## Estado Actual

✅ **Completado**:
- Modelo Categoria con todas las relaciones
- Controlador CategoriaController con CRUD completo
- Vistas para gestionar categorías
- Migraciones aplicadas (28 migracionales en total)
- Categorías iniciales sembradas (5 categorías)
- Tipos de equipos vinculados a categorías
- TipoEquipoController actualizado
- Vistas de tipos-equipos actualizadas
- DatabaseSeeder con orden correcto

⏳ **Próximos Pasos (Opcional)**:
1. Crear script de migración de datos legacy (si hay datos antiguos)
2. Agregar filtro de categorías en vista de tipos-equipos
3. Agregar iconos customizados para categorías
4. API REST para categorías (si se requiere desde JS)
5. Deprecación formal del campo `categoria` legacy

## Testing

### Verificaciones Realizadas
✓ Migraciones aplicadas correctamente (2 nuevas)
✓ Categorías sembradas (5 iniciales)
✓ Tipos de equipos vinculados correctamente
✓ Relación FK funcional
✓ Validación de restricción FK
✓ Vistas renderizadas correctamente

### Cómo Probar
1. Navegar a `/categorias` - Ver lista de categorías
2. Clic en categoría - Ver detalles y tipos de equipos
3. Navegar a `/tipos-equipos` - Ver categorías con colores
4. Crear tipo de equipo - Seleccionar categoría del dropdown
5. Editar categoría - Verificar slug auto-generado
6. Intentar eliminar categoría con tipos - Verificar error

## Conclusión

Las categorías han sido convertidas exitosamente a un sistema parametrizable en base de datos. El sistema es:

- **Flexible**: Administradores pueden crear/editar categorías sin código
- **Seguro**: FK constraints previenen datos inconsistentes
- **Escalable**: Soporta cualquier cantidad de categorías
- **Amigable**: Interfaz intuitiva con color picker e iconos
- **Compatible**: Campo legacy mantiene compatibilidad
