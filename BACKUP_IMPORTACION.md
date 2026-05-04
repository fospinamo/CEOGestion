# 📦 BACKUP E IMPORTACIÓN DE BASE DE DATOS

## Situación Actual
- **BD Local**: `ceogestion_db` (con datos operacionales)
- **BD Producción**: `ceogestion_prod` (vacía, esperando maestras)
- **Maestras a preservar**: paises, departamentos, municipios, barrios, tipos_equipos, categorias, estado_servicios, roles, permissions

---

## FASE 1: BACKUP LOCAL (5 minutos)

### ✅ Ya completado:
```bash
cd C:\xampp\mysql\bin
.\mysqldump -u root ceogestion_db paises departamentos municipios barrios tipos_equipos categorias estado_servicios roles permissions role_permissions > "C:\xampp\htdocs\CEOGestion\backup_maestras.sql"
```

**Resultado**: Archivo `backup_maestras.sql` (61.71 KB)

---

## FASE 2: SUBIR ARCHIVOS AL SERVIDOR (3 minutos)

### Archivos a subir:
1. **backup_maestras.sql** - Datos maestras
2. **import_maestras.php** - Script de importación

### Pasos en cPanel:

1. **Abre cPanel File Manager**
   - URL: `https://cpanel.hosting-co.com/` (tu servidor)
   - Login: tu usuario cPanel

2. **Crea carpeta `/db/`**
   - Ve a: `/public_html/`
   - Botón derecho → **Create Folder** → `db`

3. **Sube archivos**
   - Entra a carpeta `/public_html/db/`
   - Upload → Selecciona `backup_maestras.sql`
   - Upload → Selecciona `import_maestras.php`

### Resultado esperado:
```
/public_html/db/
  ├── backup_maestras.sql
  ├── import_maestras.php
```

---

## FASE 3: IMPORTAR EN SERVIDOR (2 minutos)

### Opción A: Script PHP (RECOMENDADO - sin SSH)

1. **Accede al script importador:**
   ```
   https://tu-dominio.com/db/import_maestras.php
   ```

2. **Espera a que termine (1-2 minutos)**
   - Verás progreso: `✓ Comando 1`, `✓ Comando 2`, etc.
   - Al final: `✅ IMPORTACIÓN COMPLETADA`

3. **Verifica resultado:**
   - Busca: `✅ Comandos ejecutados: X`
   - Busca: tabla count (ej: `paises (1 registros)`)

4. **Elimina el script por seguridad:**
   - File Manager → `/public_html/db/import_maestras.php`
   - Delete

### Opción B: phpMyAdmin (si disponible)

1. Abre cPanel → **phpMyAdmin**
2. Selecciona BD: `ceogestion_prod`
3. Pestaña **Import**
4. Selecciona: `backup_maestras.sql`
5. Click: **Go**

### Opción C: Contacta soporte (si no funciona)

Crea ticket:
```
Asunto: Importar base de datos SQL
Archivo: /public_html/db/backup_maestras.sql
BD destino: ceogestion_prod
Descripción: "Importar archivos maestras: paises, departamentos, municipios, barrios, tipos_equipos, categorias, estado_servicios, roles, permissions"
```

---

## FASE 4: VERIFICAR IMPORTACIÓN (1 minuto)

### En cPanel → Bases de Datos MySQL:

1. Selecciona: `ceogestion_prod`
2. Click: **phpMyAdmin** (ver datos)
3. Verifica tablas presentes:
   - [ ] `paises` - no vacía
   - [ ] `departamentos` - no vacía
   - [ ] `municipios` - no vacía
   - [ ] `barrios` - no vacía
   - [ ] `tipos_equipos` - no vacía
   - [ ] `categorias` - no vacía
   - [ ] `estado_servicios` - no vacía
   - [ ] `roles` - no vacía (debe tener: Admin, Técnico, Agente)
   - [ ] `permissions` - no vacía
   - [ ] `role_permissions` - no vacía

4. **NO deben existir:**
   - servicios
   - equipos
   - empresas
   - clientes
   - sedes
   - areas
   - usuarios (excepto admin que crearemos después)

---

## FASE 5: CONFIGURAR .env EN SERVIDOR (2 minutos)

### En cPanel File Manager:

1. Ve a: `/public_html/`
2. Abre archivo: `.env`
3. Busca sección `DB_*` y actualiza:

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ceogestion_prod
DB_USERNAME=tu_usuario_cpanel
DB_PASSWORD=tu_password_cpanel
```

💡 **Obtener credenciales:**
- cPanel → **Bases de Datos MySQL**
- Copiar usuario y contraseña que creaste

4. Guardar: **Ctrl+S** en File Manager

---

## FASE 6: EJECUTAR MIGRACIONES FINALES (5 minutos)

### Contactar soporte con este ticket:

```
Asunto: Ejecutar comandos Laravel en producción

Ejecutar estos comandos en /public_html/:

php artisan migrate --force
php artisan db:seed --force
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

Después validar que application carga sin errores
```

---

## FASE 7: VERIFICAR ACCESO (1 minuto)

1. Abre: `https://tu-dominio.com/`
2. Login: `admin@ceogestion.com` / `password123`
3. Verifica que Dashboard carga sin errores

---

## 🔧 TROUBLESHOOTING

### ❌ "Unknown database 'ceogestion_prod'"
- Solución: Crear BD en cPanel → MySQL Databases (si no existe)

### ❌ "Access denied for user"
- Solución: Verificar credenciales en cPanel Databases
- Asegurarse que usuario tiene permisos sobre BD

### ❌ "Table already exists"
- Esto es normal (DROP TABLE IF EXISTS maneja)
- Continuar con importación

### ❌ Script import_maestras.php no ejecuta
- Alternativa: Usar phpMyAdmin
- O contactar soporte

### ❌ Falta tabla roles/permissions
- Ejecutar: `php artisan migrate --force`
- Luego: `php artisan db:seed --force`

---

## 📋 RESUMEN RÁPIDO

| Paso | Acción | Tiempo |
|------|--------|--------|
| 1 | Crear backup local | ✅ Done |
| 2 | Subir archivos al servidor | 3 min |
| 3 | Importar con import_maestras.php | 2 min |
| 4 | Verificar tablas en phpMyAdmin | 1 min |
| 5 | Actualizar .env con credenciales BD | 2 min |
| 6 | Contactar soporte para migraciones | 5 min (async) |
| 7 | Verificar acceso a https://tu-dominio.com | 1 min |

**⏱️ TIEMPO TOTAL**: ~14 minutos (+ espera soporte)

---

## ✅ ARCHIVOS GENERADOS

- `backup_maestras.sql` - Backup de maestras (61.71 KB)
- `import_maestras.php` - Script PHP para importar
- `BACKUP_IMPORTACION.md` - Este documento
