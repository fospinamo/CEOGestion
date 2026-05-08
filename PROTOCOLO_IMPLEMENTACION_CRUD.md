# 📋 PROTOCOLO DE IMPLEMENTACIÓN - CRUD RESOURCE ROUTES

**Fecha de creación:** 8 de Mayo, 2026  
**Propósito:** Evitar errores repetitivos de routing y parámetros en implementaciones CRUD

---

## 1️⃣ VISTAS (BLADE FILES) - PARÁMETROS EN RUTAS

### ✅ REGLA 1: Siempre pasar parámetros de forma explícita

```blade
<!-- ❌ INCORRECTO - No funcionará con Route::resource -->
<form action="{{ route('parametros.tipos-equipos.update', $tipoEquipo) }}" method="POST">

<!-- ✅ CORRECTO - Parámetro explícito con nombre de ruta -->
<form action="{{ route('parametros.tipos-equipos.update', ['tipos_equipo' => $tipoEquipo->id]) }}" method="POST">

<!-- ✅ ALTERNATIVA - Para modelos con slug como route key -->
<form action="{{ route('parametros.categorias.update', ['categoria' => $categoria->slug]) }}" method="POST">
```

### ✅ REGLA 2: Validar que variable esté disponible

```blade
@if(isset($tipoEquipo) && $tipoEquipo)
    <form action="{{ route('parametros.tipos-equipos.update', ['tipos_equipo' => $tipoEquipo->id]) }}" method="POST">
        <!-- formulario -->
    </form>
@else
    <div class="alert alert-error">Error: Modelo no disponible</div>
@endif
```

---

## 2️⃣ CONTROLADORES - MANEJO DE VALIDACIÓN

### ✅ REGLA 3: Al retornar vista con errores, pasar SIEMPRE el modelo

```php
public function update(Request $request, TipoEquipo $tipoEquipo): RedirectResponse
{
    // Validar
    $validated = $request->validate([
        'nombre' => 'required|string|unique:tipos_equipos,nombre,' . $tipoEquipo->id,
        'descripcion' => 'nullable|string|max:500',
        'categoria_id' => 'required|exists:categorias,id',
        'icono' => 'nullable|string|max:50',
    ]);

    // ✅ IMPORTANTE: Cargar la categoría si es necesaria en la vista
    $categorias = Categoria::activas()->orderBy('nombre')->get();

    // Si validación falla, esto re-renderiza la vista con errores
    // Laravel automáticamente pasa $tipoEquipo por implicit binding
    
    $tipoEquipo->update($validated);

    return redirect()->route('parametros.tipos-equipos.show', $tipoEquipo)
        ->with('success', 'Actualizado exitosamente');
}
```

### ✅ REGLA 4: Para vistas que necesitan datos en error, usar método manual

```php
public function update(Request $request, TipoEquipo $tipoEquipo): RedirectResponse
{
    $validated = $request->validate([...]);

    // Si llegamos aquí, la validación pasó
    $tipoEquipo->update($validated);

    return redirect()->route('parametros.tipos-equipos.show', $tipoEquipo)
        ->with('success', 'Actualizado exitosamente');
    
    // NOTA: Si validación falla, Laravel automáticamente vuelve a 'edit'
    // con $tipoEquipo disponible por implicit binding
}
```

### ✅ REGLA 5: En vistas compartidas (create/edit), siempre null-check

```blade
@php
    $isEdit = isset($tipoEquipo) && $tipoEquipo;
    $formRoute = $isEdit 
        ? route('parametros.tipos-equipos.update', ['tipos_equipo' => $tipoEquipo->id])
        : route('parametros.tipos-equipos.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<form action="{{ $formRoute }}" method="POST">
    @csrf
    @if($isEdit)
        @method($method)
    @endif
    
    <!-- campos -->
</form>
```

---

## 3️⃣ RUTAS - CONFIGURACIÓN DE RECURSOS

### ✅ REGLA 6: Usar nomenclatura singular en parámetros

```php
// ✅ CORRECTO - Laravel usa singular como nombre de parámetro
Route::resource('tipos-equipos', TipoEquipoController::class);
// Genera: {tipos_equipo} no {tipos-equipo}

Route::resource('categorias', CategoriaController::class);
// Genera: {categoria} no {categorias}

Route::resource('contratos', ContratoController::class);
// Genera: {contrato} no {contratos}
```

### ✅ REGLA 7: Rutas específicas ANTES de recurso

```php
Route::middleware(['auth'])->group(function () {
    Route::prefix('parametros')->name('parametros.')->group(function () {
        
        // ✅ PRIMERO: Rutas específicas/personalizadas
        Route::get('equipos/exportar/excel', [EquipoController::class, 'exportarExcel'])->name('equipos.exportar.excel');
        Route::get('equipos/exportar/pdf', [EquipoController::class, 'exportarPdf'])->name('equipos.exportar.pdf');
        
        // ✅ DESPUÉS: Rutas resource (que generan CRUD automático)
        Route::resource('equipos', EquipoController::class);
        Route::resource('tipos-equipos', TipoEquipoController::class);
        Route::resource('categorias', CategoriaController::class);
    });
});
```

---

## 4️⃣ MODELOS - IMPLICIT ROUTE BINDING

### ✅ REGLA 8: Definir route key si no es 'id'

```php
<?php

namespace App\Models;

class Categoria extends Model
{
    // ✅ Si usas algo distinto a 'id' como identificador en URL
    public function getRouteKeyName(): string
    {
        return 'slug'; // Ahora: /categorias/equipos-oficina en lugar de /categorias/1
    }
}

class TipoEquipo extends Model
{
    // ✅ Si usas 'id' (default), no necesitas declarar nada
    // public function getRouteKeyName(): string
    // {
    //     return 'id'; // Es el default
    // }
}
```

---

## 5️⃣ CHECKLIST PARA NUEVOS CRUD

Usar este checklist ANTES de hacer commit:

```
CONTROLADOR:
  ☐ index(): Retorna vista con listado
  ☐ create(): Retorna vista vacía
  ☐ store(): Valida y guarda, redirige a show
  ☐ show(): Retorna vista de detalle
  ☐ edit(Model $model): Retorna vista con datos (implicit binding)
  ☐ update(Request, Model $model): Valida, actualiza, redirige
  ☐ destroy(Model $model): Elimina y redirige a index

VISTAS:
  ☐ index.blade.php: Tabla con links a show/edit
  ☐ create.blade.php: Formulario POST a store()
  ☐ edit.blade.php: Formulario PUT a update() con parámetro explícito
  ☐ show.blade.php: Detalle + botones edit/delete
  
RUTAS:
  ☐ Route::resource('resource-name', ControllerClass::class)
  ☐ Rutas específicas ANTES del resource
  ☐ Bajo middleware(['auth'])
  ☐ Con prefix y name correctos

TESTING:
  ☐ GET /create → Abre formulario vacío
  ☐ POST /store → Crea registro
  ☐ GET /show/ID → Muestra detalle
  ☐ GET /edit/ID → Abre formulario con datos
  ☐ PUT /update/ID → Actualiza registro
  ☐ DELETE /destroy/ID → Elimina registro
  ☐ Enviar datos inválidos → Retorna a form CON datos previos
  ☐ Errores de validación muestran mensajes
```

---

## 6️⃣ PATRONES COMUNES Y SUS SOLUCIONES

### Patrón 1: Crear/Editar en misma vista

```blade
{{-- resources/views/recursos/form.blade.php --}}
@php
    $isEdit = isset($modelo) && $modelo;
    $route = $isEdit 
        ? route('parametros.modelos.update', ['modelo' => $modelo->id])
        : route('parametros.modelos.store');
    $method = $isEdit ? 'PUT' : 'POST';
    $title = $isEdit ? 'Editar' : 'Crear';
@endphp

<form action="{{ $route }}" method="POST">
    @csrf
    @if($isEdit)
        @method($method)
    @endif
    <!-- campos -->
</form>

{{-- Controller: Both create() and edit() return the same view --}}
public function create(): View
{
    return view('parametros.recursos.form', ['modelo' => null]);
}

public function edit(Modelo $modelo): View
{
    return view('parametros.recursos.form', compact('modelo'));
}
```

### Patrón 2: Formularios con datos en errores

```php
// Cuando validación falla, Laravel automáticamente:
// 1. Redirige a la vista anterior
// 2. Pasa el modelo por implicit binding
// 3. Pasa los errores en $errors
// 4. Usa old() para repoblar campos

// En la vista:
<input type="text" name="nombre" value="{{ old('nombre', $modelo->nombre ?? '') }}">
@error('nombre')
    <span class="error">{{ $message }}</span>
@enderror
```

---

## 7️⃣ COMANDOS DE VERIFICACIÓN

```bash
# Ver todas las rutas
php artisan route:list

# Filtrar por patrón
php artisan route:list | grep parametros

# Ver solo rutas de recurso específico
php artisan route:list --name=parametros.tipos-equipos
```

---

## 8️⃣ REFERENCIAS RÁPIDAS

| Problema | Causa | Solución |
|----------|-------|----------|
| "Missing parameter: tipos_equipo" | Paso incorrecto de parámetro en route() | Use `route('..', ['tipos_equipo' => $model->id])` |
| Variable $model no disponible en vista | Controller no la pasa en compact() | Add `compact('model', 'related')` |
| Errores de validación pierden datos | Formulario no usa old() | Use `value="{{ old('field', $model->field) }}"` |
| Implicit binding no funciona | route key incorrecto | Verificar `getRouteKeyName()` en modelo |
| Ruta específica no se ejecuta | Resource route intercepta primero | Poner rutas específicas ANTES del resource |

---

**Autor:** AI Assistant  
**Versión:** 1.0  
**Última actualización:** 8 de Mayo, 2026
