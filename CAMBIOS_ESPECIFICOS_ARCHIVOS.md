# 🔧 CAMBIOS ESPECÍFICOS EN ARCHIVOS EXISTENTES

Este documento describe los cambios exactos a realizar en los 4 archivos que necesitan ser modificados.

---

## 1️⃣ app/Http/Controllers/AuthController.php

**Cambio:** Actualizar el método `showLogin()`

**Línea actual (antes):**
```php
public function showLogin()
{
    // Obtener la empresa principal (primera empresa de la BD)
    $empresa = Empresa::where('estado', true)->first() ?? Empresa::first();
    
    return view('auth.login', ['empresa' => $empresa]);
}
```

**Nueva versión (después):**
```php
public function showLogin()
{
    // Obtener la empresa principal (primera empresa de la BD)
    $empresa = Empresa::where('estado', true)->first() ?? Empresa::first();
    $theme = $empresa?->themeSetting()->first();
    
    return view('auth.login', [
        'empresa' => $empresa,
        'theme' => $theme,
    ]);
}
```

**Cambios:**
- ✓ Agregar línea: `$theme = $empresa?->themeSetting()->first();`
- ✓ Pasar `$theme` a la vista

---

## 2️⃣ app/Models/Empresa.php

**Cambio:** Agregar relación `themeSetting()` al final de la clase

**Ubicación:** Justo antes del cierre de la clase `}` final

**Agregar:**
```php
    /**
     * Relación: Una empresa tiene una configuración de tema
     */
    public function themeSetting()
    {
        return $this->hasOne(EmpresaThemeSetting::class);
    }
```

**Contexto (las 3 líneas antes):**
```php
    public function sedes(): HasManyThrough
    {
        return $this->hasManyThrough(Sede::class, Cliente::class);
    }
    
    // ← AGREGAR AQUÍ LA NUEVA RELACIÓN
```

---

## 3️⃣ database/seeders/DatabaseSeeder.php

**Cambio:** Agregar `ThemeSeeder` después de `RoleAndPermissionSeeder`

**Ubicación actual (antes):**
```php
        // =======================
        // 3. ROLES Y PERMISOS (CRÍTICO - Debe ser primero)
        // =======================
        $this->call(RoleAndPermissionSeeder::class);

        // =======================
        // 4. CATEGORÍAS
        // =======================
        $this->call(CategoriaSeeder::class);

        // =======================
        // 4. CATEGORÍAS   ← DUPLICADO, ELIMINAR
        // =======================
        $this->call(CategoriaSeeder::class);
```

**Nueva versión (después):**
```php
        // =======================
        // 3. ROLES Y PERMISOS (CRÍTICO - Debe ser primero)
        // =======================
        $this->call(RoleAndPermissionSeeder::class);

        // =======================
        // 4. TEMAS (TEMA DEL LOGIN Y APLICACIÓN)
        // =======================
        $this->call(ThemeSeeder::class);

        // =======================
        // 5. CATEGORÍAS
        // =======================
        $this->call(CategoriaSeeder::class);
```

**Cambios:**
- ✓ Agregar `$this->call(ThemeSeeder::class);` después de RoleAndPermissionSeeder
- ✓ Eliminar la segunda (duplicada) llamada a CategoriaSeeder
- ✓ Actualizar números de comentarios (4→5, 5→6, etc.)

---

## 4️⃣ resources/views/auth/login.blade.php

**Cambio:** Asegurar que se cargan los estilos y el logo dinámicamente

**Verificar que tenga:**

### A. En el `<head>`, la hoja de estilos:
```html
<link rel="stylesheet" href="{{ asset('css/login-modern.css') }}">
```

### B. El logo de empresa en el HTML:
```html
@if($empresa && $empresa->logo)
    <img src="{{ asset('storage/' . $empresa->logo) }}" alt="{{ $empresa->nombre }}" class="logo-empresa">
@else
    <span style="font-size: 3rem;">🏢</span>
@endif
```

### C. El nombre de la empresa:
```html
<h1>{{ $empresa?->nombre ?? 'CEOGestion' }}</h1>
<p>{{ $empresa?->descripcion ?? 'Sistema de Gestión Empresarial' }}</p>
```

### D. Al final del body, el script de tema (opcional):
```html
@if($theme)
<script>
    document.documentElement.style.setProperty('--color-primary', '{{ $theme->getPrimaryColor() }}');
    document.documentElement.style.setProperty('--color-secondary', '{{ $theme->getSecondaryColor() }}');
    document.documentElement.style.setProperty('--color-accent', '{{ $theme->getAccentColor() }}');
    document.documentElement.style.setProperty('--color-text', '{{ $theme->getTextColor() }}');
    document.documentElement.style.setProperty('--color-text-light', '{{ $theme->getTextLightColor() }}');
</script>
@endif
```

**Nota:** Si el archivo ya tiene estos elementos, no es necesario hacer cambios. Solo verificar que estén presentes.

---

## ✅ VALIDACIÓN POST-CAMBIOS

Después de hacer estos cambios:

1. **AuthController.php**: Debe poder acceder a `/login` sin errores
2. **Empresa.php**: Verificar en tinker que `$empresa->themeSetting()` funciona
3. **DatabaseSeeder.php**: Ejecutar `php artisan migrate:fresh --seed` sin errores
4. **login.blade.php**: Verificar que el logo y tema se cargan en `/login`

---

## 🔍 BÚSQUEDA Y REEMPLAZO (Para File Manager)

Si prefieres hacerlo manualmente en cPanel:

### Paso 1: Abrir AuthController.php en Editor
- cPanel File Manager → app/Http/Controllers/ → AuthController.php (Right click → Edit)
- Buscar: `public function showLogin()`
- Reemplazar el método completo

### Paso 2: Abrir Empresa.php en Editor
- cPanel File Manager → app/Models/ → Empresa.php (Right click → Edit)
- Ir al final de la clase (antes del `}`)
- Pegar la nueva relación

### Paso 3: Abrir DatabaseSeeder.php en Editor
- Buscar: `$this->call(RoleAndPermissionSeeder::class);`
- Agregar después: `$this->call(ThemeSeeder::class);`
- Buscar el duplicado de CategoriaSeeder y eliminar

### Paso 4: Verificar login.blade.php
- Solo asegurarse que tiene los elementos mencionados

---

## 📝 NOTA IMPORTANTE

Si cometes un error en alguno de estos archivos:
1. La aplicación puede no iniciar
2. Puedes restaurar desde backup
3. O corregir el error y hacer `php artisan cache:clear`

**Recomendación:** Hacer backup antes de modificar cualquier archivo.

---

**Tiempo estimado:** 10 minutos para hacer estos cambios  
**Dificultad:** Media (copiar y pegar)  
**Riesgo:** Bajo (si sigues los pasos exactamente)
