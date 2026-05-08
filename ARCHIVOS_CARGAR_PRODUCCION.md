# 📦 ARCHIVOS PARA SUBIR A PRODUCCIÓN

## ✅ ARCHIVOS A CARGAR (Lista Completa)

### 1️⃣ CONTROLADOR (ACTUALIZADO)
**Ruta en servidor:** `app/Http/Controllers/AuthController.php`

**Cambios:** Obtiene empresa y la pasa al login

**Acciones:**
- Descargar de local
- Subir vía File Manager a: `app/Http/Controllers/`

---

### 2️⃣ MODELO (ACTUALIZADO)
**Ruta en servidor:** `app/Models/Empresa.php`

**Cambios:** Agregados campos `logo` y `descripcion` al `$fillable`

**Acciones:**
- Descargar de local
- Subir vía File Manager a: `app/Models/`

---

### 3️⃣ VISTA (COMPLETAMENTE NUEVA)
**Ruta en servidor:** `resources/views/auth/login.blade.php`

**Cambios:** Rediseñada para mostrar logos dinámicos

**Acciones:**
- Descargar de local
- Subir vía File Manager a: `resources/views/auth/`

---

### 4️⃣ SEEDER (ACTUALIZADO)
**Ruta en servidor:** `database/seeders/EmpresaSeeder.php`

**Cambios:** Incluye nuevos campos

**Acciones:**
- Descargar de local
- Subir vía File Manager a: `database/seeders/`

---

### 5️⃣ MIGRACIÓN (NUEVA)
**Ruta en servidor:** `database/migrations/2026_05_06_000001_add_logo_to_empresas_table.php`

**Cambios:** Nueva migración para agregar campos a tabla

**Acciones:**
- Descargar de local
- Subir vía File Manager a: `database/migrations/`

---

## 🗄️ EJECUTAR EN phpMyAdmin

### SQL para ejecutar en producción:

```sql
-- Ejecutar en phpMyAdmin → SQL
ALTER TABLE `empresas` ADD COLUMN `logo` VARCHAR(255) NULL AFTER `email`;
ALTER TABLE `empresas` ADD COLUMN `descripcion` TEXT NULL AFTER `logo`;

-- Verificar que se agregaron
SELECT `id`, `nombre`, `email`, `logo`, `descripcion` FROM `empresas`;
```

---

## 📁 ESTRUCTURA DE DIRECTORIOS EN PRODUCCIÓN

```
htdocs/ceogestion/ (o tu dominio)
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── AuthController.php          ✅ SUBIR
│   └── Models/
│       └── Empresa.php                     ✅ SUBIR
│
├── database/
│   ├── migrations/
│   │   └── 2026_05_06_000001_add_logo_to_empresas_table.php  ✅ SUBIR
│   └── seeders/
│       └── EmpresaSeeder.php               ✅ SUBIR
│
├── resources/
│   └── views/
│       └── auth/
│           └── login.blade.php             ✅ SUBIR
│
└── storage/
    └── app/
        └── public/
            └── empresas/
                └── logo-simotec.png        ✅ SUBIR (tu logo)
```

---

## 🚀 PASO A PASO

### PASO 1: Subir Archivos PHP (5 archivos)

**Método:** cPanel File Manager

1. Descargar estos archivos de local:
   ```
   AuthController.php
   Empresa.php
   login.blade.php
   EmpresaSeeder.php
   2026_05_06_000001_add_logo_to_empresas_table.php
   ```

2. En cPanel File Manager:
   - Navegar a cada carpeta
   - Subir archivo (reemplazar el existente si hay)
   - Confirmar permisos (644 para archivos PHP)

3. **Ruta base en producción:** `/home/tu_usuario/public_html/` (variaría según tu configuración)

---

### PASO 2: Ejecutar SQL en phpMyAdmin

1. cPanel → phpMyAdmin
2. Seleccionar BD: `simotec_ceogestion_prod`
3. Pestaña: **SQL**
4. Copiar y pegar:
   ```sql
   ALTER TABLE `empresas` ADD COLUMN `logo` VARCHAR(255) NULL AFTER `email`;
   ALTER TABLE `empresas` ADD COLUMN `descripcion` TEXT NULL AFTER `logo`;
   ```
5. Click: **Ejecutar**

---

### PASO 3: Subir Logo de Empresa

1. cPanel File Manager
2. Navegar a: `storage/app/public/`
3. Crear carpeta: `empresas` (si no existe)
4. Subir tu logo: `logo-simotec.png`
   - Formato: PNG o JPG
   - Tamaño: 200-300px ancho
   - Peso: < 200 KB

---

### PASO 4: Actualizar BD con Ruta del Logo

En phpMyAdmin SQL:
```sql
UPDATE `empresas` 
SET `logo` = 'empresas/logo-simotec.png'
WHERE `id` = 1;
```

---

### PASO 5: Limpiar Cache (IMPORTANTE)

En phpMyAdmin SQL:
```sql
-- Opcional, pero recomendado para ver cambios inmediatos
DELETE FROM `cache`;
DELETE FROM `cache_locks`;
```

O si tienes acceso a web panel de migración:
- Ejecutar: `php artisan cache:clear`

---

## 📊 RESUMEN DE ARCHIVOS

| # | Archivo | Tipo | Acción |
|---|---------|------|--------|
| 1 | `AuthController.php` | PHP | Subir a `app/Http/Controllers/` |
| 2 | `Empresa.php` | PHP | Subir a `app/Models/` |
| 3 | `login.blade.php` | Blade | Subir a `resources/views/auth/` |
| 4 | `EmpresaSeeder.php` | PHP | Subir a `database/seeders/` |
| 5 | `2026_05_06_000001_add_logo_to_empresas_table.php` | PHP | Subir a `database/migrations/` |
| 6 | `logo-simotec.png` | IMG | Subir a `storage/app/public/empresas/` |

---

## ⚡ ORDEN RECOMENDADO

```
1️⃣  Ejecutar SQL en phpMyAdmin (agregar campos)
2️⃣  Subir logo a storage/app/public/empresas/
3️⃣  Actualizar ruta en BD (UPDATE empresas SET logo = ...)
4️⃣  Subir archivos PHP (5 archivos)
5️⃣  Limpiar cache
6️⃣  Probar: Ir a login y verificar
```

---

## ✅ VERIFICACIÓN FINAL

1. **Ir a login:** `https://gestion.simotec.com.co/login`

2. Debería verse:
   - ✅ Logo de Simotec (lado izquierdo)
   - ✅ Logo de CEO Gestion (lado derecho)
   - ✅ Título "CEOGestion"
   - ✅ Formulario normal

3. **Si no se ve el logo:**
   ```sql
   -- Verificar en phpMyAdmin
   SELECT id, nombre, logo FROM empresas WHERE id = 1;
   ```

---

## 🔒 NOTAS IMPORTANTES

⚠️ **Permisos:** Los archivos PHP deben tener permisos 644
⚠️ **Carpeta storage:** Asegurar que existe `storage/app/public/empresas/`
⚠️ **Ruta correcta:** El campo `logo` debe contener: `empresas/nombre-archivo.png`
⚠️ **Sin Terminal:** En producción NO puedes usar `php artisan` (tienes cPanel File Manager + phpMyAdmin)

---

## 📥 DESCARGAR ARCHIVOS (Desde tu PC Local)

Todos están en: `C:\xampp\htdocs\CEOGestion\`

```
C:\xampp\htdocs\CEOGestion\
├── app\Http\Controllers\AuthController.php
├── app\Models\Empresa.php
├── resources\views\auth\login.blade.php
├── database\seeders\EmpresaSeeder.php
└── database\migrations\2026_05_06_000001_add_logo_to_empresas_table.php
```

---

## 🎯 RESULTADO ESPERADO

Después de seguir todos los pasos, el login mostrará:

```
╔════════════════════════════════════════╗
║  [Logo Simotec]  [Logo CEO Gestion]   ║
║                                        ║
║           CEOGestion                   ║
║     Sistema de Gestión Empresarial     ║
║                                        ║
║ Correo: [                           ]  ║
║ Contraseña: [                       ]  ║
║                                        ║
║       [ Ingresar ]                     ║
╚════════════════════════════════════════╝
```

---

**¿Necesitas que desglose más algún paso? ¿Dónde es tu hosting? 🚀**
