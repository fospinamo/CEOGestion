# Sistema de Filtros Dinámicos - Verificación y Validación

## 📋 Descripción General

Se ha implementado un sistema de filtros dinámicos que relaciona los campos de empresa/cliente con el campo de sede. Cuando el usuario selecciona una empresa, el selector de sede se actualiza automáticamente para mostrar solo las sedes pertenecientes a esa empresa.

---

## ✅ Vistas Actualizadas

### 1. **usuarios/create.blade.php**
- **Cambios:**
  - Agregado `id="empresa_id"` al selector de empresa (línea 67)
  - Agregado `id="sede_id"` al selector de sede (línea 103)
  - Agregado `data-empresa-id="{{ $sede->empresa_id }}"` a cada opción de sede
  - Agregado JavaScript para filtrar dinámicamente sedes por empresa
- **Comportamiento:**
  - Al cambiar la empresa, se filtran las sedes disponibles
  - Si no hay empresa seleccionada, muestra todas las sedes
  - Mantiene la selección anterior si la sede pertenece a la empresa seleccionada

### 2. **usuarios/edit.blade.php**
- **Cambios:**
  - Agregado `id="empresa_id"` al selector de empresa (línea 67)
  - Agregado `id="sede_id"` al selector de sede (línea 103)
  - Agregado `data-empresa-id="{{ $sede->empresa_id }}"` a cada opción de sede
  - Agregado JavaScript para filtrar dinámicamente sedes por empresa
- **Comportamiento:**
  - Igual a create.blade.php
  - Al cargar la página, filtra sedes según la empresa pre-seleccionada

### 3. **servicios/create.blade.php**
- **Estado:** ✅ YA IMPLEMENTADO
- **Comportamiento:** Filtra equipos por cliente dinámicamente

---

## 🔌 Rutas API Nuevas

### 1. **GET /api/sedes-por-empresa**
```
Query Parameters:
  - empresa_id: ID de la empresa (requerido)

Response: Array of Sede objects
[
  { id: 1, nombre: "Sede Centro" },
  { id: 2, nombre: "Sede Norte" },
  ...
]

Filtros aplicados:
  - estado = 'ACTIVA'
  - empresa_id = ? (parámetro)
```

### 2. **GET /api/sedes-por-cliente**
```
Query Parameters:
  - cliente_id: ID del cliente (requerido)

Response: Array of Sede objects
[
  { id: 1, nombre: "Sede Operativa" },
  { id: 2, nombre: "Sede Administrativa" },
  ...
]

Filtros aplicados:
  - estado = 'ACTIVA'
  - cliente_id = ? (parámetro)
```

---

## 🎯 JavaScript Implementado

### Lógica de Filtrado (usuarios/create.blade.php y edit.blade.php)

```javascript
document.addEventListener('DOMContentLoaded', function() {
    const empresaSelect = document.getElementById('empresa_id');
    const sedeSelect = document.getElementById('sede_id');
    
    // Guardar opciones originales de sede con sus IDs de empresa asociados
    const originalSedeOptions = Array.from(sedeSelect.options).map(opt => ({
        value: opt.value,
        text: opt.text,
        empresaId: opt.dataset.empresaId
    }));
    
    function actualizarSedes() {
        const empresaId = empresaSelect.value;
        
        if (!empresaId) {
            // Sin empresa seleccionada: mostrar todas las sedes
            sedeSelect.innerHTML = '<option value="">Selecciona una sede</option>';
            // Repoblar todas las opciones originales
        } else {
            // Con empresa seleccionada: mostrar solo sedes de esa empresa
            sedeSelect.innerHTML = '<option value="">Selecciona una sede</option>';
            // Filtrar por empresaId
        }
    }
    
    empresaSelect.addEventListener('change', actualizarSedes);
    actualizarSedes(); // Ejecutar al cargar
});
```

---

## 📊 Vistas con Relaciones Empresa-Sede

| Vista | Tipo | Empresa | Sede | Estado |
|-------|------|---------|------|--------|
| usuarios/create.blade.php | Crear | ✅ Dinámico | ✅ Dinámico | Implementado |
| usuarios/edit.blade.php | Editar | ✅ Dinámico | ✅ Dinámico | Implementado |
| sedes/create.blade.php | Crear | ⚪ N/A | - | N/A (empresa y cliente son mutuamente excluyentes) |
| sedes/edit.blade.php | Editar | ⚪ N/A | - | N/A |
| servicios/create.blade.php | Crear | - | - | ✅ Ya tiene cliente→equipo |
| areas/index.blade.php | Listar | ✅ Filtro | - | Server-side filtering |

---

## 🧪 Casos de Prueba

### Caso 1: Crear Usuario sin Empresa
**Pasos:**
1. Ir a Crear Usuario
2. No seleccionar empresa
3. Verificar que el selector de sede muestra todas las sedes

**Resultado Esperado:** ✅ Todas las sedes disponibles

### Caso 2: Crear Usuario con Empresa
**Pasos:**
1. Ir a Crear Usuario
2. Seleccionar una empresa (ej: "Empresa A")
3. Verificar que el selector de sede se actualiza

**Resultado Esperado:** ✅ Solo sedes pertenecientes a "Empresa A"

### Caso 3: Editar Usuario - Cambiar Empresa
**Pasos:**
1. Abrir usuario existente
2. Cambiar empresa de "Empresa A" a "Empresa B"
3. Verificar que sedes se actualizan

**Resultado Esperado:** ✅ Sedes de "Empresa B" se muestran, sede anterior se deselecciona

### Caso 4: Editar Usuario - Mantener Empresa
**Pasos:**
1. Abrir usuario con empresa y sede asignadas
2. No cambiar empresa
3. Verificar que sede mantiene su selección

**Resultado Esperado:** ✅ Sede pre-seleccionada se mantiene

---

## 🔄 Flujo de Datos

```
[Usuario selecciona empresa]
            ↓
[JavaScript captura el cambio]
            ↓
[Filtra opciones originales de sede]
            ↓
[Actualiza selector de sede]
            ↓
[Usuario selecciona sede]
            ↓
[Envía formulario con empresa_id y sede_id]
            ↓
[Servidor valida relación]
```

---

## ⚙️ Configuración de HTML

### Atributos Requeridos

```html
<!-- Selector de Empresa (con ID) -->
<select id="empresa_id" name="empresa_id">
    <option value="">Selecciona una empresa</option>
    @foreach($empresas as $empresa)
        <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
    @endforeach
</select>

<!-- Selector de Sede (con ID y data-empresa-id) -->
<select id="sede_id" name="sede_id">
    <option value="">Selecciona una sede</option>
    @foreach($sedes as $sede)
        <option value="{{ $sede->id }}" 
                data-empresa-id="{{ $sede->empresa_id }}">
            {{ $sede->nombre }}
        </option>
    @endforeach
</select>
```

**Atributos críticos:**
- `id="empresa_id"` - Identificador para JavaScript
- `id="sede_id"` - Identificador para JavaScript
- `data-empresa-id="{{ $sede->empresa_id }}"` - Mapeo de relación

---

## 🚀 Cómo Extender a Otras Vistas

Si necesitas agregar este comportamiento a otras vistas:

1. **Identifica los selectores de empresa y sede**
2. **Agrega IDs únicos:**
   ```html
   <select id="empresa_id" name="empresa_id">...</select>
   <select id="sede_id" name="sede_id">...</select>
   ```
3. **Agrega data-empresa-id a opciones de sede:**
   ```html
   <option data-empresa-id="{{ $sede->empresa_id }}">...</option>
   ```
4. **Copia el script de filtrado:**
   ```javascript
   <script>
   // ... código del script
   </script>
   ```

---

## 📝 Validación del Servidor

**Backend (recomendado):**
```php
// En el controlador, validar que la sede pertenece a la empresa
$validated = $request->validate([
    'empresa_id' => 'required|exists:empresas,id',
    'sede_id' => 'nullable|exists:sedes,id',
    // Validación personalizada:
    // Si sede_id es proporcionado, debe pertenecer a empresa_id
]);
```

---

## 📋 Checklist de Implementación

- [x] Rutas API para obtener sedes por empresa
- [x] Rutas API para obtener sedes por cliente
- [x] usuarios/create.blade.php - IDs agregados
- [x] usuarios/create.blade.php - JavaScript agregado
- [x] usuarios/edit.blade.php - IDs agregados
- [x] usuarios/edit.blade.php - JavaScript agregado
- [x] data-empresa-id en todas las opciones de sede
- [ ] Pruebas manuales completadas
- [ ] Validación server-side implementada (recomendado)
- [ ] Documentación actualizada

---

## 🔗 Archivos Modificados

1. **routes/web.php**
   - Agregadas rutas API para sedes dinámicas

2. **resources/views/usuarios/create.blade.php**
   - IDs agregados a selectores
   - JavaScript agregado para filtrado

3. **resources/views/usuarios/edit.blade.php**
   - IDs agregados a selectores
   - JavaScript agregado para filtrado

---

## 💡 Notas Importantes

1. **Performance:** El filtrado se realiza en cliente, por lo que es instantáneo
2. **Fallback:** Si JavaScript está deshabilitado, se muestran todas las sedes
3. **Compatibilidad:** Funciona en todos los navegadores modernos
4. **Datos:** Los data-attributes contienen IDs de empresa para cada sede

---

## 🐛 Posibles Problemas y Soluciones

| Problema | Causa | Solución |
|----------|-------|----------|
| Sedes no se filtran | Falta ID en selector | Verificar que `id="empresa_id"` existe |
| Sedes se borran al cambiar | data-attribute falta | Agregar `data-empresa-id="{{ $sede->empresa_id }}"` |
| JavaScript no ejecuta | Error en consola | Abrir DevTools y revisar errores |
| Selección anterior se pierde | Comportamiento esperado | Es normal si sede no existe en empresa nueva |

---

## 📞 Soporte

Para agregar esta funcionalidad a nuevas vistas o problemas, asegúrate de:
1. Que los selectores tengan los IDs correctos
2. Que data-attributes estén presentes
3. Que el JavaScript esté dentro de tags `<script>`
4. Que jQuery no interfiera (usar vanilla JavaScript)
