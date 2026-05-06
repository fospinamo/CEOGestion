# 🚀 ALTERNATIVAS SIN TERMINAL CPANEL

## 📋 RESUMEN

Tienes **3 alternativas** para restaurar la BD sin acceso a Terminal:

| Opción | Acceso | Dificultad | Seguridad | Recomendación |
|--------|--------|-----------|----------|---------------|
| **Panel Web** | Navegador | ⭐⭐ Media | 🟢 Buena | ✅ PRIMERA OPCIÓN |
| **phpMyAdmin SQL** | phpMyAdmin | ⭐ Fácil | 🟡 Media | ✅ SEGUNDA OPCIÓN |
| **Soporte Hosting** | Ticket | ⭐⭐ Media | 🟢 Excelente | ⏳ TERCERA OPCIÓN |

---

## ✅ OPCIÓN 1: PANEL WEB (RECOMENDADO)

### Ventajas
- ✅ Interfaz visual y bonita
- ✅ Ejecuta migraciones directamente
- ✅ Verifica estado en tiempo real
- ✅ No requiere conocimiento de SQL

### Paso 1: Preparar archivo `.env`

Edita en cPanel (File Manager):  
`/public_html/.env`

Agrega al final:
```env
MIGRATION_TOKEN=tu_token_secreto_12345
```

**Generar token seguro:**
```bash
# En la terminal local
php -r "echo bin2hex(random_bytes(16));"
# Resultado: algo como: a3f8b2c9d4e1f0a5b3c2d1e0f4a5b3c2
```

### Paso 2: Subir archivo (si es necesario)

El archivo `clean_database.php` ya está en tu código.

### Paso 3: Acceder al Panel

Abre en navegador:

```
https://tu-dominio.com/migration-panel?token=tu_token_secreto_12345
```

Deberías ver:
```
🚀 Panel de Migraciones
=====================================

[✅ EJECUTAR MIGRACIONES] [🌱 EJECUTAR SEEDERS]
[🔍 VERIFICAR BD]         [📥 DESCARGAR SQL]
```

### Paso 4: Ejecutar en Orden

1. **Click: ✅ EJECUTAR MIGRACIONES**
   - Espera a que termine
   - Verifica: "✅ MIGRACIONES COMPLETADAS"

2. **Click: 🌱 EJECUTAR SEEDERS**
   - Espera a que termine
   - Verifica: "✅ SEEDERS COMPLETADOS"

3. **Click: 🔍 VERIFICAR BD**
   - Verifica todas las maestras están presentes
   - Verifica datos operacionales en 0

### Resultado Esperado

```
✅ MAESTRAS:
  ✅ paises: 1
  ✅ departamentos: 32
  ✅ municipios: 36
  ✅ barrios: 90
  ✅ tipos_equipos: 15
  ✅ categorias: 5
  ✅ estado_servicios: 6
  ✅ roles: 3
  ✅ permissions: 32
  ✅ role_permissions: 41

🗑️ OPERACIONALES (vacíos):
  ✅ servicios: 0
  ✅ equipos: 0
  ✅ empresas: 0
  ✅ clientes: 0
  ✅ sedes: 0
  ✅ areas: 0
```

### Paso 5: Limpiar por Seguridad

1. Ir a File Manager en cPanel
2. Navegar a: `/public_html/migration-panel`
3. Eliminar o renombrar archivo
4. También eliminar `/public_html/clean_database.php`

---

## ✅ OPCIÓN 2: PHPMYADMIN SQL (ALTERNATIVA)

Si el Panel Web no funciona, usa esta opción.

### Paso 1: Descargar SQL

En tu computadora, descarga:
- `insert_maestras_phpmyadmin.sql` (generado automáticamente)

O genéralo ejecutando:
```bash
php generate_sql_inserts.php
```

### Paso 2: Ir a phpMyAdmin

1. En cPanel: **phpMyAdmin**
2. Selecciona BD: `simotec_ceogestion_prod`
3. Click: **SQL** (pestaña superior)

### Paso 3: Copiar y Pegar SQL

1. Abre `insert_maestras_phpmyadmin.sql` en editor de texto
2. Copia TODO el contenido
3. En phpMyAdmin, pega en la caja de texto SQL grande
4. Scroll hasta abajo: Click **Ejecutar** (Go)

### Paso 4: Verificar Resultado

En phpMyAdmin:
1. Selecciona tabla `paises`
2. Debe mostrar: **1 registro**
3. Selecciona tabla `departamentos`
4. Debe mostrar: **32 registros**

### ⚠️ Si hay error

Si phpMyAdmin dice: "Cannot truncate a table referenced in a foreign key constraint"

**Solución:**
1. Copia SOLO esto al inicio:
```sql
SET FOREIGN_KEY_CHECKS=0;
```
2. Ejecuta
3. Luego copia TODO el archivo SQL
4. Ejecuta
5. Finalmente copia:
```sql
SET FOREIGN_KEY_CHECKS=1;
```
6. Ejecuta

---

## ✅ OPCIÓN 3: CONTACTAR SOPORTE HOSTING

Si las dos opciones anteriores no funcionan.

### Email a Soporte

Asunto: **"Ejecutar comandos PHP en producción"**

Cuerpo:
```
Hola,

Necesito que ejecuten 2 comandos en mi hosting:

cd /home/miusuario/public_html
php artisan migrate --force
php artisan db:seed --force

Dominio: tu-dominio.com
BD: simotec_ceogestion_prod
Usuario: tu_usuario

Gracias
```

### Alternativa: Crear Ticket

En cPanel:
1. **Help & Support** > **Support Tickets**
2. Subject: "Execute PHP artisan commands"
3. Description: lo anterior
4. Crear ticket
5. Esperar respuesta

---

## 🔒 SEGURIDAD

### IMPORTANTE: Después de usar cualquier opción

**Elimina TODOS estos archivos:**

1. `/public_html/clean_database.php` - ❌ Eliminar
2. `/public_html/generate_sql_inserts.php` - ❌ Eliminar
3. `/public_html/migration-panel.blade.php` - ❌ Eliminar
4. `/public_html/db/clean_import_maestras.php` - ❌ Eliminar
5. `/public_html/db/backup_maestras.sql` - ❌ Eliminar
6. `/public_html/db/limpiar_produccion.sql` - ❌ Eliminar
7. `/public_html/db/insert_maestras_phpmyadmin.sql` - ❌ Eliminar

**En cPanel:**
1. File Manager
2. Buscar archivos anteriores
3. Eliminar

**Cambiar token en .env:**
```env
# De:
MIGRATION_TOKEN=tu_token_secreto_12345

# A:
MIGRATION_TOKEN=disabled_after_migration
```

---

## 🆘 TROUBLESHOOTING

### Error 1: "Token inválido"

**Causa:** El token en la URL no coincide con `.env`

**Solución:**
```bash
# Generar nuevo token
php -r "echo bin2hex(random_bytes(16));"

# Actualizar en .env
MIGRATION_TOKEN=nuevo_token_aqui
```

### Error 2: "500 Internal Server Error"

**Causa:** PHP no puede ejecutar migraciones

**Solución:**
```bash
# Verificar logs
tail -100 storage/logs/laravel.log

# Limpiar cache
php artisan cache:clear
php artisan config:clear
```

### Error 3: "Class 'MigrationController' not found"

**Causa:** Rutas no fueron agregadas correctamente

**Solución:**
1. Verifica `routes/web.php`
2. Asegúrate que tiene:
```php
use App\Http\Controllers\MigrationController;
Route::get('/api/migrate-db', [MigrationController::class, 'runMigrations']);
```
3. Ejecuta: `php artisan route:cache && php artisan route:clear`

### Error 4: "Cannot execute migrations"

**Causa:** Base de datos conectada pero hay problemas con esquema

**Solución:**
1. Usa Option 2 (phpMyAdmin SQL)
2. O contacta soporte hosting

---

## 📊 COMPARATIVA DETALLADA

### Panel Web
```
✅ Ventajas:
- Interfaz bonita
- Retroalimentación en tiempo real
- Verifica estado automáticamente
- Fácil para usuario no técnico

❌ Desventajas:
- Requiere PHP activo
- Requiere configurar token
- Requiere control sobre routes/web.php
```

### phpMyAdmin SQL
```
✅ Ventajas:
- Directo, sin intermediarios
- Acceso directo a phpMyAdmin que ya tienes
- Fácil auditoria (ves exactamente qué SQL se ejecuta)
- Funciona incluso si PHP tiene problemas

❌ Desventajas:
- Requiere entender SQL un poco
- Manual (copiar/pegar)
- phpMyAdmin a veces es lento con archivos grandes
```

### Soporte Hosting
```
✅ Ventajas:
- No requiere hacer nada técnico
- Ellos manejan errores
- Profesional

❌ Desventajas:
- Lento (esperar respuesta)
- Pueden no conocer Laravel
- Requiere explicar qué hacer
```

---

## ✅ CHECKLIST FINAL

- [ ] Elegí una opción (Panel Web recomendado)
- [ ] Preparé token seguro
- [ ] Actualicé `.env` con token
- [ ] Ejecuté migraciones
- [ ] Ejecuté seeders
- [ ] Verifiqué BD con estado esperado
- [ ] Eliminé archivos de migración por seguridad
- [ ] Probé acceso a https://tu-dominio.com/
- [ ] Hice login con admin@ceogestion.com / password123
- [ ] Dashboard carga sin errores 500

---

**Generado:** 6 Mayo 2026  
**Versión:** v3 - Sin Terminal cPanel  
**Estado:** ✅ PRODUCCIÓN LISTA
