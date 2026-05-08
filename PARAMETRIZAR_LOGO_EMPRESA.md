# 🏢 PARAMETRIZAR LOGO Y NOMBRE DE EMPRESA EN LOGIN

## 📋 CAMBIOS REALIZADOS

### 1. **Migración Nueva** 
`database/migrations/2026_05_06_000001_add_logo_to_empresas_table.php`

Agrega dos campos a la tabla `empresas`:
- `logo` (string, nullable) - Ruta del logo
- `descripcion` (text, nullable) - Descripción de la empresa

### 2. **Modelo Empresa Actualizado**
`app/Models/Empresa.php`

Se agregaron campos `logo` y `descripcion` a `$fillable`

### 3. **Controlador AuthController Actualizado**
`app/Http/Controllers/AuthController.php`

Método `showLogin()` ahora obtiene la empresa:
```php
public function showLogin()
{
    $empresa = Empresa::where('estado', true)->first() ?? Empresa::first();
    return view('auth.login', ['empresa' => $empresa]);
}
```

### 4. **Vista Login Actualizada**
`resources/views/auth/login.blade.php`

Ahora muestra:
- ✅ Logo de la empresa (si existe)
- ✅ Nombre de la empresa
- ✅ Logo de CEO Gestion (fijo)
- ✅ Fallback a emoji 🏢 si no hay logo

---

## 🚀 PASOS PARA IMPLEMENTAR EN PRODUCCIÓN

### PASO 1: Ejecutar Migración

```bash
php artisan migrate --force
```

O vía **phpMyAdmin** - Ejecutar SQL:

```sql
ALTER TABLE `empresas` ADD COLUMN `logo` VARCHAR(255) NULL AFTER `email`;
ALTER TABLE `empresas` ADD COLUMN `descripcion` TEXT NULL AFTER `logo`;
```

### PASO 2: Subir Logo de la Empresa

#### Opción A: Vía Laravel (Local/Desarrollo)
```php
// En tinker o controlador
$empresa = Empresa::first();
$empresa->update([
    'logo' => 'empresas/logo-simotec.png'
]);
```

#### Opción B: Vía phpMyAdmin (Producción)
```sql
UPDATE `empresas` 
SET `logo` = 'empresas/logo-simotec.png',
    `descripcion` = 'Simotec Consultores - Soluciones Tecnológicas'
WHERE `id` = 1;
```

### PASO 3: Subir Archivo de Logo

**Ubicación:** `storage/app/public/empresas/`

#### En Local (XAMPP):
```
C:\xampp\htdocs\CEOGestion\storage\app\public\empresas\logo-simotec.png
```

#### En Producción (cPanel):
1. File Manager en cPanel
2. Navegar a: `storage/app/public/`
3. Crear carpeta: `empresas`
4. Subir archivo: `logo-simotec.png`

### PASO 4: Crear Enlace Simbólico

Para que Laravel sirva los archivos de `storage/app/public/`:

```bash
php artisan storage:link
```

O manualmente (si no funciona el artisan):
- Linux/Mac: `ln -s storage/app/public public/storage`
- Windows (cmd admin): `mklink /D C:\xampp\htdocs\CEOGestion\public\storage C:\xampp\htdocs\CEOGestion\storage\app\public`

---

## 📐 ESTRUCTURA DE CARPETAS

```
project-root/
├── storage/
│   └── app/
│       └── public/
│           └── empresas/
│               ├── logo-simotec.png
│               └── logo-otro.png
├── public/
│   ├── index.php
│   └── storage → symlink a storage/app/public
└── resources/
    └── views/
        └── auth/
            └── login.blade.php
```

---

## 🎨 TAMAÑO RECOMENDADO DEL LOGO

- **Ancho:** 200-400px
- **Alto:** 80-150px
- **Formato:** PNG (transparencia) o JPG
- **Tamaño archivo:** < 500 KB
- **Relación de aspecto:** 2.5:1 o 3:1

---

## ✅ VERIFICACIÓN EN PRODUCCIÓN

### 1. Ver datos de la empresa

```sql
SELECT id, nombre, email, logo, descripcion FROM empresas WHERE id = 1;
```

### 2. Verificar que el logo existe

```
https://gestion.simotec.com.co/storage/empresas/logo-simotec.png
```

Debería mostrar la imagen.

### 3. Probar login

Ir a: `https://gestion.simotec.com.co/login`

Debería verse:
- Logo de Simotec (lado izquierdo)
- Logo de CEO Gestion (lado derecho)
- Nombre "CEOGestion" en el centro

---

## 🔧 SOLUCIÓN DE PROBLEMAS

### El logo no se ve en el login

**Causa:** El archivo no existe o la ruta es incorrecta

**Solución:**
```sql
-- Verificar la ruta guardada
SELECT logo FROM empresas WHERE id = 1;

-- Actualizar si es necesario
UPDATE empresas 
SET logo = 'empresas/mi-logo.png' 
WHERE id = 1;
```

### Error: "No such file or directory"

**Causa:** El enlace simbólico no existe

**Solución:** Crear manualmente:
```bash
# Desde la raíz del proyecto
cd public
# Windows (cmd como admin)
mklink /D storage ..\storage\app\public
```

### El archivo está pero no se ve

**Causa:** El archivo no está en `storage/app/public/`

**Verificar:**
- Confirmar ruta exacta en cPanel File Manager
- Confirmar nombre del archivo sin espacios
- Confirmar permisos de lectura (644 o 755)

---

## 📝 EJEMPLO COMPLETO

### BD (SQL)
```sql
UPDATE empresas 
SET 
  logo = 'empresas/simotec-logo.png',
  descripcion = 'Soluciones tecnológicas para tu negocio',
  nombre = 'Simotec Consultores',
  email = 'info@simotec.com.co',
  telefono = '+57 (1) 5555-5555'
WHERE id = 1;
```

### Archivo
Subir a: `storage/app/public/empresas/simotec-logo.png`

### Resultado en Login
```
┌─────────────────────────────────────┐
│  [Logo Simotec]  [Logo CEO]         │
│  CEOGestion                         │
│  Sistema de Gestión Empresarial     │
│                                     │
│  Correo: ________________           │
│  Contraseña: ________________       │
│                                     │
│  [ Ingresar ]                       │
└─────────────────────────────────────┘
```

---

## 🔐 NOTAS IMPORTANTES

1. **En Producción:** Los logos deben estar en `storage/app/public/` para ser accesibles públicamente
2. **Permisos:** Asegurar que la carpeta tiene permisos de lectura (644)
3. **Tamaño:** Optimizar imágenes para cargar rápido (< 100 KB ideal)
4. **Formato:** PNG con fondo transparente se ve mejor
5. **Fallback:** Si no hay logo, se muestra un emoji 🏢 automáticamente

---

## 💾 RESUMEN DE ARCHIVOS MODIFICADOS

| Archivo | Cambio |
|---------|--------|
| Migración nueva | ✅ Agrega campos logo y descripcion |
| Empresa.php | ✅ Agrega campos a fillable |
| AuthController.php | ✅ Obtiene empresa y la pasa al login |
| login.blade.php | ✅ Muestra logos dinámicamente |
| EmpresaSeeder.php | ✅ Incluye nuevos campos |

---

## 🎯 PRÓXIMOS PASOS

1. **Local:** `php artisan migrate`
2. **Producción:** Ejecutar SQL en phpMyAdmin
3. **Producción:** Subir logo vía File Manager
4. **Producción:** Ejecutar migration vía phpMyAdmin SQL
5. **Verificar:** Probar login y ver logos
